<?php
namespace App\Middleware;

class VerifyCsrfToken {
    public function handle() {
        verify_csrf_token();
    }
}