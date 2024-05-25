<?php

namespace App\Http\Controllers\ProvincialCoordinator;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Models\ItemBudget;
use App\Models\Project;
use App\Services\ProjectService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProjectController extends Controller
{
    use GeneralTrait;

    public function CreateProject(Request $request)
    {

        $validate = Validator::make($request->all(),
            [

                'name' => 'required|unique:projects',
                'center_id' => 'required|exists:centers,id',
                'local_coordinator_id' => 'required|exists:users,id,role_number,1',
                'financial_management_id' => 'required|exists:users,id,role_number,2',
                'supplier_id' => 'required|exists:users,id,role_number,4',
                'short_description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);


        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }
        try {

            $project = Project::create([
                'name' => $request->name,
                'local_coordinator_id' => $request->local_coordinator_id,
                'financial_management_id' => $request->financial_management_id,
                'supplier_id' => $request->supplier_id,
                'short_description' => $request->short_description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'center_id' => $request->center_id,
            ]);

            return $this->returnSuccess("Project created successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the Project. Try again after some time');
        }
    }

    public function updateProject(Request $request)
    {

        $validate = Validator::make($request->all(),
            [

                'id' => 'required',
                'name' => 'required',
                'local_coordinator_id' => 'required|exists:users,id,role_number,1',
                'financial_management_id' => 'required|exists:users,id,role_number,2',
                'supplier_id' => 'required|exists:users,id,role_number,4',
                'short_description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'center_id' => 'required|exists:centers,id',
            ]);


        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());

        }
        // Check if the new Project name already exists
        $existingCenter = Project::where('name', $request->name)
            ->where('id', '!=', $request->id)
            ->first();

        if ($existingCenter) {
            return $this->returnError('The Project name already exists', 400);
        }
        try {
            $project = Project::find($request->id);
            if (!$project) {
                return $this->returnError('Failed to updated the project does not exist', 404);
            }

            $project->update([
                'name' => $request->name,
                'local_coordinator_id' => $request->local_coordinator_id,
                'financial_management_id' => $request->financial_management_id,
                'supplier_id' => $request->supplier_id,
                'short_description' => $request->short_description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'center_id' => $request->center_id,
            ]);

            return $this->returnSuccess("project updated Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the project. Try again after some time');
        }

    }

    public function changeStatusProject(Request $request)
    {

        $validate = Validator::make($request->all(),
            [
                'id' => 'required',
            ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());

        }
        try {
            $project = Project::find($request->id);
            if (!$project) {
                return $this->returnError('Failed to updated the project does not exist', 404);
            }
            $status = 1;
            if ($project->status == 1)
                $status = 0;

            $project->update([
                'status' => $status,
            ]);

            return $this->returnSuccess("project updated status Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the project. Try again after some time');
        }

    }

    public function getProjects()
    {

        try {
            $projects = Project::get();
            foreach ($projects as $project) {
                $project->status = $project->getStatus();
            }
            return $this->returnData('projects', $projects, 'Get the all projects successfully');

        } catch (\Throwable $th) {
            return $this->returnError('Failed Get the  projects. Try again after some time');
        }
    }

    public function getProject(Request $request)
    {
        $id = $request->id;
        $project = Project::find($id);
        $project->status = $project->getStatus();
        if (!$project) {
            return $this->returnError('Failed to get country. the Project does not exist', 404);
        }

        return $this->returnData('project', $project, 'Get the Projects successfully');
    }

    public function addItemBudget(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'item_id' => 'required|exists:items,id',
            'unite' => 'required|string',
            'unit_price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        try {
            // calculation  total_price
            $total_price = $request->quantity * $request->unit_price;

            $itemBudget = ItemBudget::create([
                'project_id' => $request->project_id,
                'item_id' => $request->item_id,
                'unite' => $request->unite,
                'unit_price' => $request->unit_price,
                'quantity' => $request->quantity,
                'total_price' => $total_price,
                'balance' => $total_price,
            ]);

            return $this->returnSuccess("ItemBudget created successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the ItemBudget. Try again after some time');
        }
    }

    public function updateItemBudget(Request $request)
    {

        $validate = Validator::make($request->all(), [

            'item_budget_id' => 'required|exists:item_budgets,id',
            'project_id' => 'required|exists:projects,id',
            'item_id' => 'required|exists:items,id',
            'unite' => 'required|string',
            'unit_price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        $itemBudget = ItemBudget::find($request->item_budget_id);
        if (!$itemBudget) {
            return $this->returnError('Failed to updated the itemBudget does not exist', 404);
        }

        try {
            // calculation  total_price
            $total_price = $request->quantity * $request->unit_price;

            $balance = $total_price - ($itemBudget->total_price - $itemBudget->balance);
            if ($balance < 0) {
                $balance = $balance * -1;
                return $this->returnError('The amount spent is greater than the budget specified for this item. Please increase the budget so that it is greater or equal ' . $balance, 404);
            }

            $itemBudget->update([
                'project_id' => $request->project_id,
                'item_id' => $request->item_id,
                'unite' => $request->unite,
                'unit_price' => $request->unit_price,
                'quantity' => $request->quantity,
                'total_price' => $total_price,
                'balance' => $total_price,
            ]);

            return $this->returnSuccess("Item  Budget update successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to update the Item Budget. Try again after some time');
        }
    }

    public function deleteItemBudget(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'item_budget_id' => 'required|exists:item_budgets,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        $itemBudget = ItemBudget::find($request->item_budget_id);
        if (!$itemBudget) {
            return $this->returnError('The ItemBudget does not exist', 404);
        }

        if ($itemBudget->total_price != $itemBudget->balance) {
            return $this->returnError('The amount spent is greater than the budget specified for this item. Please increase the budget so that it is greater or equal ', 404);
        }

        try {

            // Delete the ItemBudget
            $itemBudget->delete();
            return $this->returnSuccess('Item Budget deleted successfully');
        } catch (\Throwable $th) {
            return $this->returnError('Failed to delete the Item Budget. Try again later.');
        }

    }

    public function getBudgetProject(Request $request)
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

        // Map the item budgets to include the item names
        $itemBudgets = $project->itemBudgets->map(function ($itemBudget) {  // Map = for loop
            return [
                'item_name' => $itemBudget->item->name, // Include the item name
                'unite' => $itemBudget->unite, // Unit of measure
                'unit_price' => $itemBudget->unit_price, // Unit price
                'quantity' => $itemBudget->quantity, // Quantity
                'total_price' => $itemBudget->total_price, // Total price
                'balance' => $itemBudget->balance, // Balance
            ];});


        // Calculate the total budget and prepare the budget details
        $Budget = [
            'total' => $itemBudgets->sum('total_price'), // Sum of all total prices
            'items' => $itemBudgets, // List of item budgets
        ];

        // Return the budget data successfully
        return $this->returnData('Budget', $Budget, 'Get Budget successfully');
    }


}
