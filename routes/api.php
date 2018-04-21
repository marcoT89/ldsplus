<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::namespace('Api')->name('api.')->group(function () {
    Route::get('wards', 'WardsController@index')->name('wards.index');
});

// Protected routes
Route::middleware('auth:api')->namespace('Api')->name('api.')->group(function () {
    Route::resource('organizations', 'OrganizationsController', ['except' => ['edit']]);
    Route::get('callings/changes', 'CallingsController@changes')->name('callings.changes');
    Route::resource('users', 'UsersController', ['only' => ['index']]);
    Route::get('users/check-status', 'UsersController@checkStatus')->name('users.check-status');
});
