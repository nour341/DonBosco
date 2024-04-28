<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateReportRequest;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function AddReport(CreateReportRequest $req) {

        try{
        $Report = File::create([
            'project_id'=>$req->project_id,
            'folder_id'=>$req->folder_id,
            'name' => $req->name,
            'description' => $req->description,
        ]);
       return ResponseHelper::success('Report created successfully');

        }
        catch (\Throwable $th) {
            return ResponseHelper::error($th);

        }

    }

   /// public function showReports(){

      //  try {
        //    $files=File::query()->get()->toArray();

          //  return ResponseHelper::success($files);
        //}
        //catch (\Throwable $th) {
          //  return ResponseHelper::error('Error');
        //}

   // }
    public function getFolderReports($id){
        $Reports=Folder::where('id',$id)->with('Reports')->get()->toArray();
      if(!$Reports){
          return ResponseHelper::error('Error');
        }
        return ResponseHelper::success($Reports);
    }

}
