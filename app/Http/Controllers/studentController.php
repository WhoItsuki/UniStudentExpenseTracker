<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
            'password' => 'required|string|min:8',
        ]);

        Student::create($validatedData);// Save the new student to the database
        
        return redirect()->route('student.login.get')->with('success', 'Registration successful! Please login.');
    }

    public function login(Request $request)
    {
        //Validate the incoming data
        $credentials = $request->validate([
            'studentID' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('student')->attempt($credentials)) {
            // Authentication passed - regenerate session for security
            $request->session()->regenerate();
            
            // Store student name in session for display
            $student = Auth::guard('student')->user();
            session(['student_name' => $student->studentFname . ' ' . $student->studentLname]);
            
            return redirect()->route('student.dashboard');
        } else {
            // Authentication failed
            return back()->withErrors(['studentID' => 'Invalid student ID or password.'])->withInput($request->except('password'));
        }
    }
        

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('student.login.get')->with('success', 'You have been logged out successfully.');
    }
}
