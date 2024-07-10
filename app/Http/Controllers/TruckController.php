<?php

namespace App\Http\Controllers;

use App\Services\TruckService;
use Illuminate\Http\Request;

class TruckController extends Controller {
    public TruckService $service;

    public function __construct(TruckService $service) {
        $this->service = $service;
    }

    public function index() {
        return $this->service->index();
    }
    public function getAll() {
        return $this->service->getAllDataFromDatabase();
    }
    /**
     * Create a newly resource in database.
     */
    public function createTruckOnDatabase(Request $request) {
        return $this->service->createTruckOnDatase($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateTruckOnDatabase(Request $request, $uuid) {
        return $this->service->updateTruckOnDatase($request->all(), $uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteTruckFromDatabase(string $uuid) {
        return $this->service->destroyTruckOnDatase($uuid);
    }
}
