<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // DO, SBY, FLT, CI, CO, UNK
            $table->string('flight_number')->nullable();
            $table->string('departure_airport')->nullable();
            $table->string('arrival_airport')->nullable();
            $table->date('date');
            $table->time('start_time')->nullable(); // STD (Zulu)
            $table->time('end_time')->nullable(); // STA (Zulu)
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists('events');
    }
};
