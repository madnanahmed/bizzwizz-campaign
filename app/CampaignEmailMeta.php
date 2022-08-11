<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class CampaignEmailMeta extends Model

{
    protected $table = 'campaign_email_meta';

    protected $fillable = [
      'id',  'campaign_id', 'email_type', 'email_subject', 'email_content', 'contacts','followup_day'
    ];


    public function campaign_meta()
    {
        return $this->belongsTo('App\Campaigns');
    }

    public function campaign_email_time()
    {
        return $this->hasMany('App\CampaignEmailTime','email_meta_id');
    }

}
