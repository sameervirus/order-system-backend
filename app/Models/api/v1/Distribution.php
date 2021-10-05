<?php

namespace App\Models\api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
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
        'district_id',
        'code'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function cars()
    {
        return $this->hasMany('\App\Models\api\v1\Car');
    }

    public function district()
    {
        return $this->belongsTo('\App\Models\api\v1\District');
    }
}
