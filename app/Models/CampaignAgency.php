<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignAgency extends Model {

    protected $table = 'campaign_agencies';

    protected $fillable = [
        'name',
        'code',
        'params',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'params' => 'array',
    ];

}