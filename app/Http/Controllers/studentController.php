<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class studentController extends Controller
{
    public function signUp(Request $request)
    {
        $validatedData = $request->validate([
            'studentFname' => 'required|string|max:255',
            'studentLname' => 'required|string|max:255',
            'studentEmail' => 'required|email|unique:students,studentEmail',
            'studentID' => 'required|string|unique:students,studentID',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }
}
