<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'SalesController@index');

    Route::prefix('cars')->group(function () {
        Route::get('/', 'SalesController@index')->name('cars.list');
        Route::get('/create', 'SalesController@create')->name('cars.create');
        Route::get('/receipt', 'SalesController@receipt')->name('cars.receipt');
    });

    Route::prefix('sales')->group(function () {
        Route::get('/', 'SalesController@index')->name('sales.list');
        Route::get('/create', 'SalesController@create')->name('sales.create');
        Route::get('/receipt', 'SalesController@receipt')->name('sales.receipt');
    });
});
