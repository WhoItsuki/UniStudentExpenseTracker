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
            'password' => 'required|string|min:8',
        ]);

        // Hash the password before saving
        $validatedData['password'] = bcrypt($validatedData['password']);
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

        // Attempt to find the student by studentID
        $student = Student::where('studentID', $credentials['studentID'])->first();

        if ($student && password_verify($credentials['password'], $student->password)) {
            // Authentication passed - store student in session
            session(['student_id' => $student->id, 'student_name' => $student->studentFname . ' ' . $student->studentLname]);
            return redirect()->route('student.dashboard');
        } else {
            // Authentication failed
            return back()->withErrors(['studentID' => 'Invalid student ID or password.'])->withInput($request->except('password'));
        }
    }
}
