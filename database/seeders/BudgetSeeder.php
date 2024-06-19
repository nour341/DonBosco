<?php

namespace Database\Seeders;

use App\Models\ItemBudget;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // بيانات الميزانية للمشروع الأول
        $budget1 = [
            [
                'project_id' => 1,
                'item_id' => 1, // Food
                'unite' => 'kg',
                'unit_price' => 50,
                'quantity' => 10,
                'total_price' => 500,
                'balance' => 500,
            ],
            [
                'project_id' => 1,
                'item_id' => 2, // Equipment
                'unite' => 'piece',
                'unit_price' => 100,
                'quantity' => 20,
                'total_price' => 2000,
                'balance' => 2000,
            ],
            [
                'project_id' => 1,
                'item_id' => 3, // Salaries Wages
                'unite' => 'hour',
                'unit_price' => 25,
                'quantity' => 80,
                'total_price' => 2000,
                'balance' => 2000,
            ],
        ];

        // بيانات الميزانية للمشروع الثاني
        $budget2 = [
            [
                'project_id' => 2,
                'item_id' => 1, // Food
                'unite' => 'kg',
                'unit_price' => 30,
                'quantity' => 20,
                'total_price' => 600,
                'balance' => 600,
            ],
            [
                'project_id' => 2,
                'item_id' => 2, // Equipment
                'unite' => 'piece',
                'unit_price' => 150,
                'quantity' => 30,
                'total_price' => 4500,
                'balance' => 4500,
            ],
            [
                'project_id' => 2,
                'item_id' => 3, // Salaries Wages
                'unite' => 'hour',
                'unit_price' => 20,
                'quantity' => 150,
                'total_price' => 3000,
                'balance' => 3000,
            ],
        ];

        // إدخال بيانات الميزانية للمشاريع
        ItemBudget::insert($budget1);
        ItemBudget::insert($budget2);

        // تحديث رصيد المشروع
        $this->updateProjectBalance(1);
        $this->updateProjectBalance(2);
    }

    private function updateProjectBalance($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $totalBudget = ItemBudget::where('project_id', $projectId)->sum('total_price');
            $project->balance -= $totalBudget;
            $project->save();
        }
    }
}
