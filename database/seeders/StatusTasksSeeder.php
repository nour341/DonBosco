<?php

namespace Database\Seeders;

use App\Models\StatusTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusTasks = [
            [
                "id" => 1,
                "name" => "pending",
                "short_description" => "Task is defined but work has not yet started",
            ],
            [
                "id" => 2,
                "name" => "in_progress",
                "short_description" => "Work on the task has started",
            ],
            [
                "id" => 3,
                "name" => "on_hold",
                "short_description" => "Work on the task is temporarily paused",
            ],
            [
                "id" => 4,
                "name" => "under_review",
                "short_description" => "Task is completed and under review",
            ],
            [
                "id" => 5,
                "name" => "completed",
                "short_description" => "Task is completed and approved",
            ],
            [
                "id" => 6,
                "name" => "closed",
                "short_description" => "Task is completed and no further action is needed",
            ],
            [
                "id" => 7,
                "name" => "cancelled",
                "short_description" => "Task is cancelled and will not be completed",
            ]
        ];
        StatusTask::insert($statusTasks);
    }
}
