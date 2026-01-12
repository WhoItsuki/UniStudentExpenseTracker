<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Expense;
use App\Models\Category;

class expenseController extends Controller
{
    // View all expenses for logged-in student
    public function viewExpenses(Request $request)
    {
        $query = Expense::where('studentID', session('student_id'))->with('category');

        // Apply filters if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            if ($startDate && $endDate) {
                $query->whereBetween('expenseDate', [$startDate, $endDate]);
            }
        }

        // Filter by category if provided
        if ($request->has('categoryFilter') && $request->input('categoryFilter')) {
            $query->where('categoryID', $request->input('categoryFilter'));
        }

        $expenses = $query->get();
        $categories = Category::where('studentID', session('student_id'))
            ->where('categoryType', 'expense')
            ->get();
        $totalExpenses = $this->calculateTotalExpenses($expenses);
        return view('student.expenseStudent', compact('expenses', 'categories', 'totalExpenses'));
    }

    // Calculate total expenses
    private function calculateTotalExpenses($expenses)
    {
        return $expenses->sum('expenseAmount');
    }

    
    // Store new expense
    public function addExpense(Request $request)
    {
        $validatedData = $request->validate([
            'expenseName' => 'required|string|max:255',
            'expenseAmount' => 'required|numeric|min:0',
            'expenseDate' => 'required|date',
            'categoryID' => 'required|exists:categories,categoryID',
        ]);
        $validatedData['studentID'] = session('student_id');
        Expense::create($validatedData);
        return redirect('/expense')->with('success', 'Expense added successfully!');
    }

    // Show edit expense form
    public function editExpenseForm($expenseID)
    {
        $expense = Expense::where('expenseID', $expenseID)
            ->where('studentID', session('student_id'))
            ->firstOrFail();
        $categories = Category::where('studentID', session('student_id'))
            ->where('categoryType', 'expense')
            ->get();
        return view('student.editExpense', compact('expense', 'categories'));
    }

    // Update expense
    public function updateExpense(Request $request, $expenseID)
    {
        $expense = Expense::where('expenseID', $expenseID)
            ->where('studentID', session('student_id'))
            ->firstOrFail();
        $validatedData = $request->validate([
            'expenseName' => 'required|string|max:255',
            'expenseAmount' => 'required|numeric|min:0',
            'expenseDate' => 'required|date',
            'categoryID' => 'required|exists:categories,categoryID',
        ]);
        $expense->update($validatedData);
        return redirect('/expense')->with('success', 'Expense updated successfully!');
    }

    // Delete expense
    public function deleteExpense($expenseID)
    {
        $expense = Expense::where('expenseID', $expenseID)
            ->where('studentID', session('student_id'))
            ->firstOrFail();
        $expense->delete();
        return redirect('/expense')->with('success', 'Expense deleted successfully!');
    }
}
