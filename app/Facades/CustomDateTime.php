<?php 
namespace App\Facades;

use DateTime;

class CustomDateTime extends DateTime {
    public function subDays($days) {
        $this->modify("-{$days} days");
        return $this;
    }

    public function subMonths($months) {
        $this->modify("-{$months} months");
        return $this;
    }

    public function subWeeks($weeks) {
        $this->modify("-{$weeks} weeks");
        return $this;
    }
}