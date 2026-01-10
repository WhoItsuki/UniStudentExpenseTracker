<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate studentID from categories for existing records
        DB::statement('UPDATE expenses SET studentID = (SELECT studentID FROM categories WHERE categories.categoryID = expenses.categoryID) WHERE studentID IS NULL OR studentID = ""');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['studentID']);
            $table->dropColumn('studentID');
        });
    }
};
