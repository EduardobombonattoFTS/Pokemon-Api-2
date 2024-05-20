<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PokemonController extends Controller {
    /**
     * Obtain the array of the name of the first generation pokemons
     * @return array
     */

    public function showNamePokemonsFirstGeneration() {
        $pokemons = $this->pokeApiService->getPokemonFirstGenerationOnPokeApi(0, 151);
        $response = [];
        foreach ($pokemons['response']->results as $result) {
            $newvalue = [
                'name' => $result->name,
            ];
            array_push($response, $newvalue);
        }
        return $response;
    }

    /**
     * Obtian the array of the details of a pokemon
     */
    public function showPokemonInformationsByIdOrName($idOrName) {
        $pokemon = $this->pokeApiService->getPokemonOnPokeApi($idOrName);
        return $pokemon;
    }
}
