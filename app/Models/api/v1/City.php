<?php

namespace App\Models\api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
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
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function districts()
    {
        return $this->hasMany('\App\Models\api\v1\District');
    }
}
