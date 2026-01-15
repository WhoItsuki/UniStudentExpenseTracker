<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing admin records to use plain text passwords
        Admin::where('adminEmail', 'admin@university.edu')
            ->update(['password' => 'admin123']);

        Admin::where('adminEmail', 'sarah.johnson@university.edu')
            ->update(['password' => 'password123']);

        // Create new admin records if they don't exist (for fresh installations)
        Admin::firstOrCreate(
            ['adminEmail' => 'admin@university.edu'],
            [
                'adminFName' => 'John',
                'adminLName' => 'Doe',
                'password' => 'admin123',
            ]
        );

        Admin::firstOrCreate(
            ['adminEmail' => 'sarah.johnson@university.edu'],
            [
                'adminFName' => 'Sarah',
                'adminLName' => 'Johnson',
                'password' => 'password123',
            ]
        );
    }
}