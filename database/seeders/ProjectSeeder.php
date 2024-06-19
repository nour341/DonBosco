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
        $projects = [
            [
                "id" => 1,
                "name" => "Student training project",
                "local_coordinator_id" => 2,
                "financial_management_id" => 3,
                "supplier_id" => 9,
                "short_description" => "تقديم دورات تدريبية للطلاب",
                "start_date" => "2024-07-01",
                "end_date" => "2024-12-31",
                "center_id" => 1,
                "total" => 20000,
                "balance" => 20000,
            ],
            [
                "id" => 2,
                "name" => "Aid distribution project",
                "local_coordinator_id" => 2,
                "financial_management_id" => 3,
                "supplier_id" => 9,
                "short_description" => "توزيع مساعدات للعائلات المحتاجة",
                "start_date" => "2024-07-01",
                "end_date" => "2024-12-31",
                "center_id" => 1,
                "total" => 15000,
                "balance" => 15000,
            ]
        ];

        Project::insert($projects);

        $folders =  [
            [
                "id"=> 1,
                "name"=> "Student training project",
                "project_id"=> 1,
                "father_folder_id"=> null,

            ],
            [
                "id"=> 2,
                "name"=> "Aid distribution project",
                "project_id"=> 2,
                "father_folder_id"=> null,

            ]
        ];

        $Actions =  [
            [
                "id"=> 1,
                "user_id"=> 1,
                "short_description"=>  'Student training project folder created',

            ],
            [
                "id"=> 2,
                "user_id"=> 1,
                "short_description"=> 'Aid distribution project folder created',
            ]
        ];

        $this->createFolder('public/projectFolder/Student training project');
        $this->createFolder('public/projectFolder/Aid distribution project');

        Folder::insert($folders);
        ActionFileSystem::insert($Actions);
    }
}
