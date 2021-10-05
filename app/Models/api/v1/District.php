<?php

namespace App\Models\api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
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
        'city_id',
        'code'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function city()
    {
    	return $this->belongsTo('\App\Models\api\v1\City');
    }
}
