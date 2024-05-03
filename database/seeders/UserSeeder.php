<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users= [
            [
                'id' => 1,
                'name' => 'Provincial',
                'email' => 'provincial@gmail.com',
                'password' => Hash::make(12345678),
                'role_number' => 0,
                'gender' => 'male',
            ],
            [
                'id' => 2,
                'name' => 'Local',
                'email' => 'local@gmail.com',
                'password' => Hash::make(12345678),
                'role_number' => 1,
                'gender' => 'male',
            ],
            [
                'id' => 3,
                'name' => 'FinancialManagement',
                'email' => 'financial@gmail.com',
                'password' => Hash::make(12345678),
                'role_number' => 2,
                'gender' => 'male',
            ],
            [
                'id' => 4,
                'name' => 'Employ',
                'email' => 'employ@gmail.com',
                'password' => Hash::make(12345678),
                'role_number' => 3,
                'gender' => 'male',
            ],
            [
                'id' => 5,
                'name' => 'Supplier',
                'email' => 'supplier@gmail.com',
                'password' => Hash::make(12345678),
                'role_number' => 4,
                'gender' => 'male',
            ],

        ];

        User::insert($users);
    }
}
