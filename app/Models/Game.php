<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {
    protected $table = 'games';

    protected $fillable = [
        'name',
        'code',
        'service_id',
        'url',
        'secured_url',
        'description',
        'image',
        'created_at',
        'updated_at',
    ];
}