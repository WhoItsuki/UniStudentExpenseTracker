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
        Schema::table('budgets', function (Blueprint $table) {
            $table->string('studentID')->after('categoryID');
        });

        // Populate studentID from categories for existing records
        DB::statement('UPDATE budgets SET studentID = (SELECT studentID FROM categories WHERE categories.categoryID = budgets.categoryID)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign(['studentID']);
            $table->dropColumn('studentID');
        });
    }
};
