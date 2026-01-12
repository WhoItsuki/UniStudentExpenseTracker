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
            'programme' => 'required|string|max:255',
            'studentFaculty' => 'required|string|max:255',
            'studentEmail' => 'required|email|unique:students,studentEmail',
            'studentID' => 'required|string|unique:students,studentID',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Student::create($validatedData);// Save the new student to the database
    }

    public function login(Request $request)
    {
        //Validate the incoming data
        $credentials = $request->validate([
            'studentID' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to find the student by studentID (not email as per login form)
        $student = Student::where('studentID', $credentials['studentID'])->first();

        if ($student && $credentials['password'] === $student->password) {
            // Authentication passed - store student info in session
            session([
                'student_logged_in' => true,
                'student_id' => $student->studentID,
                'student_name' => $student->studentFname . ' ' . $student->studentLname,
                'student_email' => $student->studentEmail,
            ]);

            return redirect('/dashboardStudent')->with('success', 'Login successful! Welcome ' . $student->studentFname . ' ' . $student->studentLname);
        } else {
            // Authentication failed
            return back()->withErrors(['login' => 'Invalid student ID or password']);
        }
    }

    public function logout(Request $request)
    {
        // Clear all student session data
        session()->forget(['student_logged_in', 'student_id', 'student_name', 'student_email']);

        return redirect('/loginStudent')->with('success', 'You have been logged out successfully');
    }

    public function profile(Request $request)
    {
        // Get the logged-in student's data
        $student = Student::where('studentID', session('student_id'))->first();

        if (!$student) {
            return redirect('/loginStudent')->withErrors(['auth' => 'Student not found']);
        }

        return view('student.profileStudent', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'studentFname' => 'required|string|max:255',
            'studentLname' => 'required|string|max:255',
            'programme' => 'required|string|max:255',
            'studentFaculty' => 'required|string|max:255',
            'studentEmail' => 'required|email|unique:students,studentEmail,' . session('student_id') . ',studentID',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Get the logged-in student's data
        $student = Student::where('studentID', session('student_id'))->first();

        if (!$student) {
            return redirect('/loginStudent')->withErrors(['auth' => 'Student not found']);
        }

        // Update student data
        $student->studentFname = $validatedData['studentFname'];
        $student->studentLname = $validatedData['studentLname'];
        $student->programme = $validatedData['programme'];
        $student->studentFaculty = $validatedData['studentFaculty'];
        $student->studentEmail = $validatedData['studentEmail'];

        // Only update password if provided
        if (!empty($validatedData['password'])) {
            $student->password = $validatedData['password'];
        }

        $student->save();

        // Update session data
        session([
            'student_name' => $student->studentFname . ' ' . $student->studentLname,
            'student_email' => $student->studentEmail,
        ]);

        return redirect('/profileStudent')->with('success', 'Profile updated successfully!');
    }
}