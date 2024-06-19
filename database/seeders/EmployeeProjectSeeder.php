<?php

namespace Database\Seeders;

use App\Models\ProjectUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeProjects = [
            // موظفون في مشروع التدريب الطلابي
            ['project_id' => 1, 'user_id' => 4],
            ['project_id' => 1, 'user_id' => 5],
            ['project_id' => 1, 'user_id' => 6],
            ['project_id' => 1, 'user_id' => 7],
            ['project_id' => 1, 'user_id' => 8],

            // موظفون في مشروع توزيع المساعدات
            ['project_id' => 2, 'user_id' => 4],
            ['project_id' => 2, 'user_id' => 10],
        ];

        ProjectUser::insert($employeeProjects);
    }
}
