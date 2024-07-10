<?php

namespace App\Http\Controllers;

use App\Services\DriverService;
use Illuminate\Http\Request;

class DriverController extends Controller {

    public DriverService $service;

    public function __construct(DriverService $service) {
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
    public function createDriverOnDatabase(Request $request) {
        return $this->service->createDriverOnDatabase($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDriverOnDatabase(Request $request, $uuid) {
        return $this->service->updateDriverOnDatabase($request->all(), $uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteDriverFromDatabase(string $uuid) {
        return $this->service->destroyDriverOnDatabase($uuid);
    }
}
