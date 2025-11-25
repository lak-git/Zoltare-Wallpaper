<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ErrorLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'error_logs';

    protected $fillable = [
        'message',
        'context',
        'level',
    ];

    protected $casts = [
        'context' => 'array',
    ];
}

