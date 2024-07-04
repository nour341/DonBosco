<?php

namespace App\Http\Controllers\SharedCenterLevel;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Project;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{ use GeneralTrait;

    public function getProjects()
    {
        $user = auth()->user();

        // حالة role_number
        $roleNumber = $user->role_number;
        $projects = [];
        if ($roleNumber == 1) {
            // إذا كان المستخدم local
            $projects = Project::where('local_coordinator_id',$user->id)->get();
        } elseif ($roleNumber == 2) {
            // إذا كان المستخدم financial
            $projects = Project::where('financial_management_id',$user->id)->get();
        } elseif ($roleNumber == 3) {
            // إذا كان المستخدم employ
            $projects = $user->projects()->get();
            $projects->each(function ($project) {
                unset($project->pivot); // Remove invoice_id to clean up the response

            });

        }
        foreach ($projects as $project) {
            $project->status = $project->getStatus();
            $project->local_coordinator = User::find($project->local_coordinator_id);
            $project->financial_management = User::find($project->financial_management_id);
            $project->supplier = User::find($project->supplier_id);
            // get employees in project
            $users = $project->users()->get();
            // Modify each user to include the role and center name
            $users->each(function ($user) {
                $user->user_role = 'Employ';
                if ($user->center)
                    $user->center_name = $user->center->name;
                else
                    $user->center_name = 'unknown center';
                unset($user->center); // Remove center to clean up the response
                unset($user->pivot); // Remove pivot to clean up the response

            });
            $project->employees = $users;

            //get Center in project
            $project->center = Center::find($project->center_id);

            //clean up the response
            unset($project->center_id); // Remove center_id to clean up the response
            unset($project->local_coordinator_id); // Remove local_coordinator_id to clean up the response
            unset($project->financial_management_id); // Remove financial_management_id to clean up the response
            unset($project->supplier_id); // Remove supplier_id to clean up the response
        }
        return $this->returnData('projects', $projects);
    }



    public function getBudget(Request $request)
    {
        // Validate the request input
        $validate = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id', // project_id is required and must exist in the projects table
        ]);
        // Check if validation fails
        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors()); // Return validation errors if any
        }

        // Retrieve the project ID from the request
        $id = $request->project_id;

        // Find the project with its related item budgets and items
        $project = Project::with('itemBudgets.item')->find($id);

        // Check if the project exists
        if (!$project) {
            return $this->returnError('Failed to get project. The project does not exist', 404); // Return an error if the project is not found
        }

        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is the local coordinator, financial management, or an employee of the project
        $isLocalCoordinator = $project->local_coordinator_id === $currentUser->id;
        $isFinancialManagement = $project->financial_management_id === $currentUser->id;
        $isEmployee = $project->users->contains($currentUser->id);

        if (!$isLocalCoordinator && !$isFinancialManagement && !$isEmployee) {
            return $this->returnError('You do not have permission to copy a folder for this project',  );
        }

        #################  end Test permission ###############################

        // Map the item budgets to include the item names
        $itemBudgets = $project->itemBudgets->map(function ($itemBudget) {  // Map = for loop
            return [
                'id' => $itemBudget->id, // Include the item name
                'item_name' => $itemBudget->item->name, // Include the item name
                'unite' => $itemBudget->unite, // Unit of measure
                'unit_price' => $itemBudget->unit_price, // Unit price
                'quantity' => $itemBudget->quantity, // Quantity
                'total_price' => $itemBudget->total_price, // Total price
                'balance' => $itemBudget->balance, // Balance
            ];
        });


        // Calculate the total budget and prepare the budget details
        $Budget = [
            'total' => $project->total, // Sum of all total prices
            'balance' => $project->balance, // Sum of all total balance
            'items' => $itemBudgets, // List of item budgets
        ];

        // Return the budget data successfully
        return $this->returnData('Budget', $Budget, 'Get Budget successfully');
    }
}
