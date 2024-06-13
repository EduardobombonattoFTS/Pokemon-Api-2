<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait STRUUID {
    protected static function boot() {

        parent::boot();

        /**
         * Evento de criação na Model.
         * Gera o Uuid com Str::uuid() na instância que está sendo criada
         */
        static::creating(function ($model) {
            $model->setAttribute("uuid", Str::uuid()->toString());
        });
    }
}
