<?php

namespace App\Http\Controllers;

use App\Services\DriverAddressService;
use Illuminate\Http\Request;

class DriverAddressController extends Controller {
    public DriverAddressService $service;

    public function __construct(DriverAddressService $service) {
        $this->service = $service;
    }

    public function getAll() {
        return $this->service->getAll();
    }
    /**
     * Create a newly resource in database.
     */
    public function createDriverAddressOnDatabase(Request $request) {
        return $this->service->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDriverAddressOnDatabase(Request $request, $uuid) {
        return $this->service->update($request->all(), $uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteDriverAddressFromDatabase(string $uuid) {
        return $this->service->destroy($uuid);
    }
}
