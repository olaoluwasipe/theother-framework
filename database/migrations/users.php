<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

function up() {
    Capsule::schema()->create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('username')->unique();
        $table->string('password');
        $table->string('email')->nullable()->unique();
        $table->timestamps();
    });
}

function down()
{
    Capsule::schema()->dropIfExists('users');
}