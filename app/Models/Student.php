<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $table = 'student';

    protected $fillable = [
        'studentFname',
        'studentLname',
        'studentEmail',
        'studentID',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'studentID';
    }

    /**
     * Get the password field name for authentication.
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'password';
    }
}
