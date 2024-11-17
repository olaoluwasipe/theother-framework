<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $connection = 'mysql2';
    
    protected $table = 'transactions';

    protected $fillable = ['msisdn','t_date','charges_status','service_id','play_date','trans_id','bearer_id','amount'];
}