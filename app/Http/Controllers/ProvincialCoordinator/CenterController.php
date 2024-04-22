<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Http\Requests\CenterRequest;
use App\Models\Center;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CenterController extends Controller
{
    use GeneralTrait;
    //
    public function createCenter(CenterRequest $request)
    {
        //Validated
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:centers',
            'address' => 'required',
            'image_path' => 'required',
            'country_id' => 'required|exists:countries,id',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors()
            ], 401);
        }

        try {

            $image_path = $this->saveImage($request->image_path, 'images/Center');

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
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors()
            ], 401);
        }
        try {
            $center=Center::find($request->id);
            if(!$center){
                return $this->returnError('Failed to updated the center does not exist');
            }
            if ($request->has('image_path')) {
                $this->deletImage($center->image_path);
                $image_path = $this->saveImage($request->image_path, 'images/Center');
                $center->update([
                    'main_img' => $image_path,
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

    public function getCenters(){

        try {
            $centers=Center::with('projects')->get();
            $centers = [
                'centers' => $centers
            ];
            return $this->returnData($centers,'Get the all Centers successfully');

        }
        catch (\Throwable $th) {
            return $this->returnError('Failed Get the all Centers. Try again after some time');
        }

    }

    public function getCenter($id){
        $centers=Center::find($id);
        if(!$centers){
            return $this->returnError('Failed to get centers. the centers does not exist');
        }
        $centers=Center::with('projects')->find($id);

        $centers = [
            'center' => $centers
        ];

        return $this->returnData($centers,'Get the centers successfully');

    }



    public function getProjectsCenter($id){
        $centers=Center::find($id);
        if(!$centers){
            return $this->returnError('Failed to get centers. the centers does not exist');
        }
        $centers=Center::with('projects')->find($id);
        $projects = $centers->projects;

        $projects = [
            'projects' => $projects
        ];
        return $this->returnData($projects,'Get the Projects successfully');

    }
}
