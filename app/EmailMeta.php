<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class EmailMeta extends Model

{
    protected $table = 'email_meta';

    protected $fillable = [
        'account_id','meta_key' ,'meta_value'
    ];


    public function user_meta()
    {
        return $this->belongsTo('App\User');
    }
}
