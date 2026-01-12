<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Budget;
use App\Models\Category;

class budgetController extends Controller
{
    // View all budgets for logged-in student, with filters
    public function viewBudgets(Request $request)
    {
        $query = Budget::where('studentID', session('student_id'))->with('category');

        // Apply filters if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            if ($startDate && $endDate) {
                $query->whereBetween('budgetDate', [$startDate, $endDate]);
            }
        }
        if ($request->has('categoryFilter') && $request->input('categoryFilter')) {
            $query->where('categoryID', $request->input('categoryFilter'));
        }

        $budgets = $query->get();
        $categories = Category::where('studentID', session('student_id'))
            ->where('categoryType', 'expense')
            ->get();
        $totalBudgets = $this->calculateTotalBudgets($budgets);
        return view('student.budgetStudent', compact('budgets', 'categories', 'totalBudgets'));
    }

    // Calculate total budgets
    private function calculateTotalBudgets($budgets)
    {
        return $budgets->sum('budgetLimit');
    }

    // Store new budget
    public function addBudget(Request $request)
    {
        $validatedData = $request->validate([
            'budgetName' => 'required|string|max:255',
            'budgetLimit' => 'required|numeric|min:0',
            'budgetDate' => 'required|date',
            'categoryID' => 'required|exists:categories,categoryID',
        ]);
        $validatedData['studentID'] = session('student_id');
        Budget::create($validatedData);
        return redirect('/budget')->with('success', 'Budget added successfully!');
    }

    // Show edit budget form
    public function editBudgetForm($budgetID)
    {
        $budget = Budget::where('budgetID', $budgetID)
            ->where('studentID', session('student_id'))
            ->firstOrFail();
        $categories = Category::where('studentID', session('student_id'))
            ->where('categoryType', 'expense')
            ->get();
        return view('student.editBudget', compact('budget', 'categories'));
    }

    // Update budget
    public function updateBudget(Request $request, $budgetID)
    {
        $budget = Budget::where('budgetID', $budgetID)
            ->where('studentID', session('student_id'))
            ->firstOrFail();
        $validatedData = $request->validate([
            'budgetName' => 'required|string|max:255',
            'budgetLimit' => 'required|numeric|min:0',
            'budgetDate' => 'required|date',
            'categoryID' => 'required|exists:categories,categoryID',
        ]);
        $budget->update($validatedData);
        return redirect('/budget')->with('success', 'Budget updated successfully!');
    }

    // Delete budget
    public function deleteBudget($budgetID)
    {
        $budget = Budget::where('budgetID', $budgetID)
            ->where('studentID', session('student_id'))
            ->firstOrFail();
        $budget->delete();
        return redirect('/budget')->with('success', 'Budget deleted successfully!');
    }
}
