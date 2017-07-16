<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'Api\Auth\LoginController@index');
Route::post('/register', 'Api\Auth\RegisterController@register');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::resource('/teams/{id}/projects', 'Api\TeamProjectController');
    Route::resource('/projects/{id}/objectives', 'Api\ObjectiveController');
    Route::resource('/projects/{id}/streams', 'Api\StreamController');
    Route::resource('/projects', 'Api\UserProjectController');
    Route::resource('/teams', 'Api\TeamController');
});
