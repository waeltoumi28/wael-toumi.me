<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientWallet extends Model
{
    protected $table = 'vas_client_wallet';
    
    public $fillable = [
        'vas_client_id', 'wallet_credits', 'loyalty_credits', 'currency_id'
    ];

    public $timestamps = false;
}
