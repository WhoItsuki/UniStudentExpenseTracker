<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'studentFname',
        'studentLname',
        'studentEmail',
        'studentID',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
