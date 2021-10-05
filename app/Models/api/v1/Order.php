<?php

namespace App\Models\api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'date', 'due_date', 'branch_id', 'status_id', 'created_id', 'confirmed_id', 'edit_id', 'approved_id', 'production_id', 'driver_id', 'recevied_id'
    ];

    public function details()
    {
    	return $this->HasMany('\App\Models\api\v1\OrderDetail');
    }

    public function branch()
    {
    	return $this->belongsTo('\App\Models\api\v1\Branch');
    }

    public function status()
    {
    	return $this->belongsTo('\App\Models\api\v1\Status');
    }

    public function qty()
    {
    	return $this->details()->sum('qty');
    }

    public function approved()
    {
    	return $this->details()->sum('qty_approved');
    }

    public function creater()
    {
    	return $this->belongsTo('\App\Models\User', 'created_id');
    }

    public function confirmer()
    {
    	return $this->belongsTo('\App\Models\User', 'confirmed_id');
    }

    public function editer()
    {
    	return $this->belongsTo('\App\Models\User', 'edit_id');
    }

    public function approval()
    {
    	return $this->belongsTo('\App\Models\User', 'approved_id');
    }

    public function production()
    {
    	return $this->belongsTo('\App\Models\User', 'production_id');
    }

    public function driver()
    {
    	return $this->belongsTo('\App\Models\User', 'driver_id');
    }

    public function recevier()
    {
    	return $this->belongsTo('\App\Models\User', 'recevied_id');
    }
}
