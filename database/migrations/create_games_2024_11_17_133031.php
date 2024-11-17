<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class create_games {
    public function up(){
        Capsule::schema()->create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('service_id');
            $table->string('url')->nullable();
            $table->string('secured_url')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('games');
    }
}