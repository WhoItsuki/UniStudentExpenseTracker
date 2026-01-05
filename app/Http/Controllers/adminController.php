<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class adminController extends Controller
{
    public function studentList()
    {
        $students = Student::with(['incomes', 'expenses.category'])->get();

        // Calculate financial data for each student
        foreach ($students as $student) {
            $totalIncome = $student->incomes->sum('incomeAmount');
            $totalExpenses = $student->expenses->sum('expenseAmount');
            $student->current_balance = $totalIncome - $totalExpenses;
            $student->total_spending = $totalExpenses;
            $student->total_income = $totalIncome;
        }

        return view('admin.studentAdmin', compact('students'));
    }

    public function getStudentDetails($studentID)
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

        return response()->json([
            'student' => $student,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'balance' => $balance
        ]);
    }
}
