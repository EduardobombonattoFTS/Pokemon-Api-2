<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string("plate")->unique()->nullable();
            $table->string("model")->nullable();
            $table->foreignUuid('motorista_id')->references('uuid')->on('drivers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('trucks');
    }
};
