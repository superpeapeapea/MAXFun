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
    return view('max-home');
});

//Route::controller('singer','MXSingerController');
Route::any('/artist/search', 'MXArtistController@search');
Route::any('/artist/similar/{artistId}', 'MXArtistController@similar');