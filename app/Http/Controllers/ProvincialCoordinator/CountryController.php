<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
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
                'code' => 'required',
                'phoneCode' => 'required',
            ]);

        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());
        }

        try {
            $country = Country::create([
                'name' => $request->name,
                'code' => $request->code,
                'phoneCode' => $request->phoneCode,
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
                'id' => 'required',
                'name' => 'required',
                'code' => 'required',
                'phoneCode' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }
        try {

            $country=Country::find($request->id);

            if(!$country){
                 return $this->returnError('Failed to updated the country does not exist',404);
            }

            // Check if the new Country name already exists
            $existingCountry = Country::where('name', $request->name)
                ->where('id', '!=', $request->id)
                ->first();

            if ($existingCountry) {
                return $this->returnError('The Country name already exists', 400);
            }

            $country->update([
                'name'=>$request->name,
                'code' => $request->code,
                'phoneCode' => $request->phoneCode,
            ]);

            return $this->returnSuccess("Country updated Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the country. Try again after some time');
        }
    }

    public function deleteCountry(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());
        }

        try {

            $country=Country::find($request->id);
            if(!$country){
                 return $this->returnError('Failed to deleted the country does not exist',404);
            }

            $country->delete();

            return $this->returnSuccess("Country deleted Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to deleted the country. Try again after some time');
        }
    }

    public function getCountries(){
        try {
            $countries=Country::get();

            return $this->returnData('countries',$countries,'Get the all Countries successfully');

        }
        catch (\Throwable $th) {
                return $this->returnError('Failed Get the  Countries. Try again after some time');
        }

    }

    public function getCountry(Request $request){

        $id = $request->country_id;
        $country=Country::find($id);
        if(!$country){
            return $this->returnError('Failed to get country. the country does not exist',404);
        }

        return $this->returnData('country',$country,'Get the country successfully');

    }


    public function getProjectsCountry(Request $request){
        $id = $request->country_id;
        $country=Country::find($id);
        if(!$country){
            return $this->returnError('Failed to get country. the country does not exist',404);
        }
        $country=Country::with('centers')->find($id);
        $centers = $country->centers()->with('projects')->get();
        $projects = $centers->pluck('projects')->flatten();

        foreach ($projects as $project) {
            $project->status = $project->getStatus();
            $project->local_coordinator = User::find($project->local_coordinator_id);
            $project->financial_management = User::find($project->financial_management_id);
            $project->supplier = User::find($project->supplier_id);
        }
        return $this->returnData('projects',$projects,'Get the Projects successfully');

    }

    public function getCentersCountry(Request $request){
        $id = $request->country_id;
        $country=Country::find($id);
        if(!$country){
            return $this->returnError('Failed to get country. the country does not exist',404);
        }
        $country=Country::with('centers')->find($id);
        $centers = $country->centers()->get();

        return $this->returnData('centers',$centers,'Get the Centers successfully');

    }

}
