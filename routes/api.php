<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
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
