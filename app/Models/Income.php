<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    protected $table = 'incomes';
    protected $primaryKey = 'incomeID';

    protected $fillable = [
        'incomeName',
        'incomeAmount',
        'incomeDate',
        'studentID',
        'categoryID',
    ];

    protected $casts = [
        'incomeAmount' => 'decimal:2',
        'incomeDate' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentID');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryID');
    }
}
