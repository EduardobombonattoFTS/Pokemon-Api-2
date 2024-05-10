<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PokeAPIService {
    /**
     * URL that send the request
     */
    protected $baseUrl;

    public function __construct() {
        $this->baseUrl = config('services.poke.base_uri');
    }

    /**
     * Obtain the list of pokemons first generation
     * @return json
     */
    public function getPokemonFirstGeneration() {
        $response = Http::get("{$this->baseUrl}/pokemon", [
            'offset' => 0,
            'limit' => 151,
        ]);
        if ($response->successful()) {
            return ['response' => json_decode($response)];
        } else {
            return response()->json([
                'message' => 'Houve um erro, tente novamente',
            ], $response->status());
        }
    }

    /**
     * Obtain the details of a pokemon
     * @return stdClass
     */
    public function getPokemon($idOrName) {
        $response = Http::get("{$this->baseUrl}/pokemon/{$idOrName}");

        if ($response->successful()) {
            return ['response' => json_decode($response)];
        } else {
            return response()->json([
                'message' => 'Houve um erro, tente novamente',
            ], $response->status());
        }
    }

    /**
     * Forms the basis for at least one Pokémon.
     * @return stdClass
     */
    public function getSpecie($idOrName) {
        $response = Http::get("{$this->baseUrl}/pokemon-species/{$idOrName}");

        if ($response->successful()) {
            return ['response' => json_decode($response)];
        } else {
            return response()->json([
                'message' => 'Houve um erro, tente novamente',
            ], $response->status());
        }
    }

    /**
     * Forms the basis for at least one Pokémon.
     * @return stdClass
     */
    public function getEvolutionChain($id) {
        $response = Http::get("{$this->baseUrl}/evolution-chain/{$id}");

        if ($response->successful()) {
            return ['response' => json_decode($response)];
        } else {
            return response()->json([
                'message' => 'Houve um erro, tente novamente',
            ], $response->status());
        }
    }
}
