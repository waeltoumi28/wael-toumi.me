<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VasTransactionAnalysis extends Model
{
    protected $table = 'vas_transaction_analysis';
    public $fillable = [
        'vas_client_id', 'vas_centre_id', 'amount', 'points', 'contipayRef', 'reference', 'currency_id', 'status', 'action'
    ];
}
