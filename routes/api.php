<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth::routes();
Route::post('/login', 'Api\Auth\AuthController@authenticate');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::resource('/projects', 'Api\ProjectController');
});
