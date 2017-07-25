<?php

use Illuminate\Http\Request;

Route::post('/login', 'Api\Auth\LoginController@index');
Route::post('/register', 'Api\Auth\RegisterController@register');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::resource('/teams/{id}/projects', 'Api\TeamProjectController');
    Route::post('/teams/{id}/streams/search', 'Api\TeamStreamSearchController@index');
    Route::resource('/teams/{id}/streams', 'Api\TeamStreamController');
    Route::resource('/projects/{id}/members', 'Api\ProjectMemberController');
    Route::resource('/projects/{id}/milestones', 'Api\MilestoneController');
    Route::resource('/projects/{id}/objectives', 'Api\ObjectiveController');
    Route::resource('/projects/{id}/streams', 'Api\ProjectStreamController');
    Route::resource('/projects', 'Api\UserProjectController');
    Route::resource('/teams', 'Api\TeamController');
});
