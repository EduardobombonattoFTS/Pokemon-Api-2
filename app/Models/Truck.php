<?php

namespace App\Models;

use App\Traits\STRUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model {
    use HasFactory, STRUUID;

    protected $fillable = [
        'uuid',
        'plate',
        'model',
        'motorista_id',
    ];

    // Define a relação inversa de muitos para um com Driver
    public function driver() {
        return $this->belongsTo(Driver::class, 'motorista_id', 'uuid');
    }
}
