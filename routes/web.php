<?php

/// Neutral area ===============================================================

Route::view('/', 'home')->name('home');

/// Only guests area ===========================================================

Route::middleware(['guest'])->group(function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login')->name('do.login');
});

/// Only authenticated users area ==============================================

Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    Route::view('me', 'me')->name('me');
});
