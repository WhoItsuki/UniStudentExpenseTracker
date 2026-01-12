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
    Route::get('/dashboardStudent', function () {
        return view('student/dashboardStudent');
    });



    

    Route::get('/income', [incomeController::class, 'viewIncomes']);
    Route::get('/viewIncomes', [incomeController::class, 'viewIncomes']);

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

});


//Routes admin navigation bar
Route::get('/dashboardAdmin', function(){
    return view('admin/dashboardAdmin');
});
Route::get('/profileAdmin', function(){
    return view('admin/profileAdmin');
});
Route::get('/studentAdmin', [adminController::class, 'studentList']);
Route::get('/admin/student/{studentID}/details', [adminController::class, 'getStudentDetails']);

//Student function
Route::post('studentSignup', [StudentController::class, 'signUp']);
Route::post('studentLogin', [StudentController::class, 'login']);
Route::post('studentLogout', [StudentController::class, 'logout'])->name('student.logout');