<?php

use App\Http\Controllers\CampController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/create-camp', [CampController::class, 'create']);
    Route::get('/home', [CampController::class, 'showUserCamps'])->name('home');
});

Route::get('/', [CampController::class, 'showAllCamps']);
Route::get('/camp/{id}', [CampController::class, 'showCamp']);
