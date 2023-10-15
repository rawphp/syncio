<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'sku',
        'barcode',
        'image_id',
        'inventory_quantity',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
