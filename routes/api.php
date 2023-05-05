<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiotAPIController;
use App\Http\Controllers\APIController;

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

//AUTH
Route::post('/login', [RiotAPIController::class, 'handleLogin']);
Route::post('/2fa', [RiotAPIController::class, 'handle2fa']);
Route::post('/reauth', [RiotAPIController::class, 'handleRecookie']);

//GET
Route::get('/version', [APIController::class, 'getVersion']);
Route::get('/profile/{puuid}/{region}', [RiotAPIController::class, 'getUserInfo']);
Route::get('/store/{puuid}/{region}', [RiotAPIController::class, 'getStorefront']);
Route::get('/wallet/{puuid}/{region}', [RiotAPIController::class, 'getWallet']);
Route::get('/penalties/{region}', [RiotAPIController::class, 'getPenalties']);
Route::get('/mmr/{puuid}/{region}', [RiotAPIController::class, 'getMMR']);
Route::get('/match-history/{puuid}/{region}', [RiotAPIController::class, 'getMatchHistory']);
Route::get('/match/{matchId}/{region}', [RiotAPIController::class, 'getMatchDetails']);
Route::get('/pregame/{puuid}/{region}', [RiotAPIController::class, 'getPregame']);
Route::get('/pregame/{matchId}/{region}', [RiotAPIController::class, 'getPregameMatch']);

//POST
Route::post('/pregame/{matchId}/select/{agentId}/{region}', [RiotAPIController::class, 'selectPregameAgent']);
Route::post('/pregame/{matchId}/lock/{agentId}/{region}', [RiotAPIController::class, 'lockPregameAgent']);

//PROXY

Route::get('/redirect/{path?}', 'Controller@redirectApi')->where('path', '.*');
