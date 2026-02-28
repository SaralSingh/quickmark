<?php

use App\Http\Controllers\Api\Private\UserController as PrivateUserController;
use App\Http\Controllers\AuthController\UserController;
use App\Http\Controllers\SentimentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/sentiment/analyze', [SentimentController::class, 'analyze']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Route::get('/user', fn (Request $r) => $r->user());

    // 🟢 Read Operations - High limit (120 req / 1 min)
    Route::middleware('throttle:120,1')->group(function() {
        Route::get('/lists', [PrivateUserController::class, 'getLists']);
        Route::get('/lists/{list}/people', [PrivateUserController::class, 'getPeople']);
        Route::get('/sessions/{session}/people', [PrivateUserController::class, 'getPeopleSession']);
        Route::get('/lists/{list}/sessions', [PrivateUserController::class, 'getSessions']);
        Route::get('/sessions/{id}/attendance', [PrivateUserController::class, 'getSessionAttendance']);
        Route::get('/lists/{list}/session-titles', [PrivateUserController::class, 'loadSessionNames']);
    });

    // 🟡 Write Operations - Standard limit (60 req / 1 min)
    Route::middleware('throttle:60,1')->group(function() {
        Route::post('lists', [PrivateUserController::class, 'createList']);
        Route::post('/lists/{list}/people', [PrivateUserController::class, 'addPerson']);
        Route::post('/lists/{list}/people/bulk', [PrivateUserController::class, 'bulkAddPeople']);
        Route::post('/lists/{list}/sessions', [PrivateUserController::class, 'startSession']);
        Route::post('/sessions/{session}/presence', [PrivateUserController::class, 'storePresence']);
    });

    // 🔴 Destructive Operations - Strict limit (30 req / 1 min)
    Route::middleware('throttle:30,1')->group(function() {
        Route::delete('/lists/{list}', [PrivateUserController::class, 'deleteList']);
        Route::delete('/lists/{list}/people/{person}', [PrivateUserController::class, 'deletePerson']);
    });
});
