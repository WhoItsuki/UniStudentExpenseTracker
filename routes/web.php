<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\adminController;

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

    Route::get('/expense', function(){
        return view('student/expenseStudent');
    });

    Route::get('/budget', function(){
        return view('student/budgetStudent');
    });

    Route::get('/income', function(){
        return view('student/incomeStudent');
    });

    Route::get('/profileStudent', [StudentController::class, 'profile']);
    Route::post('/profileStudent', [StudentController::class, 'updateProfile'])->name('student.updateProfile');

    Route::get('/category', function(){
        return view('student/category');
    });
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