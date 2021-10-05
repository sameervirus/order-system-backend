<?php

namespace App\Models\api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\api\v1\Order;
use App\Models\api\v1\OrderDetail;

class Product extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pool_code',
        'code',
        'client_code',
        'slug',
        'category_id',
        'name',
        'name_ar',
        'price',
        'vat'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo('\App\Models\api\v1\Category');
    }

    public function details()
    {
        return $this->hasMany('\App\Models\api\v1\OrderDetail');
    }

    public function productOrders()
    {
        return $this->belongsToMany(Order::class, 'order_details')->withPivot('qty', 'qty_approved', 'qty_production', 'qty_delivered');
    }

}
