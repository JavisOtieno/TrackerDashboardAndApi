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
        'trip_id',
        'long',
  
    ];

    public function trip(){
        return $this->belongsTo(Trip::class,'trip_id');
    }

}
