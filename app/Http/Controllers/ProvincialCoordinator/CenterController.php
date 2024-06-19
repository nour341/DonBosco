<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CenterController extends Controller
{
    use GeneralTrait;
    //
    public function createCenter(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:centers',
            'address' => 'required',
            'image_path' => 'required',
            'country_id' => 'required|exists:countries,id',
        ]);

        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }

        try {

            $image_path = $this->saveFile($request->image_path, 'images/Center');

            $Center = Center::create([
                'name' => $request->name,
                'address' => $request->address,
                'image_path' => $image_path,
                'country_id' => $request->country_id,
            ]);

            return $this->returnSuccess("Center Created Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the Center. Try again after some time');
        }
    }

    public function updateCenter(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required',
                'address' => 'required',
                'country_id' => 'required|exists:countries,id',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }
        try {
            $center=Center::find($request->id);
            if(!$center){
                return $this->returnError('Failed to updated the center does not exist',404);
            }

            // Check if the new center name already exists
            $existingCenter = Center::where('name', $request->name)
                ->where('id', '!=', $request->id)
                ->first();

            if ($existingCenter) {
                return $this->returnError('The center name already exists', 400);
            }


            if ($request->has('image_path')) {
                // delete old image
                $this->removeFile($center->image_path);
                // save new image
                $image_path = $this->saveFile($request->image_path, 'images/Center');
                $center->update([
                    'image_path' => $image_path,
                ]);
            }

            $center->update([
                'name' => $request->name,
                'address' => $request->address,
                'country_id' => $request->country_id,
            ]);

            return $this->returnSuccess("Center updated Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the center. Try again after some time');
        }
    }

    public function deleteCenter(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'center_id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }

        try {
            $center=Center::find($request->center_id);
            if(!$center){
                return $this->returnError('Failed to deleted the center does not exist',404);
            }

            $this->removeFile($center->image_path);
            $center->delete();

            return $this->returnSuccess("Center deleted Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to deleted the center. Try again after some time');
        }
    }

    public function getCenters(){

        try {
            $centers = Center::get();
            return $this->returnData('centers', $centers, 'Successfully retrieved all centers.');}
        catch (\Throwable $th) {
            return $this->returnError('Failed Get the all Centers. Try again after some time');
        }

    }

    public function getCenter(Request $request){
        $id = $request->id;
        $center=Center::find($id);
        if(!$center){
            return $this->returnError('Failed to get centers. the centers does not exist',404);
        }


        return $this->returnData('center',$center,'Get the centers successfully');

    }

    public function getProjectsCenter(Request $request){
        $id = $request->center_id;
        $centers=Center::find($id);
        if(!$centers){
            return $this->returnError('Failed to get centers. the centers does not exist',404);
        }
        $centers=Center::with('projects')->find($id);
        $projects = $centers->projects;
        foreach ($projects as $project) {
            $project->status = $project->getStatus();
            $project->local_coordinator = User::find($project->local_coordinator_id);
            $project->financial_management = User::find($project->financial_management_id);
            $project->supplier = User::find($project->supplier_id);
        }
        return $this->returnData('projects',$projects,'Get the Projects successfully');

    }
    public function getLocalsCenter(Request $request){
        $id = $request->center_id;
        $centers=Center::find($id);
        if(!$centers){
            return $this->returnError('Failed to get centers. the centers does not exist',404);
        }

        $centers = Center::with(['users' => function ($query) {
            $query->where('role_number', 1);
        }])->find($id);

        $locals = $centers->users;

        return $this->returnData('locals',$locals,'Get the locals successfully');
    }
    public function getFinancialsCenter(Request $request){
        $id = $request->center_id;
        $centers=Center::find($id);
        if(!$centers){
            return $this->returnError('Failed to get centers. the centers does not exist',404);
        }

        $centers = Center::with(['users' => function ($query) {
            $query->where('role_number', 2);
        }])->find($id);

        $locals = $centers->users;

        return $this->returnData('financials',$locals,'Get the financials successfully');
    }

    public function getEmployeesCenter(Request $request){
        $id = $request->center_id;
        $centers=Center::find($id);
        if(!$centers){
            return $this->returnError('Failed to get centers. the centers does not exist',404);
        }

        $centers = Center::with(['users' => function ($query) {
            $query->where('role_number', 3);
        }])->find($id);

        $locals = $centers->users;

        return $this->returnData('employees',$locals,'Get the Employees successfully');
    }
}

