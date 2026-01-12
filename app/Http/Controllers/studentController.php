<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class studentController extends Controller
{
    public function signUp(Request $request)
    {
        //Validate the incoming data
        $validatedData = $request->validate([
            'studentFname' => 'required|string|max:255',
            'studentLname' => 'required|string|max:255',
            'studentEmail' => 'required|email|unique:student,studentEmail',
            'studentID' => 'required|string|unique:student,studentID',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Student::create($validatedData);// Save the new student to the database
    }

    public function login(Request $request)
    {
        //Validate the incoming data
        $credentials = $request->validate([
            'studentEmail' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to find the student by email
        $student = Student::where('studentEmail', $credentials['studentEmail'])->first();

        if ($student && password_verify($credentials['password'], $student->password)) {
            // Authentication passed
            return response()->json(['message' => 'Login successful'], 200);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}
