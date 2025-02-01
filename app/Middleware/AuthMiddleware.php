<?php

namespace App\Middleware;

class AuthMiddleware {
    public function handle() {
        $redirectUrl = $_SERVER['REQUEST_URI'];
        if ( strpos($_SERVER['REQUEST_URI'], 'login') ) $redirectUrl = null; return;
        if ( strpos($_SERVER['REQUEST_URI'], 'register') ) $redirectUrl = null; return;
        if (!isset($_SESSION['user_id'])) {
            // Store the originally requested page before redirecting
            $_SESSION['redirect_after_login'] = $redirectUrl;

            // Redirect to the login page
            redirect( '/login');
            exit;
        }
    }
}