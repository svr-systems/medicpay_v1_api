<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
  Route::post('login', [AuthController::class, 'login']);

  Route::group(['middleware' => 'auth:api'], function () {
    Route::get('logout', [AuthController::class, 'logout']);
  });
});

Route::group(['middleware' => 'auth:api'], function () {
  //SYSTEM
  Route::group(["middleware" => "UsrSystem"], function () {
    Route::group(['prefix' => 'system'], function () {
      //ROUTES
      Route::apiResource('users', UserController::class);
      Route::group(['prefix' => 'users'], function () {
        Route::post('restore', [UserController::class, 'restore']);
        Route::post('password', [UserController::class, 'setPassword']);
      });
      //CATALOGS
      Route::get('roles', [RoleController::class, 'index']);
    });
  });
});

// Route::group(['prefix' => 'public'], function () {
//   //
// });
