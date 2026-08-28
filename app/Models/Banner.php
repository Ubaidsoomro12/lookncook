<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'description', 'image', 'link', 'button_text', 'section', 'status', 'sort_order'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Helper to get full image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }

    // Delete the image file when banner is deleted
    public function deleteImageFile(): void
    {
        if ($this->image) {
            $fullPath = public_path($this->image);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }
}