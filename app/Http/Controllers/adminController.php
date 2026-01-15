<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class adminController extends Controller
{
    public function login(Request $request)
    {
        // Validate the incoming data
        $credentials = $request->validate([
            'adminID' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to find the admin by adminID
        $admin = Admin::where('adminID', $credentials['adminID'])->first();

        if ($admin && $credentials['password'] === $admin->password) {
            // Authentication passed - store admin info in session
            session([
                'admin_logged_in' => true,
                'admin_id' => $admin->adminID,
                'admin_name' => $admin->adminFName . ' ' . $admin->adminLName,
                'admin_email' => $admin->adminEmail,
                
            ]);

            return redirect('/dashboardAdmin')->with('success', 'Login successful! Welcome ' . $admin->adminFName . ' ' . $admin->adminLName);
        } else {
            // Authentication failed
            return back()->withErrors(['login' => 'Invalid admin ID or password']);
        }
    }

    public function logout(Request $request)
    {
        // Clear all admin session data
        session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email']);

        return redirect('/loginAdmin')->with('success', 'You have been logged out successfully');
    }

    public function dashboard(Request $request)
    {
        // Get the logged-in admin's data
        $admin = Admin::where('adminID', session('admin_id'))->first();

        // Get filter parameters
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $timeFrame = $request->get('time_frame', 'monthly');

        // Get ALL students for analytics (average, highest, lowest spending across all students)
        $allStudents = Student::with(['incomes', 'expenses.category'])->get();

        // Calculate financial data for all students
        foreach ($allStudents as $student) {
            $totalIncome = $student->incomes->sum('incomeAmount');
            $totalExpenses = $student->expenses->sum('expenseAmount');
            $student->total_spending = $totalExpenses;
            $student->current_balance = $totalIncome - $totalExpenses;
        }

        // Calculate analytics based on ALL students (always shows complete data)
        $analytics = $this->calculateAnalytics($allStudents);

        // Build query for filtered students (for the top 5 display)
        $filteredStudentsQuery = Student::with(['incomes', 'expenses.category']);

        // Apply date filters if provided for the top 5 students
        if ($startDate && $endDate) {
            $filteredStudentsQuery->whereHas('expenses', function($query) use ($startDate, $endDate) {
                $query->whereBetween('expenseDate', [$startDate, $endDate]);
            })->orWhereHas('incomes', function($query) use ($startDate, $endDate) {
                $query->whereBetween('incomeDate', [$startDate, $endDate]);
            });
        }

        $filteredStudents = $filteredStudentsQuery->get();

        // Calculate financial data for filtered students
        foreach ($filteredStudents as $student) {
            $totalIncome = $student->incomes->sum('incomeAmount');
            $totalExpenses = $student->expenses->sum('expenseAmount');
            $student->total_spending = $totalExpenses;
            $student->current_balance = $totalIncome - $totalExpenses;
        }

        // Get top 5 students by highest spending (from filtered results)
        $topStudents = $filteredStudents->sortByDesc('total_spending')->take(5);

        // If AJAX request, return JSON with filtered data but complete analytics
        if ($request->ajax()) {
            return response()->json([
                'students' => $topStudents->values(),
                'analytics' => $analytics // Always return complete analytics
            ]);
        }

        return view('admin/dashboardAdmin', compact('admin', 'filteredStudents', 'topStudents', 'analytics'));
    }

    private function calculateAnalytics($students)
    {
        if ($students->isEmpty()) {
            return [
                'average' => 0,
                'highest' => 0,
                'lowest' => 0,
                'average_student' => null,
                'highest_student' => null,
                'lowest_student' => null,
            ];
        }

        $spendings = $students->pluck('total_spending')->filter()->values();
        $average = $spendings->avg();
        $highest = $spendings->max();
        $lowest = $spendings->min();

        // Find students with these values
        $averageStudent = $students->first(function($student) use ($average) {
            return abs($student->total_spending - $average) < 0.01;
        });

        $highestStudent = $students->first(function($student) use ($highest) {
            return $student->total_spending == $highest;
        });

        $lowestStudent = $students->first(function($student) use ($lowest) {
            return $student->total_spending == $lowest;
        });

        return [
            'average' => round($average, 2),
            'highest' => round($highest, 2),
            'lowest' => round($lowest, 2),
            'average_student' => $averageStudent,
            'highest_student' => $highestStudent,
            'lowest_student' => $lowestStudent,
        ];
    }

    public function profile(Request $request)
    {
        // Get the logged-in admin's data
        $admin = Admin::where('adminID', session('admin_id'))->first();

        return view('admin/profileAdmin', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'adminFName' => 'required|string|max:255',
            'adminLName' => 'required|string|max:255',
            'adminEmail' => 'required|email|unique:admins,adminEmail,' . session('admin_id') . ',adminID',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Get the logged-in admin
        $admin = Admin::where('adminID', session('admin_id'))->first();

        if (!$admin) {
            return redirect('/profileAdmin')->withErrors(['admin' => 'Admin not found']);
        }

        // Update admin data
        $admin->adminFName = $validatedData['adminFName'];
        $admin->adminLName = $validatedData['adminLName'];
        $admin->adminEmail = $validatedData['adminEmail'];

        // Only update password if provided
        if (!empty($validatedData['password'])) {
            $admin->password = $validatedData['password'];
        }

        $admin->save();

        // Update session data
        session([
            'admin_name' => $admin->adminFName . ' ' . $admin->adminLName,
            'admin_email' => $admin->adminEmail,
        ]);

        return redirect('/profileAdmin')->with('success', 'Profile updated successfully!');
    }

    public function studentList()
    {
        $students = Student::with(['incomes', 'expenses.category'])->get();
        $admin = Admin::where('adminID', session('admin_id'))->first();

        // Calculate financial data for each student
        foreach ($students as $student) {
            $totalIncome = $student->incomes->sum('incomeAmount');
            $totalExpenses = $student->expenses->sum('expenseAmount');
            $student->current_balance = $totalIncome - $totalExpenses;
            $student->total_spending = $totalExpenses;
            $student->total_income = $totalIncome;
        }

        return view('admin.studentAdmin', compact('students', 'admin'));
    }

    public function studentDetail($studentID)
    {
        $student = Student::with(['incomes', 'expenses.category', 'categories'])->findOrFail($studentID);

        // Calculate expenses by category
        $expenseCategories = $student->expenses->groupBy(function($expense) {
            return $expense->category->categoryName ?? 'Uncategorized';
        })->map(function($expenses) {
            return $expenses->sum('expenseAmount');
        });

        // Calculate income by category (income doesn't have categories in the current model, so we'll group by income name)
        $incomeCategories = $student->incomes->groupBy('incomeName')->map(function($incomes) {
            return $incomes->sum('incomeAmount');
        });

        // Calculate totals
        $totalIncome = $student->incomes->sum('incomeAmount');
        $totalExpenses = $student->expenses->sum('expenseAmount');
        $balance = $totalIncome - $totalExpenses;

        return view('admin.studentDetail', compact(
            'student',
            'expenseCategories',
            'incomeCategories',
            'totalIncome',
            'totalExpenses',
            'balance'
        ));
    }

 
}
