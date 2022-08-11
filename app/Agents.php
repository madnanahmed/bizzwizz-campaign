<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agents extends Model
{
    protected  $table = 'agents';

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'password', 'twilio_number'
    ];
}
