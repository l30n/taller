<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/car/brands', 'CarsController@listBrands');
Route::get('/servicesByCar', 'ServicesController@getByCar');

Route::get('/clients', 'ClientsController@get');
Route::post('/clients', 'ClientsController@save');

Route::get('/sales', 'SalesController@get');
Route::post('/sales', 'SalesController@save');
Route::post('/sales/status', 'SalesController@changeStatus');

Route::get('/items', 'ItemsController@get');
Route::post('/items', 'ItemsController@save');

Route::get('/cars', 'CarsController@get');
Route::post('/cars', 'CarsController@save');

Route::get('/services', 'ServicesController@get');
Route::post('/services', 'ServicesController@save');

Route::get('/carservices', 'CarServicesController@get');
Route::post('/carservices', 'CarServicesController@save');

Route::get('/users', 'UsersController@get');
Route::post('/users', 'UsersController@save');

Route::get('/roles', 'RolesController@get');
Route::post('/roles', 'RolesController@save');

Route::get('/dashboard', 'DashboardController@get');
