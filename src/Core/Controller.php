<?php
namespace Core;

class Controller {
    public function view($view, $data = []) {
        extract($data);
        require "../resources/views/{$view}.php";
    }
}
