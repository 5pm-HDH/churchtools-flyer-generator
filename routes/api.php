<?php

use App\Http\Controllers\API\LoadCalendarController;
use App\Http\Middleware\ConfigureChurchToolsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware([ConfigureChurchToolsClient::class])->group(function () {
    Route::get('/calendars', [LoadCalendarController::class, 'loadCalendars']);
    Route::get('/calendars/{calendarId}/appointments', [LoadCalendarController::class, 'loadAppointments']);
});
