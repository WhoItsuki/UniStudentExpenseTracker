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
});
Route::get('/signupStudent', function () {
    return view('student/signup');
});




//Routes student navigation bar
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

Route::get('/profileStudent', function(){
    return view('student/profileStudent');
});
//

