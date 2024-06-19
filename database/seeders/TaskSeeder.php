<?php

namespace Database\Seeders;

use App\Models\StatusTask;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = [
            // مهام مشروع التدريب الطلابي
            [
                'project_id' => 1,
                'user_id' => 4, // المعلم 1
                'description' => 'تدريس الرياضيات',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],
            [
                'project_id' => 1,
                'user_id' => 5, // المعلم 2
                'description' => 'تدريس العلوم',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],
            [
                'project_id' => 1,
                'user_id' => 6, // المعلم 3
                'description' => 'تدريس التاريخ',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],
            [
                'project_id' => 1,
                'user_id' => 7, // المعلم 4
                'description' => 'تدريس الجغرافيا',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],
            [
                'project_id' => 1,
                'user_id' => 8, // المعلم 5
                'description' => 'تدريس اللغة الإنجليزية',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],

            // مهام مشروع توزيع المساعدات
            [
                'project_id' => 2,
                'user_id' => 10, // موظف
                'description' => 'تنظيم توزيع الطعام',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],
            [
                'project_id' => 2,
                'user_id' => 10, // موظف
                'description' => 'تنظيم توزيع المعدات',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],
            [
                'project_id' => 2,
                'user_id' => 10, // موظف
                'description' => 'تنظيم توزيع الأجور',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],
            [
                'project_id' => 2,
                'user_id' => 2, // المنسق المحلي
                'description' => 'الإشراف على المتطوعين',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],
            [
                'project_id' => 2,
                'user_id' => 10, // موظف
                'description' => 'التواصل مع المستفيدين',
                'start_date' => '2024-07-01',
                'end_date' => '2024-07-31',
                'status_id' => 1,
            ],
        ];
        Task::insert($tasks);
    }
}
