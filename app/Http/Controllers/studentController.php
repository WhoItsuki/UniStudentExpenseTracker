<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function signUp(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'studentFname' => 'required|string|max:255',
            'studentLname' => 'required|string|max:255',
            'studentFaculty' => 'nullable|string|max:255',
            'programme' => 'nullable|string|max:255',
            'studentEmail' => 'required|email|unique:students,studentEmail',
            'studentID' => 'required|string|unique:students,studentID',
            'password' => 'required|string|min:6',
        ]);

        $student = Student::create($validatedData);

        return redirect('/loginStudent')->with('success', 'Account created successfully! Please login.');
    }

    public function login(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'studentID' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find student and verify password
        $student = Student::where('studentID', $credentials['studentID'])->first();
        
        if ($student && $student['password'] === $credentials['password']) {
            session(['student_id' => $student->studentID]);
            return redirect('/dashboardStudent');
        } else {
            return back()->withErrors(['login' => 'Invalid student ID or password']);
        }
    }

    public function logout(Request $request)
    {
        session()->forget('student_id');
        return redirect('/loginStudent')->with('success', 'Logged out successfully');
    }
}