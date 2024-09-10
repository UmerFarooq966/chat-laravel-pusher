<?php

use Illuminate\Support\Facades\Route;


Route::get('/', 'App\Http\Controllers\PusherController@index');
Route::post('/broadcast', 'App\Http\Controllers\PusherController@broadcasts');
Route::post('/receive', 'App\Http\Controllers\PusherController@receivee');