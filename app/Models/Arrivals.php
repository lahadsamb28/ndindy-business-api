<?php

namespace App\Models;

use App\Enums\StatusArrival;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arrivals extends Model
{
    use HasFactory;

    protected $fillable = [
        'totalSpend',
        'purchaseCost',
        'shippingCost',
        'supplier',
        'country_id',
        'totalItems',
        'arrival_date',
        'sku',
        'status'
    ];

    protected $casts = [
        'totalSpend' => 'decimal:2',
        'purchaseCost' => 'decimal:2',
        'shippingCost' => 'decimal:2',
        'totalItems' => 'integer',
        'supplier' => 'string',
        'arrival_date' => 'datetime',
        'sku' => 'string',
        'status' => StatusArrival::class
    ];

    protected $attributes = [
        'status' => StatusArrival::PENDING
    ];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function item()
    {
        return $this->hasMany(Items::class);
    }

}
