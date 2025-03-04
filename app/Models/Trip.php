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
        'amount'
    ];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }


}
