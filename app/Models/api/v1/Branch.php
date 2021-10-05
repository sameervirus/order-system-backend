<?php

namespace App\Models\api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_ar',
        'slug',
        'due_period',
		'close_time',
		'client_id',
		'city_id',
		'district_id',
		'distribution_id',
        'code',
        'vat',
        'opening',
        'closing',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function client()
    {
        return $this->belongsTo('\App\Models\api\v1\Client');
    }

    public function city()
    {
        return $this->belongsTo('\App\Models\api\v1\City');
    }

    public function district()
    {
        return $this->belongsTo('\App\Models\api\v1\District');
    }

    public function distribution()
    {
        return $this->belongsTo('\App\Models\api\v1\Distribution');
    }

    public function getAttributeCity()
    {
        return $this->city->name;
    }

    public function orders()
    {
        return $this->hasMany('\App\Models\api\v1\Order');
    }
}
