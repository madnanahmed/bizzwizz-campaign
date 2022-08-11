<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected  $table = 'campaign';

    protected $fillable = [
         'title', 'sms_body', 'mms_id','folder', 'match_names','email_subject','email_body'
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
