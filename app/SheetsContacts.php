<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SheetsContacts extends Model
{
    protected  $table = 'sheets_contacts';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'lead_id',
        'user_id',
        'sheet_id'
    ];
}
