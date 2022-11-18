<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ($this->app['config']->get('laratrust.panel.restrict_non_administrators')?'role:administrator':'auth')], function(){
    Route::resource('/permissions', 'PermissionsController', ['as' => 'laratrust'])
        ->only(['index', 'edit', 'update', 'create', 'store', 'destroy']);

    Route::resource('/teams', 'TeamsController', ['as' => 'laratrust'])
        ->only(['index', 'edit', 'update', 'create', 'store']);

    Route::resource('/roles', 'RolesController', ['as' => 'laratrust']);

    Route::resource('/roles-assignment', 'RolesAssignmentController', ['as' => 'laratrust'])
        ->only(['index', 'edit', 'update']);
});

