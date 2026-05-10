<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoilSample extends Model
{
    protected $fillable = [
        'sample_id',
        'email',
        'location',
        'sample_date',
        'soil_type',
        'crop_type',
        'ph_value',
        'nitrogen',
        'phosphorus',
        'potassium',
        'calcium',
        'magnesium',
        'sulfur',
        'moisture_value',
    ];
}
