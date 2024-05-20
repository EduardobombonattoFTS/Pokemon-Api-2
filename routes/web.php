<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('pokemon')->name('pokemon.')->controller(PokemonController::class)->group(function () {
    Route::get('/get_name_of_pokemons_first_generation', 'showNamePokemonsFirstGeneration')->name('pokemon.index');
    Route::get('/get_pokemon_by_name_or_id/{idOrName}', 'showPokemonInformationsByIdOrName')->name('pokemon.show');
});
