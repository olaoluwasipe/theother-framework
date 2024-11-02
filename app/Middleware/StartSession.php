<?php

namespace App\Middleware;

class StartSession {
    public function handle() {
        session_start();
    }
}