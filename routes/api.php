<?php

use App\Http\Controllers\DriverAddressController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\TruckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------c
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('pokemon')->name('pokemon.')->controller(PokemonController::class)->group(function () {
    Route::get('/get_list_pokemons_first_generation', 'showNamePokemonsFirstGeneration')->name('pokemon.index');
    Route::get('/get_pokemon_by_name_or_id/{idOrName}', 'showPokemonInformationsByIdOrName')->name('pokemon.show');
});

Route::controller(DriverController::class)->prefix('driver')->name('driver')->group(function () {
    Route::post('/create', 'createDriverOnDatabase')->name('create');
    Route::put('/update/{driver_uuid}', 'updateDriverOnDatabase')->name('update');
    Route::delete('/delete/{driver_uuid}', 'deleteDriverFromDatabase')->name('update');
    Route::get('/get_all_from_database', 'getAll')->name('all');
});

Route::controller(TruckController::class)->prefix('truck')->name('truck')->group(function () {
    Route::post('/create', 'createTruckOnDatabase')->name('create');
    Route::put('/update/{truck_uuid}', 'updateTruckOnDatabase')->name('update');
    Route::delete('/delete/{truck_uuid}', 'deleteTruckFromDatabase')->name('update');
    Route::get('/get_all_from_database', 'getAll')->name('all');
});

Route::controller(DriverAddressController::class)->prefix('driver_address')->name('driver_address')->group(function () {
    Route::post('/create', 'createDriverAddressOnDatabase')->name('create');
    Route::put('/update/{driver_address_uuid}', 'updateDriverAddressOnDatabase')->name('update');
    Route::delete('/delete/{driver_address_uuid}', 'deleteDriverAddressFromDatabase')->name('update');
    Route::get('/get_all_from_database', 'getAll')->name('all');
});
