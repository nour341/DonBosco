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

        $users = [
            [
                'id' => 1,
                'name' => 'المسؤول الإقليمي',
                'email' => 'provincial@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 0,
                'gender' => 'male',
                'center_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'المنسق المحلي',
                'email' => 'local@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 1,
                'gender' => 'male',
                'center_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'إدارة مالية',
                'email' => 'financial@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 2,
                'gender' => 'male',
                'center_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'المعلم 1',
                'email' => 'teacher1@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 3,
                'gender' => 'male',
                'center_id' => 1,
            ],
            [
                'id' => 5,
                'name' => 'المعلم 2',
                'email' => 'teacher2@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 3,
                'gender' => 'female',
                'center_id' => 1,
            ],
            [
                'id' => 6,
                'name' => 'المعلم 3',
                'email' => 'teacher3@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 3,
                'gender' => 'female',
                'center_id' => 1,
            ],
            [
                'id' => 7,
                'name' => 'المعلم 4',
                'email' => 'teacher4@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 3,
                'gender' => 'male',
                'center_id' => 1,
            ],
            [
                'id' => 8,
                'name' => 'المعلم 5',
                'email' => 'teacher5@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 3,
                'gender' => 'female',
                'center_id' => 1,
            ],
            [
                'id' => 9,
                'name' => 'المورد',
                'email' => 'supplier@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 4,
                'gender' => 'male',
                'center_id' => null,
            ],
            [
                'id' => 10,
                'name' => 'مندوب خارجي',
                'email' => 'soso@gmail.com',
                'password' => Hash::make('12345678'),
                'role_number' => 3,
                'gender' => 'male',
                'center_id' => 1,
            ],
        ];

        User::insert($users);
    }
}
