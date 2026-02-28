<?php

use App\Http\Controllers\Api\Private\UserController as PrivateUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController\UserController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/api-docs', function () {
    return view('api-docs');
})->name('api-docs');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');

Route::middleware('guest')->group(function () {
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', [UserController::class, 'register']);
    
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [UserController::class, 'login']);
});




Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('auth.dashboard'))->name('dashboard');

    Route::get('/list-workspace', fn() => view('auth.list-workspace'));

    Route::get('/session/{session}', function ($sessionId) {
        return view('auth.session', ['sessionId' => $sessionId]);
    });

    Route::get('/session-view/{id}', function ($id) {
        return view('auth.session-view', ['sessionId' => $id]);
    });

    Route::post('/logout', [PrivateUserController::class, 'logout']);

});
