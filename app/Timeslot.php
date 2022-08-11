<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model

{
    protected $table = 'time_slots';

    protected $fillable = [
        'time_name' ,'time_value'
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
