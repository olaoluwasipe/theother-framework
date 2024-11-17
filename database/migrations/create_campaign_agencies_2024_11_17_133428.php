<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class create_campaign_agencies {
    public function up(){
        Capsule::schema()->create('campaign_agencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->json('params');
            $table->timestamps();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('campaign_agencies');
    }
}