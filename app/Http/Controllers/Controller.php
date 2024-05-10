<?php

namespace App\Http\Controllers;

use App\Services\PokeAPIService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;
    /**
     * PokeAPI to consume.
     * @var App\Services\PokeAPIService
     */
    protected $pokeApiService;

    public function __construct(PokeAPIService $pokeApiService) {
        $this->pokeApiService = $pokeApiService;
    }
}
