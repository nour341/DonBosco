<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            CenterSeeder::class,
            UserSeeder::class,
            ProjectSeeder::class,
            ItemSeeder::class,
            BudgetSeeder::class,
            StatusTasksSeeder::class,
            EmployeeProjectSeeder::class,
            TaskSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
