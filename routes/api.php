<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\productos\ProductosController;
use App\Http\Controllers\pokemons\PokemonsController;

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
    Route::get('productos/{id}', [ProductosController::class, 'show']);
    Route::post('productos', [ProductosController::class, 'store']);
    Route::post('productos/{id}', [ProductosController::class, 'update']);
    Route::delete('productos/delete/{id}', [ProductosController::class, 'delete']);

    // Pokemons
    Route::get('pokemons/save', [PokemonsController::class, 'savePokemons']);
    Route::get('pokemons', [PokemonsController::class, 'index']);
    Route::post('pokemons', [PokemonsController::class, 'store']);
});

Route::group(['prefix' => 'auth'], function(){

    // Login de usuario
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth:api')->group(function() {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});