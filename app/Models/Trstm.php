<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trstm extends Model
{
    protected $table = 'trstm';

    protected $fillable = [
        'trs_id',
        'trstm_date',
        'emoney_type',
        'card_no',
        'mid',
        'terminal_id',
        'amount',
        'balance',
        'log',
        'inv',
        'counter',
        'bank_type',
        'user_id',
    ];
}
