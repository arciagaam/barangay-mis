<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $table = "households";

    protected $fillable = [
        'purok',
        'house_number',
        'block',
        'lot',
        'others',
        'subdivision',
    ];
}
