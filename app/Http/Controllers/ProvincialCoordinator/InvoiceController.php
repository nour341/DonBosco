<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ItemBudget;
use App\Models\Project;
use App\Models\Task;
use App\Traits\GeneralTrait;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    use GeneralTrait;

    public function addInvoice(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'number' => 'required|numeric',
            'from' => 'required',
            'to' => 'required',
            'items' => 'required',
            'image_path' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }
        $invoiceData = $request->only(['task_id', 'number', 'from', 'to', 'status']);
        $invoiceItems = $request->get('items'); // الحصول على عناصر الفاتورة من الطلب
        $invoiceItems = json_decode($invoiceItems, true);
        $totalInvoiceSum = 0; // متغير لتخزين مجموع الأسعار في الفاتورة
        // مصفوفة لتجميع الكميات حسب itemBudget_id
        $itemQuantities = [];

        foreach ($invoiceItems as $item) {
            $validate = Validator::make($item, [
                'name' => 'required',
                'unite' => 'required',
                'itemBudget_id' => 'required|exists:item_budgets,id',
                'unit_price' => 'required|numeric',
                'quantity' => 'required|integer',
            ]);

            if ($validate->fails()) {
                return $this->returnErrorValidate($validate->errors());
            }


            // تجميع الكميات حسب itemBudget_id
            if (!isset($itemQuantities[$item['itemBudget_id']])) {
                $itemQuantities[$item['itemBudget_id']] = 0;
            }

            $total_price = $item['quantity'] * $item['unit_price'];
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
        ## start add image ######
        $task = Task::find($request->task_id);
        $project = Project::find($task->project_id);
        $father_folder = Folder::where('project_id', $project->id)->where('father_folder_id', null)->first();
        $father_folder_id = $father_folder->id;
        // get path father File
        $father_File = Folder::where('name', 'Invoices')
            ->where('father_folder_id', $father_folder_id)
            ->first();


        try {

            if ($father_File) {
                $father_File_path = $father_File->getPath();
            } else {

                $father_File = Folder::create([
                    'name' => 'Invoices',
                    'father_folder_id' => $father_folder_id,
                    'project_id' => $project->id,
                ]);
                $father_folder_path = $father_folder->getPath();
                // Check if the new Folder name already exists in public
                $existingFolder = $this->createFolder('projectFolder/' . $father_folder_path . '/' . $father_File->name);
                if (!$existingFolder) {
                    return $this->returnError('The Folder name already exists', 400);
                }
                $father_File_path = $father_File->getPath();
            }
            $file_path = $this->saveFile($request->image_path, 'projectFolder/' . $father_File_path);

            $file_type = $this->getFileType($request->image_path);
            $filename = basename($file_path);

            $File = File::create([
                'name' => $filename,
                'folder_id' => $father_File->id,
                'type' => $file_type,
                'description' => 'image Invoice',
            ]);

            ## end add image ######

            // إنشاء الفاتورة
            $invoice = Invoice::create($invoiceData);

            foreach ($invoiceItems as $item) {
                // الحصول على البند المخصص للمشروع
                $itemBudget = ItemBudget::find($item['itemBudget_id']);
                // تحديث الرصيد المتاح للبند
//                    $itemBudget->balance -= $total_price;
//                    $itemBudget->save();
                $total_price = $item['quantity'] * $item['unit_price'];

                // إضافة العنصر إلى الفاتورة
                InvoiceItem::create([
                    'name' => $item['name'],
                    'itemBudget_id' => $item['itemBudget_id'],
                    'invoice_id' => $invoice->id,
                    'unite' => $item['unite'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'total_price_quantity' => $total_price,
                ]);

                $totalInvoiceSum += $total_price; // جمع السعر الإجمالي للفاتورة
            }

            // تحديث السعر الإجمالي للفاتورة
            $invoice->total_price = $totalInvoiceSum;
            $invoice->image_id = $File->id;
            $invoice->save();

            return $this->returnSuccess("Invoice created successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the Invoice. Try again after some time');
        }
    }



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


        if($invoice->status){

            return $this->returnError('Invoice is already confirmed');
        }
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

        return $this->returnData('d', $invoice);
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

        if ($invoice->status) {
            return $this->returnError('The invoice has already been confirmed and the amount has been disbursed. It cannot be canceled.');

        }
        $invoice->status = false;
        $invoice->save();
        return $this->returnSuccess("Invoice has been cancelled");

    }


    public function getAllNewInvoice()
    {
        $invoices = Invoice::with(['invoice_items.itemBudget.item', 'image'])
            ->where('status', null)
            ->get();
        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            $invoice->status = 'New';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });
        });

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            // get path father File
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

        return $this->returnData("invoices", $invoices, 'Get all new invoices');
    }

    public function getAllInvoiceCancelled()
    {

        $invoices = Invoice::with(['invoice_items.itemBudget.item', 'image'])
            ->where('status', false)
            ->get();
        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            $invoice->status = 'Canceled';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });
        });

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            // get path father File
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

        return $this->returnData("invoices", $invoices, 'Get all Cancelled invoices');

    }

    public function getAllInvoiceConfirmed()
    {

        $invoices = Invoice::with(['invoice_items.itemBudget.item', 'image'])
            ->where('status', true)
            ->get();
        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            $invoice->status = 'confirmed';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });
        });

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            // get path father File
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

        return $this->returnData("invoices", $invoices, 'Get all Confirmed invoices');
    }


    public function getInvoiceNewByProjectID(Request $request)
    {

        //Validated
        $validate = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }
        $project = Project::with('tasks')->find($request->project_id);
        $tasks = $project->tasks()->with('invoices')->get();


//        return $this->returnData('tasks',$tasks,'Get the tasks successfully');

        // Retrieve the invoices for each task
        $invoices = collect();

        foreach ($tasks as $task) {
            $taskInvoices = $task->invoices()->with(['invoice_items.itemBudget.item', 'image'])->where('status', null)->get();
            $invoices = $invoices->concat($taskInvoices);
        }
        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            $invoice->status = 'New';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });
        });

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            // get path father File
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

        return $this->returnData("invoices", $invoices);
    }

    public function getInvoiceCancelledByProjectID(Request $request)
    {

        //Validated
        $validate = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }
        $project = Project::with('tasks')->find($request->project_id);
        $tasks = $project->tasks()->with('invoices')->get();


        // Retrieve the invoices for each task
        $invoices = collect();

        foreach ($tasks as $task) {
            $taskInvoices = $task->invoices()->with(['invoice_items.itemBudget.item', 'image'])->where('status', false)->get();
            $invoices = $invoices->concat($taskInvoices);
        }
        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            $invoice->status = 'Canceled';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });
        });

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            // get path father File
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

        return $this->returnData("invoices", $invoices);
    }

    public function getInvoiceConfirmedByProjectID(Request $request)
    {

        //Validated
        $validate = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }
        $project = Project::with('tasks')->find($request->project_id);
        $tasks = $project->tasks()->with('invoices')->get();


        // Retrieve the invoices for each task
        $invoices = collect();

        foreach ($tasks as $task) {
            $taskInvoices = $task->invoices()->with(['invoice_items.itemBudget.item', 'image'])->where('status', true)->get();
            $invoices = $invoices->concat($taskInvoices);
        }

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            $invoice->status = 'confirmed';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });
        });

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            // get path father File
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
        return $this->returnData("invoices", $invoices);
    }

    public function getInvoiceNewByTaskID(Request $request)
    {

        //Validated
        $validate = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        $task = Task::with('invoices')->find($request->task_id);


        $invoices = $task->invoices()->with(['invoice_items.itemBudget.item', 'image'])->where('status', null)->get();


        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            $invoice->status = 'New';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });
        });

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            // get path father File
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

        return $this->returnData("invoices", $invoices);
    }

    public function getInvoiceCancelledByTaskID(Request $request)
    {

        //Validated
        $validate = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        $task = Task::with('invoices')->find($request->task_id);

        $invoices = $task->invoices()->with(['invoice_items.itemBudget.item', 'image'])->where('status', false)->get();
        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            $invoice->status = 'Canceled';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });
        });

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            // get path father File
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


        return $this->returnData("invoices", $invoices);
    }

    public function getInvoiceConfirmedByTaskID(Request $request)
    {

        //Validated
        $validate = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        $task = Task::with('invoices')->find($request->task_id);


        $invoices = $task->invoices()->with(['invoice_items.itemBudget.item', 'image'])->where('status', true)->get();

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            $invoice->status = 'confirmed';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
                $invoiceItem->item_type = $invoiceItem->itemBudget->item->name;
                unset($invoiceItem->itemBudget_id); // Remove itemBudget to clean up the response
                unset($invoiceItem->invoice_id); // Remove invoice_id to clean up the response
                unset($invoiceItem->itemBudget); // Remove itemBudget to clean up the response
            });
        });

        // Format the response to include the item name
        $invoices->each(function ($invoice) {
            // get path father File
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

        return $this->returnData("invoices", $invoices);
    }


    public function getInvoiceConfirmedByDateRange(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        // Parse the start and end dates
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // Retrieve the confirmed invoices within the date range
        $invoices = Invoice::with(['invoice_items.itemBudget.item', 'image'])
            ->where('status', true)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Format the response to include the item name and update the status
        $invoices->each(function ($invoice) {
            $invoice->status = 'confirmed';
            unset($invoice->image_id);
            $invoice->invoice_items->each(function ($invoiceItem) {
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
        $invoicesGroupedByMonth = $invoices->groupBy(function ($invoice) {
            return Carbon::parse($invoice->created_at)->format('Y-m');
        });

        foreach ($invoicesGroupedByMonth as $month => $invoicesInMonth) {
            $monthlyExpenses[$month] = $invoicesInMonth->sum(function ($invoice) {
                return $invoice->invoice_items->sum('total_price_quantity');
            });
        }

        return $this->returnData('monthly_expenses', $monthlyExpenses);
    }

    public function getInvoiceConfirmedByProjectAndDateRange(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        // Parse the start and end dates
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // Retrieve the confirmed invoices within the date range for the specified project
        $project = Project::with('tasks')->find($request->project_id);
        $tasks = $project->tasks()->pluck('id');

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
                return $invoice->invoice_items->sum('total_price_quantity');
            });
        }

        return $this->returnData('monthly_expenses',$monthlyExpenses);
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
