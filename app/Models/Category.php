<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'categoryID';

    protected $fillable = [
        'categoryName',
        'categoryType',
        'studentID',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentID');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'categoryID');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'categoryID');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'categoryID');
    }
}
