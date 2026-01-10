<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';
    protected $primaryKey = 'expenseID';

    protected $fillable = [
        'expenseName',
        'expenseAmount',
        'expenseDate',
        'categoryID',
    ];

    protected $casts = [
        'expenseAmount' => 'decimal:2',
        'expenseDate' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryID');
    }

    public function student()
    {
        return $this->hasOneThrough(Student::class, Category::class, 'categoryID', 'studentID', 'categoryID', 'studentID');
    }
}
