<?php

namespace App\Middleware;

class AuthMiddleware {
    public function handle() {
        $redirectUrl = $_SERVER['REQUEST_URI'];

        // Prevent storing the login/register page as redirect URL
        if (strpos($redirectUrl, 'login') !== false || strpos($redirectUrl, 'register') !== false) {
            return;
        }

        if (!isset($_SESSION['user_id'])) {
            // Store the originally requested page before redirecting
            $_SESSION['redirect_after_login'] = $redirectUrl;

            // Redirect to the login page
            redirect('/login');
            exit;
        }
    }
}
