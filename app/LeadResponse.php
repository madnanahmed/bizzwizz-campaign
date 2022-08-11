<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadResponse extends Model
{
    protected $table = 'lead_response';

    protected $fillable = [
        'from',
        'to',
        'body',
        'sms_message_sid',
        'sms_sid',
        'message_sid',
        'account_sid',
        'data'
    ];
}
