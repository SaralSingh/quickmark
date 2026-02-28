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

        $list = $request->user()->lists()->findOrFail($listId);

        $person = Person::create([
            'list_id' => $list->id,
            'name' => $validated['name']
        ]);

        return response()->json([
            'status' => true,
            'data' => $person
        ], 201);
    }


    public function bulkAddPeople(Request $request, $listId)
    {
        // 1. Validate request payload
        $validated = $request->validate([
            'names'   => ['required', 'array', 'min:1', 'max:100'],
            'names.*' => ['required', 'string', 'max:100'],
        ]);

        // 2. Ensure list belongs to authenticated user
        $list = $request->user()->lists()->findOrFail($listId);

        // 3. Transaction to avoid race condition
        DB::transaction(function () use ($list, $validated) {

            // Lock list row (important for concurrent requests)
            $list->lockForUpdate();

            $existingCount = $list->people()->count();
            $incomingCount = count($validated['names']);

            // HARD CAP: list can never exceed 100 people
            if ($existingCount + $incomingCount > 100) {
                abort(422, 'This list can contain a maximum of 100 people.');
            }

            $data = [];

            foreach ($validated['names'] as $name) {
                // EXPLICIT MAPPING to prevent Mass Assignment vulnerability during insert
                $data[] = [
                    'list_id'    => $list->id,
                    'name'       => trim($name),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Person::insert($data);
        });

        return response()->json([
            'status'  => true,
            'message' => 'People added successfully',
        ], 201);
    }


    public function getPeople(Request $request, $listId)
    {
        // Find owner's list first to prevent IDOR
        $list = $request->user()->lists()->findOrFail($listId);

        // Now safe to query people for this validated list
        $people = Person::where('list_id', $list->id)->get();

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

        $list = $request->user()->lists()->findOrFail($listId);

        $today = Carbon::today()->toDateString();
        $title = trim($request->title);

        // Check existing session
        $session = PramanSession::where('list_id', $list->id)
            ->where('session_date', $today)
            ->where('title', $title)
            ->first();

        // If not exists → create
        if (!$session) {
            $session = PramanSession::create([
                'list_id' => $list->id,
                'session_date' => $today,
                'title' => $title,
                'status' => 'active',
            ]);

            // 🔥 IMPORTANT PART — create attendance sheet
            $peopleIds = Person::where('list_id', $list->id)->pluck('id');

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

    public function getSessions(Request $request, $listId)
    {
        // Authenticate ownership first
        $list = $request->user()->lists()->findOrFail($listId);

        $sessions = PramanSession::where('list_id', $list->id)
            ->orderByDesc('session_date')
            ->orderByDesc('id')
            ->get(['id', 'title', 'session_date', 'status']);

        return response()->json([
            'data' => $sessions
        ]);
    }

    public function getPeopleSession(Request $request, PramanSession $session)
    {
        // Get all people of this session's list
        $request->user()->lists()->findOrFail($session->list_id);

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
        $request->user()->lists()->findOrFail($session->list_id);

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

    public function getSessionAttendance(Request $request, string $id)
    {
        // 1️⃣ Fetch session (authoritative)
        $session = PramanSession::select('id', 'list_id', 'title', 'session_date', 'status')
            ->findOrFail($id);

        // Ensure user actually owns the list this session belongs to
        $request->user()->lists()->findOrFail($session->list_id);

        // 2️⃣ Fetch attendance WITHOUT joins (history-safe)
        $presences = Presence::select(
            'person_id',
            'person_name',
            'is_present'
        )
            ->where('praman_session_id', $session->id)
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



    public function loadSessionNames(Request $request, string $id)
    {
        $list = $request->user()->lists()->findOrFail($id);

        $sessions = PramanSession::where('list_id', $list->id)
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
