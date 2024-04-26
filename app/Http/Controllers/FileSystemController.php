<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class FileSystemController extends Controller
{   use GeneralTrait;
    public function AddFolder(Request $req) {

        Folder::create([
            'project_id'=>$req->project_id,
            'name'=>$req->name,
        ]);
        $arr=[
            'message'=>'The folder has been successfully selected for the project',
            'status'=>200
        ];
        return response($arr,200);

    }


    public function ShowFolders(){

        try {
            $folders=Folder::with('projects')->get();
            $folders = [
                'folders' => $folders
            ];
            return $this->returnData($folders,'Get the all Folders successfully');

        }
        catch (\Throwable $th) {
            return $this->returnError('Failed Get the all Folders. Try again after some time');
        }

    }
}
