<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSettings extends Model
{
    protected  $table = 'email_settings';

    protected $fillable = [
        'from_email', 'from_name', 'reply_to'
    ];
}
