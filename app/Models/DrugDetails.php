<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugDetails extends Model
{
        // Define the table name explicitly
        protected $table = 'drug_details'; // This tells Laravel to use the 'drugs' table.

        // Define the primary key if it's not 'id'
        // protected $primaryKey = 'your_primary_key_column';
    
        // Define which attributes can be mass-assigned (optional)
        // protected $fillable = ['name', 'weight'];
    
        // Or you can use guarded to specify which fields cannot be mass-assigned
        // protected $guarded = ['id'];
}