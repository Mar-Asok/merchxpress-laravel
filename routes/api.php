<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes for registration and login
// Removed ->middleware('guest') as it causes redirects for SPAs
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// Removed ->middleware('guest') as it causes redirects for SPAs
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user(); // Returns the authenticated user's data
    });
    // Add other protected API routes here
});