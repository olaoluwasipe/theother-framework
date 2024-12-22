<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class create_cache_table {
    public function up(){
        Capsule::schema()->create('cache', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->integer('expiration')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('cache');
    }
}