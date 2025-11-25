<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class Wallpaper extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'wallpapers';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'price',
        'image_path',
        'uploaded_by',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $wallpaper): void {
            if (empty($wallpaper->slug)) {
                $wallpaper->slug = Str::slug($wallpaper->title).'-'.Str::random(6);
            }

            if (! isset($wallpaper->is_active)) {
                $wallpaper->is_active = true;
            }

            if (! empty($wallpaper->category)) {
                // $wallpaper->category = Str::of($wallpaper->category)->lower()->slug('_')->replace('_', '-');
                // Ensure we store a plain string (not a Stringable object) so MongoDB/driver
                // persists the category correctly.
                $wallpaper->category = (string) Str::of($wallpaper->category)
                    ->lower()
                    ->slug('_')
                    ->replace('_', '-');
            }
        });
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isFree(): bool
    {
        return (float) $this->price <= 0;
    }

    public function categoryLabel(): string
    {
        if (is_array($this->category)) {
            return Str::headline(str_replace('-', ' ', (string) implode(',', $this->category)));
        }
        return Str::headline(str_replace('-', ' ', (string) $this->category));
    }
}

