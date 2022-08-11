<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadFeedback extends Model
{
    protected $table = "lead_feedback";

    protected $fillable = [
        'is_sms',
        'sms',
        'is_email',
        'subject',
        'email_body',
        'sheet_id',
        'user_id',
        'page_id'
    ];
}
