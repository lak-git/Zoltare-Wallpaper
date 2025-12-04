<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class WallpaperResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => (string) $this->getKey(),
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'price' => $this->price,
            'is_featured' => (bool) $this->is_featured,
            'is_active' => (bool) $this->is_active,
            'image_path' => $this->image_path,
            'image_url' => $this->when($this->image_path, function () {
                $disk = config('filesystems.default');
                try {
                    // If the configured disk exposes a URL helper, use it. Otherwise
                    // fall back to a safe asset path so APIs always return a usable
                    // value for clients.
                    $diskInstance = Storage::disk($disk);

                    if (method_exists($diskInstance, 'url')) {
                        return $diskInstance->url($this->image_path);
                    }

                    return asset('storage/'.$this->image_path);
                } catch (\Throwable $e) {
                    return $this->image_path;
                }
            }),
            'uploaded_by' => $this->uploaded_by,
            'created_at' => optional($this->created_at)?->toDateTimeString(),
            'updated_at' => optional($this->updated_at)?->toDateTimeString(),
        ];
    }
}
