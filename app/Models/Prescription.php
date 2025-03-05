<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id','images', 'note', 'delivery_address', 'delivery_time'];

    protected $casts = [
        'images' => 'array', // Automatically convert JSON to array
    ];
}