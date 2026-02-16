<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sti extends Model
{
    protected $table = 'sti';

    protected $fillable = [
        'card_type',
        'terminal_id',
        'terminal',
        'card_no',
        'amount',
        'balance',
        'trans_date',
        'ftp_file',
        'created_date',
        'settle_date',
        'response',
        'filename',
        'bank_mid',
        'bank_tid',
        'other',
    ];
}
