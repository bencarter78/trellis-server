<?php

Route::post('/login', 'Api\Auth\LoginController@index');
Route::post('/register', 'Api\Auth\RegisterController@register');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::resource('/teams/{uid}/members', 'Api\TeamMemberController');
    Route::resource('/teams/{uid}/projects', 'Api\TeamProjectController');
    Route::post('/teams/{uid}/streams/search', 'Api\TeamStreamSearchController@index');
    Route::resource('/teams/{uid}/streams', 'Api\TeamStreamController');
    Route::post('/projects/{uid}/invite', 'Api\ProjectInviteController@store');
    Route::resource('/projects/{uid}/members', 'Api\ProjectMemberController');
    Route::resource('/projects/{uid}/milestones', 'Api\MilestoneController');
    Route::resource('/projects/{uid}/objectives', 'Api\ObjectiveController');
    Route::resource('/projects/{uid}/streams', 'Api\ProjectStreamController');
    Route::resource('/projects', 'Api\UserProjectController');
    Route::resource('/teams', 'Api\TeamController');
});
