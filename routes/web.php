<?php

/// Neutral area ===============================================================

Route::view('/', 'home')->name('home');

/// Only guests area ===========================================================

Route::middleware(['guest'])->group(function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login')->name('login.attempt');

    // Password reset
    Route::middleware(['throttle:30,2'])->prefix('password')->namespace('Auth')->group(function () {
        Route::get('reset', 'ResetPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('email', 'ResetPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('reset', 'ResetPasswordController@reset');
    });
});

/// Only authenticated users area ==============================================

Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    // Current user dashboard
    Route::prefix('me')->group(function () {
        Route::get('/', 'MeController@showInfo')->name('me');
        Route::get('password', 'MeController@showChangePasswordForm')->name('me.password');
        Route::post('password', 'MeController@changePassword')->name('me.password.change');
        Route::get('notifications', 'MeController@showNotifications')->name('me.notifications');
        Route::post('notifications', 'MeController@markNotificationAsRead')->name('me.notification.read');
    });

    // Access module
    Route::middleware(['can:access,module'])->prefix('module/access')->namespace('Access')->name('access.')->group(function () {
        Route::view('/', 'module/access')->name('home');
        Route::resource('role', 'RoleController');
        Route::resource('user', 'UserController');
    });

    // Master module
    Route::middleware(['can:master,module'])->prefix('module/master')->namespace('Master')->name('master.')->group(function () {
        Route::view('/', 'module/master')->name('home');
        Route::resource('country', 'CountryController');
    });
});
