<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $table = 'residents';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'nickname',
        'sex',
        'birth_date',
        'age',
        'place_of_birth',
        'civil_status_id',
        'occupation_id',
        'religion_id',
        'household_id',
        'phone_number',
        'telephone_number',
        'voter_status',
        'disabled',
        'head_of_the_family'
    ];
}
