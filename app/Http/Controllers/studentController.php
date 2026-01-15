<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    /**
     * Get date range based on period
     */
    private function getDateRange($period)
    {
        $now = Carbon::now();

        switch ($period) {
            case 'weekly':
                // Current week: Monday to Sunday
                return [
                    'start' => $now->copy()->startOfWeek(Carbon::MONDAY),
                    'end' => $now->copy()->endOfWeek(Carbon::SUNDAY)
                ];
            case 'monthly':
                // Current month: 1st to last day of month
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'yearly':
                // Current year: January 1st to December 31st
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            default:
                // Default to current month
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
    }

    /**
     * Get total expenses by category with period filter (API endpoint)
     */
    public function getExpensesByCategory(Request $request, $period = 'monthly')
    {
        // Validate period parameter
        $validPeriods = ['weekly', 'monthly', 'yearly'];
        if (!in_array($period, $validPeriods)) {
            return response()->json(['error' => 'Invalid period. Must be weekly, monthly, or yearly.'], 400);
        }

        $studentID = session('student_id');
        $dateRange = $this->getDateRange($period);

        $expensesByCategory = Expense::where('studentID', $studentID)
            ->whereBetween('expenseDate', [$dateRange['start'], $dateRange['end']])
            ->with('category')
            ->selectRaw('categoryID, SUM(expenseAmount) as total_amount')
            ->groupBy('categoryID')
            ->get()
            ->map(function ($expense) {
                return [
                    'category_name' => $expense->category->categoryName,
                    'total_amount' => $expense->total_amount
                ];
            });

        return response()->json([
            'period' => $period,
            'date_range' => [
                'start' => $dateRange['start']->format('Y-m-d'),
                'end' => $dateRange['end']->format('Y-m-d')
            ],
            'data' => $expensesByCategory
        ]);
    }

    /**
     * Get budget vs expense comparison with period filter (API endpoint)
     */
    public function getBudgetVsExpense(Request $request, $period = 'monthly')
    {
        // Validate period parameter
        $validPeriods = ['weekly', 'monthly', 'yearly'];
        if (!in_array($period, $validPeriods)) {
            return response()->json(['error' => 'Invalid period. Must be weekly, monthly, or yearly.'], 400);
        }

        $studentID = session('student_id');
        $dateRange = $this->getDateRange($period);

        $totalBudget = Budget::where('studentID', $studentID)
            ->whereBetween('budgetDate', [$dateRange['start'], $dateRange['end']])
            ->sum('budgetLimit');

        $totalExpense = Expense::where('studentID', $studentID)
            ->whereBetween('expenseDate', [$dateRange['start'], $dateRange['end']])
            ->sum('expenseAmount');

        return response()->json([
            'period' => $period,
            'date_range' => [
                'start' => $dateRange['start']->format('Y-m-d'),
                'end' => $dateRange['end']->format('Y-m-d')
            ],
            'data' => [
                'total_budget' => $totalBudget,
                'total_expense' => $totalExpense,
                'remaining_budget' => $totalBudget - $totalExpense,
                'budget_status' => $totalExpense > $totalBudget ? 'over_budget' : 'within_budget'
            ]
        ]);
    }

    public function dashboard()
    {
        $studentID = session('student_id');

        // Get current month start and end dates
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        // 1. Total expenses by category for current month
        $expensesByCategory = Expense::where('studentID', $studentID)
            ->whereBetween('expenseDate', [$currentMonthStart, $currentMonthEnd])
            ->with('category')
            ->selectRaw('categoryID, SUM(expenseAmount) as total_amount')
            ->groupBy('categoryID')
            ->get()
            ->map(function ($expense) {
                return [
                    'category_name' => $expense->category->categoryName,
                    'total_amount' => $expense->total_amount
                ];
            });

        // 2. Current balance (Income - Expense) for current month
        $totalIncome = Income::where('studentID', $studentID)
            ->whereBetween('incomeDate', [$currentMonthStart, $currentMonthEnd])
            ->sum('incomeAmount');

        $totalExpense = Expense::where('studentID', $studentID)
            ->whereBetween('expenseDate', [$currentMonthStart, $currentMonthEnd])
            ->sum('expenseAmount');

        $currentBalance = $totalIncome - $totalExpense;

        // 3. Budget vs Expense comparison for current month (Total)
        $totalBudget = Budget::where('studentID', $studentID)
            ->whereBetween('budgetDate', [$currentMonthStart, $currentMonthEnd])
            ->sum('budgetLimit');

        $totalExpenseForBudget = Expense::where('studentID', $studentID)
            ->whereBetween('expenseDate', [$currentMonthStart, $currentMonthEnd])
            ->sum('expenseAmount');

        return view('student.dashboardStudent', compact(
            'expensesByCategory',
            'totalIncome',
            'totalExpense',
            'currentBalance',
            'totalBudget',
            'totalExpenseForBudget'
        ));
    }
}