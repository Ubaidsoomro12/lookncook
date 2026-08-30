<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'sale_price',
        'weight',
        'variation',
        'image',
        'status',
    ];

    protected $casts = [
        'variation' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Safe display string for variation — use this instead of $product->variation
     * anywhere in Blade. Fixes: htmlspecialchars(): Argument #1 must be of type
     * string, array given.
     */
    public function getVariationDisplayAttribute()
    {
        if (!$this->variation) {
            return '—';
        }

        if (is_array($this->variation)) {
            return collect($this->variation)->map(function ($item) {
                if (is_array($item)) {
                    if (isset($item['weight']) || isset($item['price'])) {
                        $label = $item['weight'] ?? '';
                        if (!empty($item['price'])) {
                            $label .= ' - Rs.' . $item['price'];
                        }
                        return trim($label, ' -');
                    }
                    return json_encode($item);
                }
                return (string) $item;
            })->implode(', ');
        }

        return (string) $this->variation;
    }
}