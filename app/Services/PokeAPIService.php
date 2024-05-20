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
    public function getPokemonFirstGenerationOnPokeApi($offset, $limit) {
        $response = Http::get("{$this->baseUrl}/pokemon", [
            'offset' => $offset,
            'limit' => $limit,
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
    public function getPokemonOnPokeApi($idOrName) {
        $response = Http::get("{$this->baseUrl}/pokemon/{$idOrName}");

        if ($response->successful()) {
            return [
                'response' => response()->json(),
                'data' => $response->json(),
            ];
        } else {
            return [
                'response' => response()->json([
                    'message' => 'Houve um erro, tente novamente',
                ], $response->status()),
            ];
        }
    }

    /**
     * Forms the basis for at least one Pokémon.
     * @return stdClass
     */
    public function getPokemonSpecieOnPokeApi($idOrName) {
        $response = Http::get("{$this->baseUrl}/pokemon-species/{$idOrName}");

        if ($response->successful()) {
            return [
                'response' => response()->json(),
                'data' => $response->json(),
            ];
        } else {
            return [
                'response' => response()->json([
                    'message' => 'Houve um erro, tente novamente',
                ], $response->status()),
            ];
        }
    }
}
