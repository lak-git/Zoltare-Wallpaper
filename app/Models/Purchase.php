<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'purchases';

    protected $fillable = [
        'user_id',
        'wallpaper_id',
        'stripe_session_id',
        'status',
    ];

    protected $casts = [
        'user_id' => 'string',
        'wallpaper_id' => 'string',
    ];

    public function wallpaper()
    {
        return $this->belongsTo(Wallpaper::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

