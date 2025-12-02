<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // INI YANG KURANG TADI:
    // Buka gembok agar 'order_id', 'product_id', dll bisa masuk
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
