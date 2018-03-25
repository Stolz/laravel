<?php

/// Neutral area ===============================================================

Route::view('/', 'home')->name('home');

/// Only guests area ===========================================================

Route::middleware(['guest'])->group(function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login')->name('login.attempt');
});

/// Only authenticated users area ==============================================

Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::prefix('me')->group(function () {
        Route::get('/', 'MeController@showInfo')->name('me');
        Route::get('password', 'MeController@showChangePasswordForm')->name('me.password');
        Route::post('password', 'MeController@changePassword')->name('me.password.change');
    });
});
