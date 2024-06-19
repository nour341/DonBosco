<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Models\ItemBudget;
use App\Models\Project;
use App\Models\User;
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
                'total' => 'required|numeric',
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
                'total' => $request->total,
                'balance' => $request->total,
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
                'total' => 'required|numeric',

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
            $expense = ($project->total - $project->balance);
            if ($expense > $request->total) {
                return $this->returnError('Failed to updated  because the new budget is less than the expense', 404);
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
                'total' => $request->total,
                'balance' => $request->total - $expense,
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
                $project->local_coordinator = User::find($project->local_coordinator_id);
                $project->financial_management = User::find($project->financial_management_id);
                $project->supplier = User::find($project->supplier_id);
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
        $project->local_coordinator = User::find($project->local_coordinator_id);
        $project->financial_management = User::find($project->financial_management_id);
        $project->supplier = User::find($project->supplier_id);
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

        // calculation  total_price
        $total_price = $request->quantity * $request->unit_price;

        // استرجاع مجموع الميزانية المخصصة للمشروع
        $project = Project::find($request->project_id);
        if ($total_price > $project->balance) {
            return $this->returnError('The total price of items exceeds the project budget');
        }
        try {


            $itemBudget = ItemBudget::create([
                'project_id' => $request->project_id,
                'item_id' => $request->item_id,
                'unite' => $request->unite,
                'unit_price' => $request->unit_price,
                'quantity' => $request->quantity,
                'total_price' => $total_price,
                'balance' => $total_price,
            ]);
            // تحديث رصيد المشروع
            $project->balance -= $total_price;

            $project->save(); // حفظ التغييرات في رصيد المشروع


            return $this->returnSuccess("ItemBudget created successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the ItemBudget. Try again after some time');
        }
    }


    public function addBudget(Request $request)
    {
        $items = $request->all(); // الحصول على كافة العناصر من الطلب

        $totalSum = 0; // متغير لتخزين مجموع الأسعار

        // تحقق من البيانات لكل عنصر
        foreach ($items as $item) {
            $validate = Validator::make($item, [
                'project_id' => 'required|exists:projects,id',
                'item_id' => 'required|exists:items,id',
                'unite' => 'required|string',
                'unit_price' => 'required|numeric',
                'quantity' => 'required|integer',
            ]);

            if ($validate->fails()) {
                return $this->returnErrorValidate($validate->errors());
            }

            // حساب total_price لكل عنصر وجمعه
            $total_price = $item['quantity'] * $item['unit_price'];
            $totalSum += $total_price;
        }

        // استرجاع مجموع الميزانية المخصصة للمشروع
        $project = Project::find($items[0]['project_id']);
        if ($totalSum > $project->balance) {
            return $this->returnError('The total price of items exceeds the project budget');
        }

        try {
            foreach ($items as $item) {
                $total_price = $item['quantity'] * $item['unit_price'];

                $itemBudget = ItemBudget::create([
                    'project_id' => $item['project_id'],
                    'item_id' => $item['item_id'],
                    'unite' => $item['unite'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $total_price,
                    'balance' => $total_price,
                ]);

                // تحديث رصيد المشروع
                $project->balance -= $total_price;
            }

            $project->save(); // حفظ التغييرات في رصيد المشروع

            return $this->returnSuccess("Budget created successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the Budgets. Try again after some time');
        }
    }

    public function updateBudget(Request $request)
    {
        $items = $request->all(); // الحصول على كافة العناصر من الطلب
        $project = null; // سيتم تعيينه بمجرد التحقق من العنصر الأول
        $totalSum = 0; // متغير لتخزين مجموع الأسعار
        $balanceSum = 0; // متغير لتخزين مجموع
        foreach ($items as $item) {
            $validate = Validator::make($item, [
                'project_id' => 'required|exists:projects,id',
                'item_id' => 'required|exists:items,id',
                'unite' => 'required|string',
                'unit_price' => 'required|numeric',
                'quantity' => 'required|integer',
                'item_budget_id' => 'sometimes|exists:item_budgets,id' // اختياري، وجوده يعني تحديث
            ]);

            if ($validate->fails()) {
                return $this->returnErrorValidate($validate->errors());
            }

            if (!$project) {
                $project = Project::find($item['project_id']);
            }

            $total_price = $item['quantity'] * $item['unit_price'];
            $totalSum += $total_price;
            if (isset($item['item_budget_id'])) {
                // تحديث عنصر موجود
                $itemBudget = ItemBudget::find($item['item_budget_id']);
                $expense = ($itemBudget->total_price - $itemBudget->balance);
                if ($expense > $total_price) {
                    return $this->returnError('Failed to updated  because the new budget is less than the expense', 404);
                }
// إعادة إضافة القديم قبل خصم الجديد
                $balanceSum += $itemBudget->total_price;
            }
        }

        // استرجاع مجموع الميزانية المخصصة للمشروع
        if ($totalSum > $project->balance + $balanceSum) {
            return $this->returnError('The total price of items exceeds the project budget');
        }


        try {
            foreach ($items as $item) {

                $total_price = $item['quantity'] * $item['unit_price'];
                if (isset($item['item_budget_id'])) {
                    // تحديث عنصر موجود
                    $itemBudget = ItemBudget::find($item['item_budget_id']);
                    $expense = ($itemBudget->total_price - $itemBudget->balance);
                    $project->balance += $itemBudget->total_price; // إعادة إضافة القديم قبل خصم الجديد
                    $itemBudget->update([
                        'unite' => $item['unite'],
                        'unit_price' => $item['unit_price'],
                        'quantity' => $item['quantity'],
                        'total_price' => $total_price,
                        'balance' => $total_price - $expense,
                    ]);
                } else {
                    // إضافة عنصر جديد
                    $itemBudget = ItemBudget::create([
                        'project_id' => $item['project_id'],
                        'item_id' => $item['item_id'],
                        'unite' => $item['unite'],
                        'unit_price' => $item['unit_price'],
                        'quantity' => $item['quantity'],
                        'total_price' => $total_price,
                        'balance' => $total_price,
                    ]);
                }

                // تحديث الرصيد الجديد
                $project->balance -= $total_price;
            }

            $project->save(); // حفظ التغييرات في رصيد المشروع

            return $this->returnSuccess("Budget updated successfully");



        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the Budgets. Try again after some time');
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


            // استرجاع مجموع الميزانية المخصصة للمشروع
            $project = Project::find($request->project_id);
            if ($total_price > $project->balance + $itemBudget->total_price  ) {
                return $this->returnError('The total price of items exceeds the project budget');
            }
            $project->balance += $itemBudget->total_price; // إعادة إضافة القديم قبل خصم الجديد

            $itemBudget->update([
                'project_id' => $request->project_id,
                'item_id' => $request->item_id,
                'unite' => $request->unite,
                'unit_price' => $request->unit_price,
                'quantity' => $request->quantity,
                'total_price' => $total_price,
                'balance' => $balance,
            ]);

            $project->balance -= $total_price;

            $project->save(); // حفظ التغييرات في رصيد المشروع


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


        // استرجاع مجموع الميزانية المخصصة للمشروع
        $project = Project::find($itemBudget->project_id);

        $project->balance += $itemBudget->total_price; // إعادة إضافة القديم قبل خصم الجديد
        try {

            // Delete the ItemBudget
            $itemBudget->delete();

            $project->save(); // حفظ التغييرات في رصيد المشروع

            return $this->returnSuccess('Item Budget deleted successfully');
        } catch (\Throwable $th) {
            return $this->returnError('Failed to delete the Item Budget. Try again later.');
        }

    }

    public function deleteListBudget(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'item_budget_ids' => 'required|array',
            'item_budget_ids.*' => 'exists:item_budgets,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        $itemBudgets = ItemBudget::findMany($request->item_budget_ids); // استرجاع جميع العناصر المطلوب حذفها

        // معالجة كل عنصر
        foreach ($itemBudgets as $itemBudget) {
            if ($itemBudget->total_price != $itemBudget->balance) {
                continue;
            }

            $project = Project::find($itemBudget->project_id);
            $project->balance += $itemBudget->total_price; // إعادة إضافة القديم قبل خصم الجديد

            try {
                // حذف العنصر
                $itemBudget->delete();
//                $project->save(); // حفظ التغييرات في رصيد المشروع
            } catch (\Throwable $th) {
                return $this->returnError('Failed to delete an item budget. Try again later.');
            }
        }

        return $this->returnSuccess('All have been deleted except those from which credit has been consumed');
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
