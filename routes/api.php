<?php

use App\Http\Controllers\CampController;
use App\Http\Controllers\ParticipantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['middleware' => ['auth:api']], function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('camps', CampController::class)->except('index', 'show', 'update') ;
    Route::apiResource('participants', ParticipantController::class)->except('index', 'show', 'update');
    Route::get('/my-camps', [CampController::class, 'myCamps']);
    Route::get('/camp/{id}/participants', [ParticipantController::class, 'campParticipants']);
});

//public route
Route::get('/camps', [CampController::class, 'index']);
Route::get('/camp/{id}', [CampController::class, 'show']);



