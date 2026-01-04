<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';
    protected $primaryKey = 'budgetID';

    protected $fillable = [
        'budgetName',
        'budgetLimit',
        'budgetDate',
        'categoryID',
    ];

    protected $casts = [
        'budgetLimit' => 'decimal:2',
        'budgetDate' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryID');
    }
}
