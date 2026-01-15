<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;
use App\Http\Controllers\budgetController;
use App\Http\Controllers\expenseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\incomeController;

Route::get('/', function () {
    return view('student/loginStudent');
});

Route::get('/loginAdmin', function () {
    return view('admin/loginAdmin');
});


Route::get('/loginStudent', function () {
    return view('student/loginStudent');
});
Route::get('/signupStudent', function () {
    return view('student/signup');
});




//Routes student navigation bar (protected by student authentication)
Route::middleware('student.auth')->group(function () {
    Route::get('/dashboardStudent', [StudentController::class, 'dashboard']);




    Route::get('/profileStudent', [StudentController::class, 'profile']);
    Route::post('/profileStudent', [StudentController::class, 'updateProfile'])->name('student.updateProfile');

    Route::get('/category', [categoryController::class, 'viewCategories']);

    //Routes for category
    Route::get('/viewCategories', [categoryController::class, 'viewCategories']);
    Route::post('/addCategory', [categoryController::class, 'addCategory']);
    Route::delete('/category/{categoryID}', [categoryController::class, 'deleteCategory']);
    Route::put('/category/{categoryID}', [categoryController::class, 'updateCategory']);

    //Routes for expense
    Route::get('/expense', [expenseController::class, 'viewExpenses']);
    Route::post('/addExpense', [expenseController::class, 'addExpense']);
    Route::put('/expense/{expenseID}', [expenseController::class, 'updateExpense']);
    Route::delete('/expense/{expenseID}', [expenseController::class, 'deleteExpense']);
    Route::get('/viewExpenses', [expenseController::class, 'viewExpenses']);

    //Routes for budget
    Route::get('/budget', [budgetController::class, 'viewBudgets']);
    Route::get('/api/budget/fetch-filters', [budgetController::class, 'fetchBudgetByFilters']);
    Route::post('/addBudget', [budgetController::class, 'addBudget']);
    Route::put('/budget/{budgetID}', [budgetController::class, 'updateBudget']);
    Route::delete('/budget/{budgetID}', [budgetController::class, 'deleteBudget']);
    Route::get('/viewBudget', [budgetController::class, 'viewBudgets']);

    //Routes for income
    Route::post('/addIncome', [incomeController::class, 'addIncome']);
    Route::delete('/income/{incomeID}', [incomeController::class, 'deleteIncome']);
    Route::get('/income', [incomeController::class, 'viewIncomes']);
    Route::get('/viewIncomes', [incomeController::class, 'viewIncomes']);

    // API Routes for filters
    Route::get('/api/expenses-by-category/{period}', [StudentController::class, 'getExpensesByCategory']);
    Route::get('/api/budget-vs-expense/{period}', [StudentController::class, 'getBudgetVsExpense']);

});


//Admin authentication routes
Route::post('/adminLogin', [adminController::class, 'login']);
Route::post('/adminLogout', [adminController::class, 'logout'])->name('admin.logout');

//Routes admin navigation bar (protected by admin authentication)
Route::middleware('admin.auth')->group(function () {
    Route::get('/dashboardAdmin', [adminController::class, 'dashboard']);
    Route::get('/profileAdmin', [adminController::class, 'profile']);
    Route::post('/profileAdmin', [adminController::class, 'updateProfile'])->name('admin.updateProfile');
    Route::get('/studentAdmin', [adminController::class, 'studentList']);
    Route::get('/student/{studentID}', [adminController::class, 'studentDetail'])->name('admin.student.detail');
    
});

//Student function
Route::post('studentSignup', [StudentController::class, 'signUp']);
Route::post('studentLogin', [StudentController::class, 'login']);
Route::post('studentLogout', [StudentController::class, 'logout'])->name('student.logout');