<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleFeedback extends Model
{
    protected $table = "schedule_feedback";

    protected $fillable = [
        'days',
        'hours',
        'minutes',
        'is_sms',
        'sms',
        'is_email',
        'subject',
        'email_body',
        'sheet_id',
        'user_id',

    ];
}
