<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model

{
    protected $table = 'students'; // Fixed table name to match migration

    protected $fillable = [
        'studentFname',
        'studentLname',
        'studentEmail',
        'studentID',
        'password',
        'studentFaculty',
        'programme',
    ];

    protected $hidden = [
        'password',
    ];

    public function incomes()
    {
        return $this->hasMany(Income::class, 'studentID', 'studentID');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'studentID', 'studentID');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'studentID', 'studentID');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'studentID', 'studentID');
    }
}
