<?php

namespace App\Middleware;

class AuthMiddleware {
    public function handle() {
        if ( strpos($_SERVER['REQUEST_URI'], 'login') ) return;
        if ( strpos($_SERVER['REQUEST_URI'], 'register') ) return;
        if (!isset($_SESSION['user_id'])) {
            redirect( '/login');
            exit;
        }
    }
}