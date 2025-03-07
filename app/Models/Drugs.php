<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drugs extends Model
{
    protected $fillable = ['user_id','drug_details_id', 'quotation_id', 'quantity', 'amount'];
}