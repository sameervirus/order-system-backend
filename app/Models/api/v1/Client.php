<?php

namespace App\Models\api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
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
        'com_reg',
        'email',
        'person',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function branches()
    {
        return $this->hasMany('\App\Models\api\v1\Branch');
    }

    public function categories()
    {
        return $this->belongsToMany('\App\Models\api\v1\Category');
    }
}
