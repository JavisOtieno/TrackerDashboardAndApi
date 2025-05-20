<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'start_location',
        'start_lat',
        'start_long',
        'end_location',
        'end_lat',
        'end_long',
        'description',
        'amount',
        'distance',
        'status',
        'customer_id',
        'user_id'
    ];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    
    public function user(){
        //return $this->belongsTo(User::class);
        return $this->belongsTo(User::class,'user_id');
    }

    public function customer(){
        //return $this->belongsTo(User::class);
        return $this->belongsTo(User::class,'customer_id');
    }


}
