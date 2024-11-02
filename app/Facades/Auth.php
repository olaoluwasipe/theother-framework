<?php
namespace App\Facades;

use App\Models\User;

class Auth {
    public static function user() {
        if (isset($_SESSION['user_id'])) {
            return User::find($_SESSION['user_id']);
        }
        return null;
    }

    public static function check() {
        return isset($_SESSION['user_id']);
    }

    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function logout() {
        unset($_SESSION['user_id']);
    }
}
