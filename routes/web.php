<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/oauth.php', 'LoginController@oauth');
Route::get('/email', 'EmailController@showUserInfo');
Route::post('/email', 'EmailController@sendEmail');

Route::get("/evenement", function() { return Redirect::to("evenement.php"); });
Route::get("/home", function() { return Redirect::to("home.php"); });
Route::get("/nouveau", function() { return Redirect::to("nouveau.php"); });
Route::get("/profil", function() { return Redirect::to("profil.php"); });

