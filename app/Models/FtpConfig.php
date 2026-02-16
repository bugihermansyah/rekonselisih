<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FtpConfig extends Model
{
    protected $fillable = [
        'name',
        'host',
        'port',
        'username',
        'password',
        'path',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'password' => 'encrypted',
        ];
    }
}
