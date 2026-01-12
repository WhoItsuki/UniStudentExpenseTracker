<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class categoryController extends Controller
{
    // View all categories for logged-in student
    public function viewCategories(Request $request)
    {
        $categories = Category::where('studentID', session('student_id'))->get();
        return view('student.category', compact('categories'));
    }


    // Store new category
    public function addCategory(Request $request)
    {
        $validatedData = $request->validate([
            'categoryName' => 'required|string|max:255',
            'categoryType' => 'required|string|in:Expense,Income',
        ]);

        // Add studentID to the validated data
        $validatedData['studentID'] = session('student_id');

        Category::create($validatedData);

        return redirect('/viewCategories')->with('success', 'Category added successfully!');
    }

    // Edit category form
    public function editCategoryForm($categoryID)
    {
        $category = Category::where('categoryID', $categoryID)
            ->where('studentID', session('student_id'))
            ->firstOrFail();

        return view('student.category', compact('category'));
    }

    // Delete category
    public function deleteCategory($categoryID)
    {
        $category = Category::where('categoryID', $categoryID)
            ->where('studentID', session('student_id'))
            ->firstOrFail();

        // Check if category has related expenses
        if ($category->expenses()->count() > 0) {
            return redirect('/viewCategories')->withErrors(['delete' => 'Cannot delete category. It has related expenses.']);
        }

        // Check if category has related budgets
        if ($category->budgets()->count() > 0) {
            return redirect('/viewCategories')->withErrors(['delete' => 'Cannot delete category. It has related budgets.']);
        }

        // Check if category has related incomes
        if ($category->incomes()->count() > 0) {
            return redirect('/viewCategories')->withErrors(['delete' => 'Cannot delete category. It has related incomes.']);
        }

        $category->delete();

        return redirect('/viewCategories')->with('success', 'Category deleted successfully!');
    }
}
