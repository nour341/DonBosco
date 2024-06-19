<?php

namespace App\Http\Controllers\FinancialManagement;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ItemBudget;
use App\Models\Project;
use App\Models\Task;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    use GeneralTrait;


    public function confirmInvoice(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());

        }



        $itemQuantities = [];

        $invoice = Invoice::with('invoice_items')->find($request->invoice_id);



        #################  Test permission destination ###############################
        $task = Task::find($invoice->task_id);
        if (!$task){
            return $this->returnError('Task not found', 404);

        }
        // Retrieve the project
        $project = Project::find($task->project_id);
        if (!$project) {
            return $this->returnError('Project not found', 404);
        }
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is the local coordinator, financial management, or an employee of the project
        $isFinancialManagement = $project->financial_management_id === $currentUser->id;

        if (!$isFinancialManagement) {
            return $this->returnError('You do not have permission to confirm Invoice for this project');
        }

        if($invoice->status){

            return $this->returnError('Invoice is already confirmed');
        }

        #################  end Test permission ###############################

        $invoice->status = true;
        $invoice_items = $invoice->invoice_items;
        foreach ($invoice_items as $item) {
            $item->total_price_quantity;


            // تجميع الكميات حسب itemBudget_id
            if (!isset($itemQuantities[$item->itemBudget_id])) {
                $itemQuantities[$item->itemBudget_id] = 0;
            }

            $total_price = $item->total_price_quantity;
            $itemQuantities[$item['itemBudget_id']] += $total_price;
        }
        // التحقق من تجاوز الرصيد
        foreach ($itemQuantities as $itemBudget_id => $totalQuantity) {
            $itemBudget = ItemBudget::with('item')->find($itemBudget_id);
            $item = $itemBudget->item->name;
            if ($totalQuantity > $itemBudget->balance) {
                return $this->returnError("The total price of items with itemBudget {$item} exceeds the available balance");
            }
        }
        // حذف من balance
        foreach ($itemQuantities as $itemBudget_id => $totalQuantity) {
            $itemBudget = ItemBudget::with('item')->find($itemBudget_id);
            $itemBudget->balance -= $totalQuantity;
            $itemBudget->save();
        }
        $invoice->save();

        return $this->returnSuccess("The invoice has been confirmed successfully");
    }


    public function cancelInvoice(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());

        }
        $invoice = Invoice::with('invoice_items')->find($request->invoice_id);

        #################  Test permission destination ###############################
        $task = Task::find($invoice->task_id);
        if (!$task){
            return $this->returnError('Task not found', 404);

        }
        // Retrieve the project
        $project = Project::find($task->project_id);
        if (!$project) {
            return $this->returnError('Project not found', 404);
        }
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is the local coordinator, financial management, or an employee of the project
        $isFinancialManagement = $project->financial_management_id === $currentUser->id;

        if (!$isFinancialManagement) {
            return $this->returnError('You do not have permission to confirm Invoice for this project');
        }


        #################  end Test permission ###############################

        if ($invoice->status) {
            return $this->returnError('The invoice has already been confirmed and the amount has been disbursed. It cannot be canceled.');

        }
        $invoice->status = false;
        $invoice->save();
        return $this->returnSuccess("Invoice has been cancelled");

    }



}
