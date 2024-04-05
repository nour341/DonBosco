<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CountryController extends Controller
{    use GeneralTrait;
    //
    public function createCountry(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required|unique:countries',
            ]);

        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors()
            ], 401);
        }
        try {
            $country = Country::create([
                'name' => $request->name,
            ]);

            return $this->returnSuccess("Country Created Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the country. Try again after some time');
        }
    }

    public function updateCountry(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required',
            ]);
        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors()
            ], 401);
        }
        try {

            $country=Country::find($request->id);
            if(!$country){
                 return $this->returnError('Failed to updated the country does not exist');
            }

            $country->update([
                'name'=>$request->name,
            ]);

            return $this->returnSuccess("Country updated Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the country. Try again after some time');
        }
    }

    public function getCountries(){

        try {
            $countries=Country::with('centers')->get();
            $countries = [
                'countries' => $countries
            ];
            return $this->returnData($countries,'Get the all Countries successfully');

        }
        catch (\Throwable $th) {
                return $this->returnError('Failed Get the  Countries. Try again after some time');
        }

    }

    public function getCountry($id){
        $country=Country::find($id);
        if(!$country){
            return $this->returnError('Failed to get country. the country does not exist');
        }
        $country=Country::with('centers')->find($id);

        $country = [
        'country' => $country
        ];

        return $this->returnData($country,'Get the country successfully');

    }
    public function getProjectsCountry($id){
        $country=Country::find($id);
        if(!$country){
            return $this->returnError('Failed to get country. the country does not exist');
        }
        $country=Country::with('centers')->find($id);
        $projects = $country->centers()->with('projects')->get();

        $projects = [
        'projects' => $projects
        ];
        return $this->returnData($projects,'Get the Projects successfully');

    }


}
