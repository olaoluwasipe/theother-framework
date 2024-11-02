<?php
namespace Core;

class Logger {
    protected $logFile;

    public function __construct($logFile = 'app.log') {
        // Set the log file path
        $this->logFile = __DIR__ . '/../logs/' . $logFile;

        // Create logs directory if it doesn't exist
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
    }

    public function log($message, $level = 'INFO') {
        // Prepare the log message
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] [$level] $message" . PHP_EOL;

        // Write the log message to the log file
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    public function info($message) {
        $this->log($message, 'INFO');
    }

    public function error($message) {
        $this->log($message, 'ERROR');
    }

    public function warning($message) {
        $this->log($message, 'WARNING');
    }

    public function debug($message) {
        $this->log($message, 'DEBUG');
    }
}
