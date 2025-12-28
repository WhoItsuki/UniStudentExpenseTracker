<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\studentController;
use App\Http\Controllers\adminController;

Route::get('/', function () {
    return view('student/loginStudent');
});

Route::get('/loginAdmin', function () {
    return view('admin/loginAdmin');
});

Route::get('/loginStudent', function () {
    return view('student/loginStudent');
})->name('student.login.get');

Route::post('/loginStudent', [studentController::class, 'login'])->name('student.login');

Route::get('/signupStudent', function () {
    return view('student/signup');
})->name('student.signup.get');

Route::post('/signupStudent', [studentController::class, 'signUp'])->name('student.signup');

Route::get('/dashboardStudent', function () {
    return view('student/dashboardStudent');
})->name('student.dashboard');