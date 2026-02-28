<?php

use App\Http\Controllers\Api\Private\UserController as PrivateUserController;
use App\Http\Controllers\AuthController\UserController;
use App\Http\Controllers\SentimentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/sentiment/analyze', [SentimentController::class, 'analyze']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Route::get('/user', fn (Request $r) => $r->user());

    Route::post('lists', [PrivateUserController::class, 'createList']);
    Route::get('/lists', [PrivateUserController::class, 'getLists']);

    Route::post('/lists/{list}/people', [PrivateUserController::class, 'addPerson']);
    Route::post('/lists/{list}/people/bulk', [PrivateUserController::class, 'bulkAddPeople']);
    Route::get('/lists/{list}/people', [PrivateUserController::class, 'getPeople']);
    Route::get('/sessions/{session}/people', [PrivateUserController::class, 'getPeopleSession']);
    Route::post('/lists/{list}/sessions', [PrivateUserController::class, 'startSession']);
    Route::post('/sessions/{session}/presence', [PrivateUserController::class, 'storePresence']);
    Route::get('/lists/{list}/sessions', [PrivateUserController::class, 'getSessions']);
    Route::get('/sessions/{id}/attendance', [PrivateUserController::class, 'getSessionAttendance']);
    Route::get('/lists/{list}/session-titles', [PrivateUserController::class, 'loadSessionNames']);
    Route::delete('/lists/{list}', [PrivateUserController::class, 'deleteList']);
    Route::delete('/lists/{list}/people/{person}', [PrivateUserController::class, 'deletePerson']);


});
