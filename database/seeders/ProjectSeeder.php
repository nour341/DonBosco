<?php

namespace Database\Seeders;

use App\Models\ActionFileSystem;
use App\Models\Folder;
use App\Models\Project;
use App\Traits\GeneralTrait;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use GeneralTrait;
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
                "short_description"=> "is ndjd",
                "start_date"=> "2024-04-01",
                "end_date"=> "2024-04-04",
                "center_id"=> 1
            ]
        ];
        Project::insert($projects);

        $folders =  [
            [
                "id"=> 1,
                "name"=> "project 1",
                "project_id"=> 1,
                "father_folder_id"=> null,

            ],
            [
                "id"=> 2,
                "name"=> "project 2",
                "project_id"=> 2,
                "father_folder_id"=> null,

            ]
        ];

        $Actions =  [
            [
                "id"=> 1,
                "user_id"=> 1,
                "short_description"=>  'project 1 folder created',

            ],
            [
                "id"=> 2,
                "user_id"=> 1,
                "short_description"=> 'project 2 folder created',
            ]
        ];

        $this->createFolder('projectFolder/project 1');
        $this->createFolder('projectFolder/project 2');

        Folder::insert($folders);
        ActionFileSystem::insert($Actions);
    }
}
