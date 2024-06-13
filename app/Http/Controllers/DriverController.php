<?php

namespace App\Http\Controllers;

use App\Services\DriverService;
use Illuminate\Http\Request;

class DriverController extends Controller {

    public DriverService $service;

    public function __construct(DriverService $service) {
        $this->service = $service;
    }

    public function getAll() {
        return $this->service->getAll();
    }

    /**
     * Create a newly resource in database.
     */
    public function createDriverOnDatabase(Request $request) {
        return $this->service->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDriverOnDatabase(Request $request, $uuid) {
        return $this->service->update($request->all(), $uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteDriverFromDatabase(string $uuid) {
        return $this->service->destroy($uuid);
    }
}
