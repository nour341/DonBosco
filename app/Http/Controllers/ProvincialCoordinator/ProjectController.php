<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Models\Budget;
use App\Models\Item;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function CreateProject(CreateProjectRequest $request)
    {
        try {
            $project = Project::create([
                'name' => $request->name,
                'Total' => $request->Total,
                'description'=> $request->description,
                'start_date'=>  $request->start_date,
                'end_date'=>   $request->end_date,
                'center_id'=>  $request->center_id,
                'LocalName'=>  $request->LocalName,
                'FinancialName'=>   $request->FinancialName,
                'FinName'=>  $request->FinName,
            ]);
         return ResponseHelper::success('project created successfully');

        } catch (\Throwable $th) {
            return ResponseHelper::error('Error');

        }
    }

    public function getProjects(){

        try {
            $projects=Project::query()->get()->toArray();

    return ResponseHelper::success($projects);
        }
        catch (\Throwable $th) {
            return ResponseHelper::error('Error');

        }

    }

    public function getProject($id){
        $Project=Project::find($id);
        if(!$Project){
            return ResponseHelper::error('Error');
        }
        return ResponseHelper::success($Project);
    }

    // public function AddBudget(Request $request) {
    //     $validatedData = $request->validate([
    //         'balance' => 'required|numeric',
    //         'total' => 'required|numeric',
    //         'project_id' => 'required|:projects_id',
    //         'item_id' => 'required|exists:items_id',
    //     ]);

    //     $projects = Project::select('start_date', 'end_date')->get();

    //     foreach ($projects as $project) {
    //         $startDate = $project->start_date;
    //         $endDate = $project->end_date;
    //     }
    //     $items = Item::find($validatedData['item_id']);
    //     foreach ($items as $item) {

    //         $budget = new Budget();
    //         $budget->item_id = $item->id;
    //         $budget->number = null;
    //         $budget->name = null;
    //         $budget->unite = null;
    //         $budget->unit_price = null;
    //         $budget->quantity = null;
    //         $budget->total_price = null;
    //         $budget->save();
    //     }

    //     $budget = new Budget();
    //     $budget->balance = $validatedData['balance'];
    //     $budget->balance = $validatedData['total'];
    //     $budget->start_date = $project->start_date;
    //     $budget->end_date = $project->end_date;
    //     $budget->save();

    //     return response()->json(['message' => 'Budget added successfully', 'budget' => $budget], 200);
    // }


    // public function getBudget(Request $req)
    // {
    //     $project=Project::find($req->project_id);
    //     if ($project) {

    //         $Budgets = $project->budget()->get();

    //         $arr = [
    //             'message' => $Budgets,
    //             'status' => 200
    //         ];
    //     } else {
    //         $arr = [
    //             'message' => 'Project not found',
    //             'status' => 404
    //         ];
    //     }

    //     return response($arr, $arr['status']);
    // }



   // public function getProjectsService(){

   //     $result=app(ProjectService::class)-> getProjects();
  //      return $result;

   //}
   }
