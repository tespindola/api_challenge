<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\productos\ProductosController;

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

Route::middleware('auth:api')->group(function(){
    // Productos
    Route::get('productos', [ProductosController::class, 'index']);
    Route::post('productos', [ProductosController::class, 'store']);
    Route::post('productos/{id}', [ProductosController::class, 'update']);
    Route::delete('productos/delete/{id}', [ProductosController::class, 'delete']);
});

Route::group(['prefix' => 'auth'], function(){

    // Login de usuario
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth:api')->group(function() {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});