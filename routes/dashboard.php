<?php
/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
*/


Route::group(['prefix' => 'login', 'as' => 'login', 'namespace' => 'Auth'], function () {
    Route::get('/', 'LoginController@showLoginForm');
    Route::post('/', 'LoginController@login');
});

Route::middleware('dashboard')->group(function () {
    Route::post('logout', 'Auth\LoginController@logout')->name('auth.logout');

    Route::get('/', 'DashboardController@index')->name('index');
    Route::resource('pages', 'PagesController');
    Route::resource('members', 'MembersController');

    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
        Route::get('/', 'PostsController@index')->name('index');
        Route::delete('{post}', 'PostsController@delete')->name('delete');
    });

    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/', 'ReportsController@index')->name('index');
        Route::get('{report}/mark-safe', 'ReportsController@markSafe')->name('mark-safe');
        Route::get('{report}/delete-post', 'ReportsController@deletePost')->name('delete-post');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', 'SettingsController@index')->name('index');
        Route::post('/', 'SettingsController@update')->name('update');
    });
});