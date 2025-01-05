<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\ExampleTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//LOG
Route::group(['prefix' => 'auth'], function () {
  Route::post('login', [AuthController::class, 'login']);

  Route::group(['middleware' => 'auth:api'], function () {
    Route::get('logout', [AuthController::class, 'logout']);
  });
});

//WITH AUTH
Route::group(['middleware' => 'auth:api'], function () {
  Route::apiResource('users', UserController::class);
  Route::group(['prefix' => 'users'], function () {
    Route::post('password', [UserController::class, 'setPassword']);
  });
  Route::apiResource('examples', ExampleController::class);

  //CATALOGS
  Route::get('roles', [RoleController::class, 'index']);
  Route::get('example_types', [ExampleTypeController::class, 'index']);
});