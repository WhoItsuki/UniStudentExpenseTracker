<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

class incomeController extends Controller
{
    public function viewIncomes(Request $request)
    {
        $studentID = Session::get('student_id');

        // Build query with filters
        $query = Income::where('studentID', $studentID)->with('category');

        // Apply filters if provided
        if ($request->has('start_date') && $request->filled('start_date')) {
            $query->where('incomeDate', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->filled('end_date')) {
            $query->where('incomeDate', '<=', $request->end_date);
        }

        if ($request->has('categoryFilter') && $request->filled('categoryFilter')) {
            $query->where('categoryID', $request->categoryFilter);
        }

        // Get filtered incomes
        $incomes = $query->orderBy('incomeDate', 'desc')->get();

        // Calculate total income
        $totalIncomes = $incomes->sum('incomeAmount');

        // Get categories for filters
        $categories = Category::where('studentID', $studentID)
                             ->where('categoryType', 'Income')
                             ->get();

        return view('student.incomeStudent', compact('incomes', 'totalIncomes', 'categories'));
    }

    public function addIncome(Request $request)
    {
        $request->validate([
            'incomeName' => 'required|string|max:255',
            'incomeAmount' => 'required|numeric|min:0',
            'incomeDate' => 'required|date',
            'categoryID' => 'required|exists:categories,categoryID',
        ]);

        $studentID = Session::get('student_id');

        Income::create([
            'incomeName' => $request->incomeName,
            'incomeAmount' => $request->incomeAmount,
            'incomeDate' => $request->incomeDate,
            'categoryID' => $request->categoryID,
            'studentID' => $studentID,
        ]);

        return redirect()->back()->with('success', 'Income added successfully!');
    }

    public function deleteIncome($incomeID)
    {
        $studentID = Session::get('student_id');

        $income = Income::where('incomeID', $incomeID)
                       ->where('studentID', $studentID)
                       ->first();

        if (!$income) {
            return redirect()->back()->with('error', 'Income not found or you do not have permission to delete it.');
        }

        $income->delete();

        return redirect()->back()->with('success', 'Income deleted successfully!');
    }
}
