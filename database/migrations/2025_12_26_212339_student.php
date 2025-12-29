<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void//Create the student table
    {
        Schema::create('student', function (Blueprint $table) {
            $table->id('studentID');
            $table->string('studentFname');
            $table->string('studentLname');
            $table->string('studentFaculty');
            $table->string('programme');
            $table->string('studentEmail')->unique();
            $table->string('password');
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};
