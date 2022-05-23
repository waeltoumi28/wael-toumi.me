<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'client_id', 'client_log', 'log_type',
    ];

    // protected $dates = [
    //     'created_at',
    //     'updated_at',
    // ];

    public $timestamps = true;
}
