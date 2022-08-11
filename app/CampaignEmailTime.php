<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class CampaignEmailTime extends Model

{
    protected $table = 'campaign_email_time';

    protected $fillable = [
       'id', 'email_meta_id', 'day' , 'start_interval', 'end_interval','is_enabled'
    ];



    public function campaign_email_time()
    {
        return $this->belongsTo('App\CampaignEmailMeta');
    }

}
