<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Timezone extends Model

{
    protected $table = 'time_zones';

    protected $fillable = [
        'zone_name' ,'zone_value'
    ];


    public function user_meta()
    {
        return $this->belongsTo('App\User');
    }

    public function campaign_meta()
    {
        return $this->hasMany('App\CampaignEmailMeta','campaign_id');
    }
}
