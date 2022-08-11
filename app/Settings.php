<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'twilio_sid', 'twilio_token', 'from_number'
    ];
}
