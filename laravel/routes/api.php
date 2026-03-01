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

// Public routes
Route::post('/login', 'API\AuthController@login');

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::post('/logout', 'API\AuthController@logout');
    Route::get('/user', 'API\AuthController@user');
    
    // Movie routes
    Route::get('/movies/search', 'API\MovieController@search');
    Route::get('/movies/{imdbId}', 'API\MovieController@show');
});

