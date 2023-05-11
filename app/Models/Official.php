<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    use HasFactory;

    protected $table = 'officials';

    protected $fillable = [
        'resident_id',
        'position_id',
        'term_start',
        'term_end',
    ];
}
