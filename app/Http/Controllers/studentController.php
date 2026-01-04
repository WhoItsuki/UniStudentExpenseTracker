<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function signUp(Request $request)
    {
        //Validate the incoming data
        $validatedData = $request->validate([
            'studentFname' => 'required|string|max:255',
            'studentLname' => 'required|string|max:255',
            'studentFaculty' => 'nullable|string|max:255',
            'programme' => 'nullable|string|max:255',
            'studentEmail' => 'required|email|unique:students,studentEmail',
            'studentID' => 'required|string|unique:students,studentID',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the student
        $student = Student::create($validatedData);

        // Log the student in automatically after registration
        Auth::guard('student')->login($student);

        return response()->json(['message' => 'Registration successful', 'student' => $student], 201);
    }

    public function login(Request $request)
    {
        //Validate the incoming data
        $credentials = $request->validate([
            'studentEmail' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate using the student guard
        if (Auth::guard('student')->attempt([
            'studentEmail' => $credentials['studentEmail'],
            'password' => $credentials['password']
        ])) {
            $student = Auth::guard('student')->user();
            return response()->json([
                'message' => 'Login successful',
                'student' => $student
            ], 200);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
