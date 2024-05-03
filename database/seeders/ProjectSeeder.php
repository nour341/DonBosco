<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects =  [
        [
            "id"=> 1,
            "name"=> "project 1",
            "local_coordinator_id"=> 2,
            "financial_management_id"=> 3,
            "supplier_id"=> 5,
            "short_description"=> "is project 1",
            "start_date"=> "2024-04-01",
            "end_date"=> "2024-04-04",
            "center_id"=> 1
        ],
        [
            "id"=> 2,
            "name"=> "project2",
            "local_coordinator_id"=> 2,
            "financial_management_id"=> 3,
            "supplier_id"=> 5,
            "short_description"=> "is project 2",
            "start_date"=> "2024-04-01",
            "end_date"=> "2024-04-04",
            "center_id"=> 1
        ]
    ];
        Project::insert($projects);
    }
}
