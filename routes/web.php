<?php


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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::post('register', 'Api\RegisterController@store')->name('ajax.register')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::view('/chamados', 'callings')->name('callings');
    Route::view('/organizations', 'organizations')->name('organizations');
    Route::view('/discursantes', 'speakers')->name('speakers');
});

Route::view('/discursantes', 'pages.teste');

/*Route::get('/discursantes', function () {
    return  view('pages.teste');
});*/
