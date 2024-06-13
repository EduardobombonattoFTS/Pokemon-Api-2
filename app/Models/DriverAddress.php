<?php

namespace App\Models;

use App\Traits\STRUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverAddress extends Model {
    use HasFactory, STRUUID;

    protected $fillable = [
        'uuid',
        'street',
        'number',
        'district',
        'city',
        'state',
        'motorista_id',
    ];
    // Define a relação inversa de um para um com Driver
    public function driver() {
        return $this->belongsTo(Driver::class, 'driver_id', 'uuid');
    }
}
