<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model {
    protected $connection = 'mysql2';
    
    protected $table = 'campaign_log';

    protected $fillable = ['t_date','travel_id','uniq_id','status','cource_id','service_code'];
}