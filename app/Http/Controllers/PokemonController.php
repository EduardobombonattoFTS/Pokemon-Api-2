<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PokemonController extends Controller {
    /**
     * Obtain the array of the name of the first generation pokemons
     * @return array
     */

    public function showPokemonsFirstGeneration() {
        $pokemons = $this->pokeApiService->getPokemonFirstGeneration(0, 151);
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
    public function showPokemon($idOrName) {
        $pokemon = $this->pokeApiService->getPokemon($idOrName);

        if ($pokemon['response']->getStatusCode() === 404) {
            return ['data' => $pokemon['response']->getData()->message];
        }

        $evolution = $this->getEvolution($idOrName);
        $evolution_chain = $this->evolutionChain($evolution);

        $moves = []; #array dos moves do pokemon a ser retornada
        $stats = []; #array dos stats do pokemon a ser retornada

        #pega o nome de todos os moves do pokemon
        foreach ($pokemon['data']['moves'] as $pokemon_move) {
            array_push($moves, $pokemon_move['move']['name']);
        }
        #pega o nome e stat base de todos os stats do pokemon
        foreach ($pokemon['data']['stats'] as $pokemon_stat) {
            array_push($stats, [
                'name' => $pokemon_stat['stat']['name'],
                'base_stat' => $pokemon_stat['base_stat'],
            ]);
        }

        $response = [
            'name' => $pokemon['data']['name'],
            'moves' => $moves,
            'stats' => $stats,
            'evolution_chain' => $evolution_chain,
        ];
        #fazer a função para buscar a evolução, e o retorno em forma de array.
        return $response;
    }

    public function getEvolution($pokemon) {
        $evolution = $this->getSpecie($pokemon);
        $evolution_chain =  json_decode(Http::get($evolution['data']['evolution_chain']['url']));

        return ['response' => $evolution_chain];
    }

    public function getSpecie($idOrName) {
        $pokemon_specie = $this->pokeApiService->getSpecie($idOrName);
        return $pokemon_specie;
    }

    #trata a evolution chain e retorna em forma de array.
    #como o maximo de evoluções que um pokemon pode ter é 2, então esse código funciona!
    public function evolutionChain($evolution) {
        $pokemon_evolutions = [
            'name' => $evolution['response']->chain->species->name,
            'evolves_to' => [],
        ];
        $array_evolves_to = []; #array auxiliar

        foreach ($evolution['response']->chain->evolves_to as $evolves_to) {
            if (count($evolution['response']->chain->evolves_to) > 1) {
                array_push($array_evolves_to, [
                    'name' => $evolves_to->species->name,
                    'evolves_to' => []
                ]);
            } else {
                $array_evolves_to = [
                    'name' => $evolves_to->species->name,
                    'evolves_to' => []
                ];
            }

            if ($evolves_to->evolves_to) {
                foreach ($evolves_to->evolves_to as $evolves_to_to) {
                    $array_evolves_to['evolves_to'] = [
                        'name' => $evolves_to_to->species->name,
                        'evolves_to' => $evolves_to_to->evolves_to,
                    ];
                }
            }
            $pokemon_evolutions['evolves_to'] = $array_evolves_to;
        }

        return $pokemon_evolutions;
    }
}
