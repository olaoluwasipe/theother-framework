<?php

namespace App\Middleware;

class StartSession {
    public function handle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}