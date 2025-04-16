<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'lat',
        'long',
        'trip_id',
        'user_id',
        'accuracy',
        'distance',
        'type',
        'name'
    ];

    public function trip(){
        return $this->belongsTo(Trip::class,'trip_id');
    }

    
    public function user(){
        //return $this->belongsTo(User::class);
        return $this->belongsTo(User::class,'user_id');
    }

}
