<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blotter extends Model
{
    use HasFactory;

    protected $table = 'blotters';
    protected $fillable = [
        'incident_type',
        'incident_place',
        'date_time_incident',
        'description'
    ];
    
}
