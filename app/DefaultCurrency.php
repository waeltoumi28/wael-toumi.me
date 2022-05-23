<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultCurrency extends Model
{
    protected $fillable = [
        'merchant_id', 'client_id', 'currency_id',
    ];
}
