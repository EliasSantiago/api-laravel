<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
  Route::group([
    'prefix' => 'auth'
  ], function () {
    Route::post('/register', 'App\Http\Controllers\Api\AuthController@register');
    Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
    Route::post('/logout', 'App\Http\Controllers\Api\AuthController@logout');
  });

  Route::group([
    'middleware' => ['auth:api']
  ], function () {
    Route::resource('/books', 'App\Http\Controllers\Api\BooksController');
  });
});
