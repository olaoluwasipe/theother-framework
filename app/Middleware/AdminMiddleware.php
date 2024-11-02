<?php

namespace App\Middleware;

class AdminMiddleware {
    public function handle() {
        if (!isset($_SESSION['admin'])) {
            echo "Access denied. Admins only.";
            exit;
        }
    }
}