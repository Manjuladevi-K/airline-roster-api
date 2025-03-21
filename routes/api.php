<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// roster-parser-routes

Route::post('/upload-roster', [EventController::class, 'uploadRoster']);
Route::get('/events-between', [EventController::class, 'getEventsBetween']);
Route::get('/flights-next-week', [EventController::class, 'getFlightsNextWeek']);
Route::get('/standby-next-week', [EventController::class, 'getStandbyNextWeek']);
Route::get('/flights-from', [EventController::class, 'getFlightsFromLocation']);
