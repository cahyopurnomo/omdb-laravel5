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

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');

// AUTH ROUTES
Route::group(['middleware' => ['auth','locale']], function () {
    Route::get('/movies', 'MovieController@index')->name('movies.index');
    Route::get('/movies/search', 'MovieController@search')->name('movies.search');
    Route::get('/movies/{id}', 'MovieController@show')->name('movies.show'); // new
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');
