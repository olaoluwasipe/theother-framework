<?php

namespace App\Facades;

use Core\Logger;

class Log extends Logger
{
    protected $logger;

    public function __construct (Logger $logger) {
        $this->logger = $logger;
    }

    public function __call($method, $args) {

    }

    public function info($message) {
        $this->logger->log($message, 'INFO');
    }

    public function error($message) {
        $this->logger->log($message, 'ERROR');
    }

    public function warning($message) {
        $this->logger->log($message, 'WARNING');
    }

    public function debug($message) {
        $this->logger->log($message, 'DEBUG');
    }

    public function log($message, $level = 'INFO') {
        $this->logger->log($message, $level);
    }
}
