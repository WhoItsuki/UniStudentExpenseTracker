<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'students';

    protected $primaryKey = 'studentID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'studentFname',
        'studentLname',
        'studentFaculty',
        'programme',
        'studentEmail',
        'studentID',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getAuthIdentifierName()
    {
        return 'studentID';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'studentID');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'studentID');
    }

    public function expenses()
    {
        return $this->hasManyThrough(Expense::class, Category::class, 'studentID', 'categoryID');
    }

    public function budgets()
    {
        return $this->hasManyThrough(Budget::class, Category::class, 'studentID', 'categoryID');
    }
}
