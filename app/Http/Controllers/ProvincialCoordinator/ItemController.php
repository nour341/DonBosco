<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{   use GeneralTrait;

    public function create(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required|unique:items',
            ]);

        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }
        try {
            $item = Item::create([
                'name' => $request->name,
            ]);

            return $this->returnSuccess("Item Created Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to create the Item. Try again after some time');
        }
    }

    public function update(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required',
                'id' => 'required',

            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }
        try {

            $item=Item::find($request->id);
            if(!$item){
                return $this->returnError('Failed to updated the Item does not exist',404);
            }
            // Check if the new Item name already exists
            $existingItem = Item::where('name', $request->name)
                ->where('id', '!=', $request->id)
                ->first();

            if ($existingItem) {
                return $this->returnError('The Item name already exists', 400);
            }

            $item->update([
                'name'=>$request->name,
            ]);

            return $this->returnSuccess("Item updated Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to updated the Item. Try again after some time');
        }
    }

    public function delete(Request $request)
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

            $item=Item::find($request->id);
            if(!$item){
                return $this->returnError('Failed to deleted the Item does not exist',404);
            }

            $item->delete();

            return $this->returnSuccess("Item deleted Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to deleted the Item. Try again after some time');
        }
    }

    public function getItems(){

        try {
            $items=Item::get();

            return $this->returnData('items',$items,'Get the all Items successfully');

        }
        catch (\Throwable $th) {
            return $this->returnError('Failed Get the  Items. Try again after some time');
        }

    }

    public function getItem(Request $request){
        //Validated
        $validate = Validator::make($request->all(),
            [
                'id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());
        }
        $id = $request->id;
        $item=Item::find($id);
        if(!$item){
            return $this->returnError('Failed to get item. the item does not exist',404);
        }

        return $this->returnData('item',$item,'Get the item successfully');

    }


}
