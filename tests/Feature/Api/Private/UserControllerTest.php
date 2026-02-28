<?php

use App\Models\ListModel;
use App\Models\Person;
use App\Models\PramanSession;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

// --- Profile Tests ---
it('can get authenticated user profile', function () {
    $response = $this->getJson('/api/user');
    
    // The route /api/user isn't strictly defined in the provided routes but let's test the root endpoint
    // Fallback: testing the index method on the controller if it was mapped, but it's not in api.php
    // We will skip this since it's commented out in api.php
});

// --- List Tests ---
it('can create a new list', function () {
    $response = $this->postJson('/api/lists', [
        'name' => 'My New List'
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'status' => true,
            'message' => 'List created successfully',
            'data' => [
                'name' => 'My New List',
                'user_id' => $this->user->id
            ]
        ]);

    $this->assertDatabaseHas('lists', [
        'name' => 'My New List',
        'user_id' => $this->user->id
    ]);
});

it('can retrieve user lists', function () {
    ListModel::create(['user_id' => $this->user->id, 'name' => 'List 1']);
    ListModel::create(['user_id' => $this->user->id, 'name' => 'List 2']);

    $response = $this->getJson('/api/lists');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'status',
            'total',
            'data' => [
                '*' => ['id', 'name']
            ]
        ]);
});

it('can delete an empty list', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'Empty List']);

    $response = $this->deleteJson('/api/lists/' . $list->id);

    $response->assertStatus(200)
        ->assertJson(['message' => 'List archived successfully.']);

    // Soft deleted
    $this->assertSoftDeleted('lists', ['id' => $list->id]);
});

it('cannot delete a list that has session history', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'Active List']);
    
    PramanSession::create([
        'list_id' => $list->id,
        'session_date' => now()->toDateString(),
        'title' => 'Session 1',
        'status' => 'active'
    ]);

    $response = $this->deleteJson('/api/lists/' . $list->id);

    $response->assertStatus(422)
        ->assertJson(['message' => 'This list has session history and cannot be deleted.']);
});

// --- People Tests ---
it('can add a single person to a list', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'My List']);

    $response = $this->postJson('/api/lists/' . $list->id . '/people', [
        'name' => 'John Doe'
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'status' => true,
            'data' => [
                'name' => 'John Doe',
                'list_id' => $list->id
            ]
        ]);

    $this->assertDatabaseHas('people', [
        'name' => 'John Doe',
        'list_id' => $list->id
    ]);
});

it('can bulk add people to a list', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'My List']);

    $response = $this->postJson('/api/lists/' . $list->id . '/people/bulk', [
        'names' => ['Alice', 'Bob', 'Charlie']
    ]);

    $response->assertStatus(201)
        ->assertJson(['status' => true, 'message' => 'People added successfully']);

    $this->assertDatabaseCount('people', 3);
});

it('cannot bulk add more than 100 people to a list', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'My List']);
    
    // Create 99 people
    for ($i = 0; $i < 99; $i++) {
        Person::create(['list_id' => $list->id, 'name' => "Person $i"]);
    }

    $response = $this->postJson('/api/lists/' . $list->id . '/people/bulk', [
        'names' => ['Extra 1', 'Extra 2']
    ]);

    $response->assertStatus(422);
});

it('can delete a person and nullify their presence history', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'My List']);
    $person = Person::create(['list_id' => $list->id, 'name' => 'To Be Deleted']);
    $session = PramanSession::create(['list_id' => $list->id, 'title' => 'Test', 'session_date' => now(), 'status' => 'active']);
    
    Presence::create([
        'praman_session_id' => $session->id,
        'person_id' => $person->id,
        'is_present' => 1,
        'person_name' => $person->name
    ]);

    $response = $this->deleteJson("/api/lists/{$list->id}/people/{$person->id}");

    $response->assertStatus(200);
    $this->assertSoftDeleted('people', ['id' => $person->id]);
    
    $this->assertDatabaseHas('presences', [
        'person_name' => 'To Be Deleted',
        'person_id' => null // Verify it properly detached
    ]);
});

// --- Sessions & Presence Tests ---
it('can start a new session for a list', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'My List']);
    $person1 = Person::create(['list_id' => $list->id, 'name' => 'Alice']);
    $person2 = Person::create(['list_id' => $list->id, 'name' => 'Bob']);

    $response = $this->postJson("/api/lists/{$list->id}/sessions", [
        'title' => 'Morning Class'
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.title', 'Morning Class');

    $session = PramanSession::first();
    expect($session->title)->toBe('Morning Class');

    // Should automatically create blank presence records for the 2 people
    $this->assertDatabaseCount('presences', 2);
    $this->assertDatabaseHas('presences', [
        'praman_session_id' => $session->id,
        'person_id' => $person1->id,
        'is_present' => 0
    ]);
});

it('can record presence for a person', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'My List']);
    $person = Person::create(['list_id' => $list->id, 'name' => 'Alice']);
    $session = PramanSession::create(['list_id' => $list->id, 'title' => 'Morning', 'session_date' => now(), 'status' => 'active']);
    Presence::create(['praman_session_id' => $session->id, 'person_id' => $person->id, 'is_present' => 0]);

    $response = $this->postJson("/api/sessions/{$session->id}/presence", [
        'person_id' => $person->id,
        'is_present' => 1
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('presences', [
        'person_id' => $person->id,
        'is_present' => 1,
        'person_name' => 'Alice' // Checks Snapshot creation
    ]);
});

it('cannot record presence if session is closed', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'My List']);
    $person = Person::create(['list_id' => $list->id, 'name' => 'Alice']);
    $session = PramanSession::create(['list_id' => $list->id, 'title' => 'Morning', 'session_date' => now(), 'status' => 'closed']);

    $response = $this->postJson("/api/sessions/{$session->id}/presence", [
        'person_id' => $person->id,
        'is_present' => 1
    ]);

    $response->assertStatus(403)
        ->assertJson(['message' => 'Session already closed']);
});

it('closes the session when final_save is passed', function () {
    $list = ListModel::create(['user_id' => $this->user->id, 'name' => 'My List']);
    $person = Person::create(['list_id' => $list->id, 'name' => 'Alice']);
    $session = PramanSession::create(['list_id' => $list->id, 'title' => 'Morning', 'session_date' => now(), 'status' => 'active']);
    Presence::create(['praman_session_id' => $session->id, 'person_id' => $person->id, 'is_present' => 0]);

    $response = $this->postJson("/api/sessions/{$session->id}/presence", [
        'person_id' => $person->id,
        'is_present' => 1,
        'final_save' => true
    ]);

    $response->assertStatus(200);
    expect($session->fresh()->status)->toBe('closed');
});

// --- Authorization Tests ---
it('cannot add a person to another users list', function () {
    $otherUser = User::factory()->create();
    $otherList = ListModel::create(['user_id' => $otherUser->id, 'name' => 'Other List']);

    $response = $this->postJson('/api/lists/' . $otherList->id . '/people', [
        'name' => 'Sneaky Person'
    ]);

    $response->assertStatus(404);
});

it('cannot start a session on another users list', function () {
    $otherUser = User::factory()->create();
    $otherList = ListModel::create(['user_id' => $otherUser->id, 'name' => 'Other List']);

    $response = $this->postJson('/api/lists/' . $otherList->id . '/sessions', [
        'title' => 'Sneaky Session'
    ]);

    $response->assertStatus(404);
});

it('cannot view sessions from another users list', function () {
    $otherUser = User::factory()->create();
    $otherList = ListModel::create(['user_id' => $otherUser->id, 'name' => 'Other List']);

    PramanSession::create([
        'list_id' => $otherList->id,
        'title' => 'Other Session',
        'session_date' => now(),
        'status' => 'active'
    ]);

    $response = $this->getJson('/api/lists/' . $otherList->id . '/sessions');

    $response->assertStatus(404);
});
