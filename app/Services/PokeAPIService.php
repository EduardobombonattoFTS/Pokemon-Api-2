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
            $pokemon = $response->json();
            $moves = [];
            $stats = [];
            $evolution = $this->getPokemonEvolutionChainOnPokeApi($idOrName);
            $evolution_chain = $this->getEvolutionChainFormated($evolution);
            foreach ($pokemon['moves'] as $pokemon_move) {
                array_push($moves, $pokemon_move['move']['name']);
            }
            foreach ($pokemon['stats'] as $pokemon_stat) {
                array_push($stats, [
                    'name' => $pokemon_stat['stat']['name'],
                    'base_stat' => $pokemon_stat['base_stat'],
                ]);
            }

            $formattedResponse = [
                'name' => $pokemon['name'],
                'moves' => $moves,
                'stats' => $stats,
                'evolution_chain' => $evolution_chain,
                'error' => false,
            ];
            #fazer a função para buscar a evolução, e o retorno em forma de array.
            return $formattedResponse;
        } else {
            return $formattedResponse = [
                'message' => 'Houve um erro, tente novamente',
                'error' => true,
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


    /**
     * Forms the basis for at least one Pokémon.
     * @return stdClass
     */
    public function getPokemonEvolutionChainOnPokeApi($pokemon) {
        $evolution = $this->getPokemonSpecieOnPokeApi($pokemon);
        $response = Http::get("{$evolution['data']['evolution_chain']['url']}");

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

    public function getEvolutionChainFormated($evolution) {
        $pokemon_evolutions = [
            'name' => $evolution['data']['chain']['species']['name'],
            'evolves_to' => [],
        ];

        $array_evolves_to = []; #array auxiliar

        foreach ($evolution['data']['chain']['evolves_to'] as $evolves_to) {
            if (count($evolution['data']['chain']['evolves_to']) > 1) {
                array_push($array_evolves_to, [
                    'name' => $evolves_to['species']['name'],
                    'evolves_to' => []
                ]);
            } else {
                $array_evolves_to = [
                    'name' => $evolves_to['species']['name'],
                    'evolves_to' => []
                ];
            }

            if ($evolves_to['evolves_to']) {
                foreach ($evolves_to['evolves_to'] as $evolves_to_to) {
                    $array_evolves_to['evolves_to'] = [
                        'name' => $evolves_to_to['species']['name'],
                        'evolves_to' => $evolves_to_to['evolves_to'],
                    ];
                }
            }
            $pokemon_evolutions['evolves_to'] = $array_evolves_to;
        }

        return $pokemon_evolutions;
    }
}
