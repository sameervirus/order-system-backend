<?php

namespace App\Models\api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'distribution_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function distribution()
    {
        return $this->belongsTo('\App\Models\api\v1\Distribution');
    }
}
