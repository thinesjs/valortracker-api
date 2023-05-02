<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiotAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return "ValorAuth - API for (v2.valortracker.xyz)";
});
Route::post('/login', [RiotAPIController::class, 'handleLogin']);
Route::post('/2fa', [RiotAPIController::class, 'handle2fa']);
Route::post('/reauth', [RiotAPIController::class, 'handleRecookie']);
Route::get('/profile', [RiotAPIController::class, 'getUserInfo']);
Route::get('/store/{puuid}/{region}', [RiotAPIController::class, 'getStorefront']);
Route::get('/wallet/{puuid}/{region}', [RiotAPIController::class, 'getWallet']);
Route::get('/penalties/{region}', [RiotAPIController::class, 'getPenalties']);
