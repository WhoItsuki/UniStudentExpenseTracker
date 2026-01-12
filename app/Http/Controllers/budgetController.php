<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;

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
        
        // Calculate budget data
        $totalBudget = $this->calculateTotalBudgets($budgets);
        $usedBudget = $this->calculateUsedBudget($budgets);
        $categoryBudgets = $this->calculateCategoryBudgets($budgets, $categories);
        
        return view('student.budgetStudent', compact('budgets', 'categories', 'totalBudget', 'usedBudget', 'categoryBudgets'));
    }

    // Fetch budget data based on category and date filters (API endpoint)
    public function fetchBudgetByFilters(Request $request)
    {
        $categoryID = $request->input('categoryID');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Budget::where('studentID', session('student_id'))->with('category');

        // Apply date filter
        if ($startDate && $endDate) {
            $query->whereBetween('budgetDate', [$startDate, $endDate]);
        }

        // Apply category filter
        if ($categoryID) {
            $query->where('categoryID', $categoryID);
            $budgets = $query->get();
            
            // Get single category data
            $totalBudget = $budgets->sum('budgetLimit');
            $expenses = Expense::where('studentID', session('student_id'))
                ->where('categoryID', $categoryID)
                ->sum('expenseAmount');
            $usedBudget = min($expenses, $totalBudget);
            
            $category = Category::find($categoryID);
            
            return response()->json([
                'success' => true,
                'total' => (float)$totalBudget,
                'used' => (float)$usedBudget,
                'categoryID' => $categoryID,
                'categoryName' => $category ? $category->categoryName : '',
                'budgets' => $budgets
            ]);
        } else {
            // Get all budgets for date range
            $budgets = $query->get();
            $categories = Category::where('studentID', session('student_id'))->get();
            
            $totalBudget = $this->calculateTotalBudgets($budgets);
            $usedBudget = $this->calculateUsedBudget($budgets);
            $categoryBudgets = $this->calculateCategoryBudgets($budgets, $categories);
            
            return response()->json([
                'success' => true,
                'total' => (float)$totalBudget,
                'used' => (float)$usedBudget,
                'categoryBudgets' => $categoryBudgets,
                'budgets' => $budgets
            ]);
        }
    }

    // Calculate total budgets
    private function calculateTotalBudgets($budgets)
    {
        return $budgets->sum('budgetLimit');
    }

    // Calculate used budget (expenses within budget limits)
    private function calculateUsedBudget($budgets)
    {
        $usedTotal = 0;
        foreach ($budgets as $budget) {
            // Get expenses for this budget's category
            $expenses = Expense::where('studentID', session('student_id'))
                ->where('categoryID', $budget->categoryID)
                ->sum('expenseAmount');
            $usedTotal += min($expenses, $budget->budgetLimit);
        }
        return $usedTotal;
    }

    // Calculate category-wise budgets with expense data
    private function calculateCategoryBudgets($budgets, $categories)
    {
        $categoryBudgets = [];
        foreach ($categories as $category) {
            $categoryBudget = $budgets->where('categoryID', $category->categoryID)->first();
            if ($categoryBudget) {
                $expenses = Expense::where('studentID', session('student_id'))
                    ->where('categoryID', $category->categoryID)
                    ->sum('expenseAmount');
                
                $categoryBudgets[] = [
                    'categoryID' => $category->categoryID,
                    'categoryName' => $category->categoryName,
                    'total' => $categoryBudget->budgetLimit,
                    'used' => min($expenses, $categoryBudget->budgetLimit)
                ];
            }
        }
        return $categoryBudgets;
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
