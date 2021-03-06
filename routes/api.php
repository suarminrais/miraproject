<?php

use Illuminate\Http\Request;

/*php
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources(['user' => 'API\UserController']);
Route::apiResources(['dokter' => 'API\DokterController']);
Route::apiResources(['rkategori' => 'API\rkategori']);
Route::apiResources(['rkelas' => 'API\rkelas']);
Route::apiResources(['rtarif' => 'API\rtarif']);
Route::apiResources(['paramedis' => 'API\ParamedisController']);
Route::apiResources(['suplier' => 'API\SuplierController']);
Route::apiResources(['barang' => 'API\BarangController']);
Route::apiResources(['poli' => 'API\PoliController']);
Route::get('profile', 'API\UserController@profile');
Route::get('findUser', 'API\UserController@search');
Route::put('profile', 'API\UserController@updateProfile');

