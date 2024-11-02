<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDetail extends Model {
    protected $connection = 'mysql2';

    protected $table = 'sub_details';

    protected $fillable = [
        'msisdn',
        't_date',
        'ticket_id',
        'sub_status',
        'play_status',
        'service_id',
        'play_date',
        'trans_id',
        'updated_at',
    ];
}