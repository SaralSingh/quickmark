<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Controllers\Controller;
use App\Models\ListModel;
use App\Models\Person;
use App\Models\PramanSession;
use App\Models\Presence;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;



class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => $request->user()->only(['id', 'name', 'email'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createList(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $list = ListModel::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'List created successfully',
            'data' => $list
        ], 201);
    }



    public function getLists(Request $request)
    {
        $lists = ListModel::where('user_id', $request->user()->id)
            ->select('id', 'name')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'total' => $lists->count(),
            'data' => $lists
        ]);
    }

    public function addPerson(Request $request, $listId)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100']
        ]);

        $person = Person::create([
            'list_id' => $listId,
            'name' => $validated['name']
        ]);

        return response()->json([
            'status' => true,
            'data' => $person
        ], 201);
    }


    public function bulkAddPeople(Request $request, $listId)
    {
        $validated = $request->validate([
            'names' => ['required', 'array'],
            'names.*' => ['required', 'string', 'max:100']
        ]);

        // Optional safety: ensure list belongs to this user
        $list = $request->user()->lists()->where('id', $listId)->firstOrFail();

        $data = [];

        foreach ($validated['names'] as $name) {
            $data[] = [
                'list_id' => $listId,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Person::insert($data);

        return response()->json([
            'status' => true,
            'message' => 'People added successfully'
        ], 201);
    }


    public function getPeople($listId)
    {
        $people = Person::where('list_id', $listId)->get();

        return response()->json([
            'status' => true,
            'data' => $people
        ]);
    }


    public function startSession(Request $request, $listId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $today = Carbon::today()->toDateString();
        $title = trim($request->title);

        // Check existing session
        $session = PramanSession::where('list_id', $listId)
            ->where('session_date', $today)
            ->where('title', $title)
            ->first();

        // If not exists → create
        if (!$session) {
            $session = PramanSession::create([
                'list_id' => $listId,
                'session_date' => $today,
                'title' => $title,
                'status' => 'active',
            ]);

            // 🔥 IMPORTANT PART — create attendance sheet
            $peopleIds = Person::where('list_id', $listId)->pluck('id');

            $rows = $peopleIds->map(function ($pid) use ($session) {
                return [
                    'praman_session_id' => $session->id,
                    'person_id' => $pid,
                    'is_present' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            Presence::insert($rows);
        }

        return response()->json([
            'data' => $session
        ]);
    }

    public function getSessions($listId)
    {
        $sessions = PramanSession::where('list_id', $listId)
            ->orderByDesc('session_date')
            ->orderByDesc('id')
            ->get(['id', 'title', 'session_date', 'status']);

        return response()->json([
            'data' => $sessions
        ]);
    }

    public function getPeopleSession(PramanSession $session)
    {
        // Get all people of this session's list
        $people = Person::where('list_id', $session->list_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'session' => [
                'id' => $session->id,
                'title' => $session->title,
                'session_date' => $session->session_date,
                'status' => $session->status,
            ],
            'people' => $people,
        ]);
    }

    public function storePresence(Request $request, PramanSession $session)
    {
        // 1️⃣ Block closed session immediately (immutability rule)
        if ($session->status === 'closed') {
            return response()->json([
                'message' => 'Session already closed'
            ], 403);
        }

        // 2️⃣ Validate input + ownership + soft-delete safety
        $validated = $request->validate([
            'person_id' => [
                'required',
                Rule::exists('people', 'id')
                    ->whereNull('deleted_at')
                    ->where('list_id', $session->list_id),
            ],
            'is_present' => 'required|boolean',
            'final_save' => 'nullable|boolean',
        ]);

        // 3️⃣ Atomic transaction (consistency guaranteed)
        DB::transaction(function () use ($validated, $session) {

            // 🔑 Fetch person ONCE (authoritative source)
            $person = Person::select('id', 'name')
                ->where('id', $validated['person_id'])
                ->where('list_id', $session->list_id)
                ->firstOrFail();

            // 🔑 Update presence + SNAPSHOT person_name
            $updated = Presence::where('praman_session_id', $session->id)
                ->where('person_id', $person->id)
                ->update([
                    'is_present'  => $validated['is_present'],
                    'person_name' => $person->name, // HISTORY SNAPSHOT
                ]);

            // 🔴 Presence row must already exist
            if ($updated === 0) {
                abort(404, 'Presence record not found');
            }

            // 4️⃣ Final save → close session permanently
            if (!empty($validated['final_save'])) {
                $session->update([
                    'status' => 'closed'
                ]);
            }
        });

        return response()->json([
            'message' => 'Attendance updated successfully'
        ], 200);
    }

    public function getSessionAttendance(string $id)
    {
        // 1️⃣ Fetch session (authoritative)
        $session = PramanSession::select('id', 'title', 'session_date', 'status')
            ->findOrFail($id);

        // 2️⃣ Fetch attendance WITHOUT joins (history-safe)
        $presences = Presence::select(
            'person_id',
            'person_name',
            'is_present'
        )
            ->where('praman_session_id', $id)
            ->get();

        // 3️⃣ Map response (null-safe forever)
        $people = $presences->map(function ($p) {
            return [
                'id'         => $p->person_id,      // may be null
                'name'       => $p->person_name,    // snapshot
                'is_present' => $p->is_present,
                'is_deleted' => $p->person_id === null,
            ];
        });

        return response()->json([
            'status'  => true,
            'session' => [
                'id'           => $session->id,
                'title'        => $session->title,
                'session_date' => $session->session_date,
                'status'       => $session->status,
            ],
            'people' => $people,
        ], 200);
    }



    public function loadSessionNames(string $id)
    {
        $sessions = PramanSession::where('list_id', $id)
            ->select('id', 'title', 'session_date')
            ->orderBy('session_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'sessions' => $sessions
        ], 200);
    }


    public function deleteList(ListModel $list)
    {
        // 1) Ownership via policy
        $this->authorize('delete', $list);

        // 2) Historical integrity rule
        if ($list->sessions()->exists()) {
            return response()->json([
                'message' => 'This list has session history and cannot be deleted.'
            ], 422);
        }

        // 3) Soft delete (archive)
        $list->delete();

        return response()->json([
            'message' => 'List archived successfully.'
        ]);
    }




    public function logout(Request $request)
    {
        Auth::logout();                       // user logout
        $request->session()->invalidate();    // session destroy
        $request->session()->regenerateToken(); // new CSRF token

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }


    public function deletePerson(ListModel $list, Person $person)
    {
        // 🔒 Ownership check
        if ($person->list_id !== $list->id) {
            abort(404, 'Person not found in this list');
        }

        // ❌ Already deleted
        if ($person->deleted_at !== null) {
            return response()->json([
                'message' => 'Person already deleted'
            ], 409);
        }

        DB::transaction(function () use ($person) {

            // 1️⃣ Soft delete person
            $person->delete();

            // 2️⃣ Detach from historical records (IMPORTANT)
            Presence::where('person_id', $person->id)
                ->update([
                    'person_id' => null
                ]);
        });

        return response()->json([
            'message' => 'Person deleted successfully'
        ], 200);
    }
}
