<?php

namespace App\Http\Controllers\LocalCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Invoice;
use App\Models\ItemBudget;
use App\Models\Project;
use App\Models\Task;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{   use GeneralTrait;


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
        $isLocalCoordinator = $project->local_coordinator_id === $currentUser->id;
//        $isFinancialManagement = $project->financial_management_id === $currentUser->id;

        if (!$isLocalCoordinator) {
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
        $isLocalCoordinator = $project->local_coordinator_id === $currentUser->id;
//        $isFinancialManagement = $project->financial_management_id === $currentUser->id;

        if (!$isLocalCoordinator) {
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


    public function getInvoiceMonthlyExpensesConfirmedByProjectID(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        // Retrieve the project and determine the start date
        $project = Project::with('tasks')->find($request->project_id);

        #################  Test permission destination ###############################
        if (!$project) {
            return $this->returnError('Project not found', 404);
        }
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Check if the user is the local coordinator, financial management, or an employee of the project
        $isLocalCoordinator = $project->local_coordinator_id === $currentUser->id;
//        $isFinancialManagement = $project->financial_management_id === $currentUser->id;

        if (!$isLocalCoordinator) {
            return $this->returnError('You do not have permission to confirm Invoice for this project');
        }
        #################  end Test permission ###############################

        $startDate = Carbon::parse($project->created_at)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Retrieve the task IDs for the project
        $tasks = $project->tasks()->pluck('id');

        // Retrieve the confirmed invoices within the date range for the specified project
        $invoices = Invoice::with(['invoice_items.itemBudget.item', 'image'])
            ->whereIn('task_id', $tasks)
            ->where('status', true)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Format the response to include the item name and update the status
        $invoices->each(function($invoice) {
            $invoice->status = 'confirmed';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });

            // Get the file path for the invoice image
            $father_File_path = '';
            $father_File = Folder::find($invoice->image->folder_id);
            if ($father_File) {
                $father_File_path = $father_File->getPath();
            }
            $file_path = 'projectFolder/' . $father_File_path . '/' . $invoice->image->name;
            $invoice->image->path = $file_path;
            unset($invoice->image->created_at);
            unset($invoice->image->updated_at);
            unset($invoice->image->folder_id);
            unset($invoice->image->description);
        });

        // Generate a list of months between start date and end date
        $period = CarbonPeriod::create($startDate->startOfMonth(), '1 month', $endDate->endOfMonth());
        $monthlyExpenses = [];

        foreach ($period as $date) {
            $monthlyExpenses[$date->format('Y-m')] = 0;
        }

        // Aggregate expenses by month
        $invoicesGroupedByMonth = $invoices->groupBy(function($invoice) {
            return Carbon::parse($invoice->created_at)->format('Y-m');
        });

        foreach ($invoicesGroupedByMonth as $month => $invoicesInMonth) {
            $monthlyExpenses[$month] = $invoicesInMonth->sum(function($invoice) {
                return $invoice->invoice_items->sum('amount');
            });
        }

        return $this->returnData( 'monthly_expenses' , $monthlyExpenses);
    }


}
