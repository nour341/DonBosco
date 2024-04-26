<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\CreateFolderRequest;
use App\Models\File;
use App\Models\Folder;
use App\Models\Project;
use Illuminate\Http\Request;

class FileSystemController extends Controller
{
    public function AddFolder(CreateFolderRequest $req) {

        try{
        $folder = Folder::create([
            'project_id'=>$req->project_id,
            'name'=>$req->name,
        ]);
       return ResponseHelper::success('folder created successfully');

        }
        catch (\Throwable $th) {
            return ResponseHelper::error($th);

        }

    }


    public function getProjectFolders($id){
        $folders=Project::where('id',$id)->with('folders')->get()->toArray();
        if(!$folders){
           return ResponseHelper::error('Error');
        }
        return ResponseHelper::success($folders);
    }

    public function ShowFolder($id){
        $folder=Folder::find($id);
        if(!$folder){
            return ResponseHelper::error('Error');
        }
        return ResponseHelper::success($folder);
    }


}




