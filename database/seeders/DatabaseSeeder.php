<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            ProductSeeder::class,
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'account_credit' => 1000
        ]);
        $admin->assignRole('admin');

        // Create a test customer
        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'account_credit' => 500
        ]);
        $customer->assignRole('customer');
    }
}
