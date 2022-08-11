<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SheetsData extends Model
{
    protected  $table = 'sheets_data';

    protected $fillable = [
        'sheet_id', 'data'
    ];
}
