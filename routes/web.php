<?php

use Illuminate\Support\Facades\Route;

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