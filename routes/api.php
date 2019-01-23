<?php

/// Only guests area ===========================================================

Route::middleware(['guest'])->group(function () {
    Route::middleware(['throttle:5,5'])->post('login', 'AuthController@login')->name('login');
});

/// Only authenticated users area ==============================================

Route::middleware(['auth:api'])->group(function () {
    Route::middleware(['throttle:5,5'])->get('refresh', 'AuthController@refresh')->name('refresh');
    Route::get('me', 'AuthController@me')->name('me');
    Route::get('logout', 'AuthController@logout')->name('logout');

    Route::apiResource('country', 'CountryController');
    Route::apiResource('permission', 'PermissionController')->only(['index']);
    Route::apiResource('user', 'UserController');
});
