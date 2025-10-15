<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'arrivals_id',
        'product_id',
        'cost',
        'price',
        'brand',
        'model',
        'condition',
        'imei',
        'battery_health',
        'image_url',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'price' => 'decimal:2',
        'brand' => 'string',
        'model' => 'string',
        'condition' => \App\Enums\ECondition::class,
        'imei' => 'string',
        'battery_health' => 'integer',
        'image_url' => 'string',
    ];

    public function arrival()
    {
        return $this->belongsTo(Arrivals::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
