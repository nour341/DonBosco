<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\StatusTask;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatusTaskController extends Controller
{

    use GeneralTrait;

    public function create(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required|unique:status_tasks',
                'short_description' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());
        }
        try {

            StatusTask::create([
                'name'=>$request->name,
                'short_description'=>$request->short_description,
            ]);

            return $this->returnSuccess('The StatusTask created successfully');

        } catch (\Throwable $th) {
            return $this->returnError('Failed create StatusTask . Try again after some time');

        }
    }

    public function update(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required',
                'status_task_id' => 'required',
                'short_description' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());
        }
        // Check if the new StatusTask name already exists
        $existingStatusTask = StatusTask::where('name', $request->name)
            ->where('id', '!=', $request->status_task_id)
            ->first();

        if ($existingStatusTask) {
            return $this->returnError('The Status Task name already exists', 400);
        }

        try {
            $task = StatusTask::find($request->status_task_id); // Find the task by ID
            $task->update([
                'name'=>$request->name,
                'short_description'=>$request->short_description,
            ]);

            return $this->returnSuccess('The task has been successfully updated.');

        } catch (\Throwable $th) {
            return $this->returnError('Failed to update the task. Try again after some time');
        }
    }


    public function delete(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'status_task_id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }

        try {
            $task=StatusTask::find($request->status_task_id);
            if(!$task){
                return $this->returnError('Failed to deleted the Status Task does not exist',404);
            }

            $task->delete();

            return $this->returnSuccess("Status Task deleted Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to deleted the Status Task. Try again after some time');
        }
    }


    public function getStatusTask(){

        try {
            $statusTask = StatusTask::get();
            return $this->returnData('statusTask', $statusTask, 'Successfully retrieved all Status Task.');}
        catch (\Throwable $th) {
            return $this->returnError('Failed Get the all Status Task. Try again after some time');
        }

    }



}
