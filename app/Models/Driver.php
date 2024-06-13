<?php

namespace App\Models;

use App\Traits\STRUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model {
    use HasFactory, STRUUID;

    protected $fillable = [
        'uuid',
        'full_name',
        'cpf',
        'phone',
    ];
    // Define a relação de um para muitos com Truck
    public function trucks() {
        return $this->hasMany(Truck::class, 'motorista_id', 'uuid');
    }

    // Define a relação de um para um com DriverAddress
    public function address() {
        return $this->hasOne(DriverAddress::class, 'driver_id', 'uuid');
    }
}
