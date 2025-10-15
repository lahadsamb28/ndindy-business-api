<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;



    protected $fillable = [
        'name',
        'description',
        'category_id'
    ];

    protected $appends = ['quantity', 'totalPrice'];

    protected $casts = [
        'totalPrice' => 'decimal:2',
        'quantity' => 'integer',
        'name' => 'string',
        'description' => 'string'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function isLowStock()
    {
        return $this->quantity < 20;
    }
    public function items()
    {
        return $this->hasMany(Items::class);
    }
    public function arrivals()
    {
        return $this->hasManyThrough(Arrivals::class, Items::class, 'product_id', 'id', 'id', 'arrivals_id');
    }
    public function getQuantityAttribute(){
        return $this->items()->count();
    }
    public function getTotalPriceAttribute(){
        return $this->items()->sum('price');
    }
}
