<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'vas_client_subs';
    protected $fillable = [
        'centre_id', 'user_id', 'is_Subscribed', 'sub_type'
    ];
}
