<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
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
}
