<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $fillable = [
        'number',
        'first_name',
        'last_name',
        'birthdate',
        'voti',
    ];

    protected $dates = ['birthdate'];
}
