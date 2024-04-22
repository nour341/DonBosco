<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function AddReport(Request $req) {
        File::create([
            'project_id'=>$req->project_id,
            'folder_id'=>$req->folder_id,

        ]);
        $arr=[
            'message'=>'The Report has been successfully selected for the project',
            'status'=>200
        ];
        return response($arr,200);

    }


    public function showReports(){

        try {
            $files=File::with('projects')->get();
            $files = [
                'folders' => $files
            ];
            return $this->returnData($files,'Get the all files successfully');

        }
        catch (\Throwable $th) {
            return $this->returnError('Failed Get the all files. Try again after some time');
        }

    }


}
