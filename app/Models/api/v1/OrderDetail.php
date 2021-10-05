<?php

namespace App\Models\api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty', 'qty_approved', 'qty_production', 'qty_delivered', 'order_id', 'product_id', 'comments',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo('\App\Models\api\v1\Order');
    }

    public function product()
    {
        return $this->belongsTo('\App\Models\api\v1\Product');
    }
}
