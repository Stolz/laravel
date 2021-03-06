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
        Route::get('notifications', 'NotificationController@index')->name('me.notifications');
        Route::get('notifications/stream', 'NotificationController@stream')->name('me.notifications.stream');
        Route::post('notifications', 'NotificationController@markAsRead')->name('me.notification.read');
    });

    // Access module
    Route::middleware(['can:access,module'])->prefix('access')->namespace('Access')->name('access.')->group(function () {
        Route::resource('role', 'RoleController');
        Route::resource('user', 'UserController');
    });

    // Master module
    Route::middleware(['can:master,module'])->prefix('master')->namespace('Master')->name('master.')->group(function () {
        Route::resource('announcement', 'AnnouncementController');
        Route::resource('country', 'CountryController');
    });
});
