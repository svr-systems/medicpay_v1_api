<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FiscalRegimeController;
use App\Http\Controllers\FiscalTypeController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\TownController;
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
      Route::apiResource('doctors', DoctorController::class);
      Route::group(['prefix' => 'doctors'], function () {
        Route::post('restore', [DoctorController::class, 'restore']);
        Route::post('password', [UserController::class, 'setPassword']);
      });
      Route::apiResource('specialties', SpecialtyController::class);
      Route::group(['prefix' => 'specialties'], function () {
        Route::post('restore', [SpecialtyController::class, 'restore']);
      });
      Route::apiResource('hospitals', HospitalController::class);
      Route::group(['prefix' => 'hospitals'], function () {
        Route::post('restore', [HospitalController::class, 'restore']);
      });
      Route::apiResource('users', UserController::class);
      Route::group(['prefix' => 'users'], function () {
        Route::post('restore', [UserController::class, 'restore']);
        Route::post('password', [UserController::class, 'setPassword']);
      });
      //CATALOGS
      Route::get('fiscal_regimes', [FiscalRegimeController::class, 'index']);
      Route::get('fiscal_types', [FiscalTypeController::class, 'index']);
      Route::get('towns', [TownController::class, 'index']);
      Route::get('states', [StateController::class, 'index']);
      Route::get('roles', [RoleController::class, 'index']);
    });
  });
});

Route::group(['prefix' => 'public'], function () {
  Route::group(['prefix' => 'doctor'], function () {
    Route::post('', [DoctorController::class, 'storeByDoctor']);
  });
  Route::group(['prefix' => 'hospitals'], function () {
    Route::get('', [HospitalController::class, 'getItemsPublic']);
  });
  Route::group(['prefix' => 'specialties'], function () {
    Route::get('', [SpecialtyController::class, 'getItemsPublic']);
  });
});
