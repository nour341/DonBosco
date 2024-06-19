<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\ActionFileSystem;
use App\Models\File;
use App\Models\Folder;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FileSystemController extends Controller
{ use GeneralTrait;


    public function creatFolder(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required',
                'father_folder_id' => 'required',
                'project_id' => 'required',
            ]);

        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }

        // Check if the father folder belongs to the same project
        $fatherFolder = Folder::find($request->father_folder_id);
        if ($fatherFolder && $fatherFolder->project_id != $request->project_id) {
            return $this->returnError('The father folder does not belong to the specified project', 400);
        }

        // Check if the new Folder name already exists
        $existingFolder = Folder::where('name', $request->name)
            ->where('father_folder_id', '==', $request->father_folder_id)
            ->first();
        if ($existingFolder) {
            return $this->returnError('The Folder name already exists', 400);
        }

        // get path father folder
        $father_folder_path = '';
        $father_folder = Folder::find($request->father_folder_id);
        if ($father_folder) {
            $father_folder_path = $father_folder->getPath();
        }


        // Check if the new Folder name already exists in public
        $existingFolder = $this->createFolder('projectFolder/'.$father_folder_path.'/'.$request->name);
        if (!$existingFolder) {
            return $this->returnError('The Folder name already exists', 400);
        }


        try {

            $folder = Folder::create([
                'name' => $request->name,
                'father_folder_id' => $request->father_folder_id,
                'project_id' => $request->project_id,
            ]);
            // $user_id = Auth::user();
            // TODO: $user_id
            //
            ActionFileSystem::create([
                'user_id' => 1,
                'short_description' => $request->name.' folder Created',
            ]);
            return $this->returnSuccess("folder Created Successfully");

        } catch (\Throwable $th) {
            // remove Folder public
            // get path father folder
            $folder=Folder::find($request->father_folder_id);
            $father_folder_path = $folder->getPath();
            $this->removeFolder('projectFolder/'.$father_folder_path.'/'.$request->name);
            return $this->returnError('Failed to create the folder. Try again after some time');
        }
    }

    public function reNameFolder(Request $request)
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


            $folder=Folder::find($request->id);
            if(!$folder){
                return $this->returnError('Failed to rename the folder does not exist',404);
            }

            // Check if the new Folder name already exists
            $existingFolder = Folder::where('name', $request->name)
                ->where('id', '!=', $request->id)->where('father_folder_id', '==', $request->father_folder_id)
                ->first();

            if ($existingFolder) {
                return $this->returnError('The Folder name already exists', 400);
            }
            // get path father folder
            $father_folder_path = $folder->getPath();
            $parentDirectoryPath = $folder->getParentDirectoryPath();



            $this->renameFolderPh('projectFolder/'.$father_folder_path,'projectFolder/'.$parentDirectoryPath.'/'.$request->name);
            try {

            $folder->update([
                'name'=>$request->name,
            ]);

                //$user_id = Auth::user();
                //TODO: $user_id
                //

                ActionFileSystem::create([
                    'user_id' => 1,
                    'short_description' => $request->name.' folder rename',
                ]);

            return $this->returnSuccess("folder rename Successfully");

        } catch (\Throwable $th) {
                return $this->returnError('Failed to rename the folder. Try again after some time');
        }
    }

    public function deleteFolder(Request $request)
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

            $folder=Folder::find($request->id);

            if(!$folder){
                return $this->returnError('Failed to deleted the folder does not exist',404);
            }

            // get path father folder
            $father_folder_path = $folder->getPath();

            $folder->delete();
            // remove Folder public
            $this->removeFolder('projectFolder/'.$father_folder_path);

            //$user_id = Auth::user();
            //TODO: $user_id
            //
            ActionFileSystem::create([
                'user_id' => 1,
                'short_description' => $request->name.' folder deleted',
            ]);

            return $this->returnSuccess("Folder deleted Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to deleted the folder. Try again after some time');
        }
    }

    public function getMainFolderProjects()
    {

        try {

            $folders=Folder::where('father_folder_id',null)->get();

            return $this->returnData('MainFolders',$folders,"get Main Folders Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to get Main Folders. Try again after some time');
        }
    }


    public function getChildrenFolder(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'father_folder_id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());
        }
        try {

            $folder=Folder::with('files')->with('childrenFolders')->find($request->father_folder_id);

            if(!$folder){
                return $this->returnError('Failed to get children the folder does not exist',404);
            }


            // get folders and files
            $folders = $folder->childrenFolders()->get();
            $files = $folder->files()->get();

            $files->each(function($file) {
                // get path father File
                $father_File_path = '';
                $father_File = Folder::find($file->folder_id);
                if ($father_File) {
                    $father_File_path = $father_File->getPath();
                }
                $file_path = 'projectFolder/' . $father_File_path . '/' . $file->name;
                $file->path  =$file_path;
            });
            $children = [
                   'folders'=>$folders,
                   'files'=>$files,
                ];

            return $this->returnData('children',$children,"Get children folder Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to Get children folder. Try again after some time');
        }
    }

    public function addFile(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [

                'path' => 'required',
                'folder_id' => 'required',
                'description' => 'required',
            ]);

        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }

        // get path father File
        $father_File_path = '';
        $father_File = Folder::find($request->folder_id);
        if ($father_File) {
            $father_File_path = $father_File->getPath();
        }
        $file_path = $this->saveFile($request->path,'projectFolder/'. $father_File_path);

        $file_type = $this->getFileType($request->path);
        $filename = basename($file_path);

        try {

            $File = File::create([
                'name' => $filename,
                'folder_id' => $request->folder_id,
                'type' => $file_type,
                'description' => $request->description,
            ]);



            // $user_id = Auth::user();
            // TODO: $user_id
            //
            ActionFileSystem::create([
                'user_id' => 1,
                'short_description' => $File->name.' File added',
            ]);
            return $this->returnSuccess("File added Successfully");

        } catch (\Throwable $th) {

            return $this->returnError('Failed to added the File. Try again after some time');
        }
    }

    public function reNameFile(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'name' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }


        $File=File::find($request->id);
        if(!$File){
            return $this->returnError('Failed to rename the File does not exist',404);
        }
        // get path father File
        $father_File_path = '';
        $father_File = Folder::find($File->folder_id);
        if ($father_File) {
            $father_File_path = $father_File->getPath();
        }
        $old_name = $File->name;

        $newFilePath = $this->renameFilePh('projectFolder/'. $father_File_path.'/'.$old_name,$request->name,'projectFolder/'. $father_File_path);
        $filename = basename($newFilePath);

        try {

            $File->update([
                'name'=>$filename,
            ]);

            //$user_id = Auth::user();
            //TODO: $user_id
            //

            ActionFileSystem::create([
                'user_id' => 1,
                'short_description' => $old_name.' File rename to '.$request->name,
            ]);

            return $this->returnSuccess("File rename Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to rename the File. Try again after some time');
        }
    }
    public function deleteFile(Request $request)
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

            $File=File::find($request->id);

            if(!$File){
                return $this->returnError('Failed to deleted the File does not exist',404);
            }

            // get path father File
            $father_File_path = '';
            $father_File = Folder::find($File->folder_id);
            if ($father_File) {
                $father_File_path = $father_File->getPath();
            }

            $path = 'projectFolder/'. $father_File_path.'/'.$File->name;

            $File->delete();
            // remove File public
            $this->removeFile($path);

            //$user_id = Auth::user();
            //TODO: $user_id
            //
            ActionFileSystem::create([
                'user_id' => 1,
                'short_description' => $File->name.' File deleted',
            ]);

            return $this->returnSuccess("File deleted Successfully");

        } catch (\Throwable $th) {
            return $this->returnError('Failed to deleted the File. Try again after some time');
        }
    }

    public function downloadFile(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }


        $File=File::find($request->id);
        if(!$File){
            return $this->returnError('Failed to download the File does not exist',404);
        }

        // get path father File
        $father_File_path = '';
        $father_File = Folder::find($File->folder_id);
        if ($father_File) {
            $father_File_path = $father_File->getPath();
        }

        $path = 'projectFolder/'. $father_File_path.'/'.$File->name;

        try {


            return response()->download($path);

        } catch (\Throwable $th) {
            return $this->returnError('Failed to download the File. Try again after some time');
        }
    }

    public function downloadFolderZip(Request $request)
    {
        //Validated
        $validate = Validator::make($request->all(),
            [
                'id' => 'required',
            ]);
        if($validate->fails()){
            return $this->returnErrorValidate($validate->errors());

        }


        $Folder=Folder::find($request->id);
        if(!$Folder){
            return $this->returnError('Failed to download zip the Folder does not exist',404);
        }

        $path = $Folder->getPath();
        $zipFile = $this->downloadZip('projectFolder/'.$path);
        if(!$zipFile){
            return $this->returnError('Failed to download zip the Folder does not exist',404);
        }
        try {

            return response()->download($zipFile)->deleteFileAfterSend(true);

        } catch (\Throwable $th) {
            return $this->returnError('Failed to  download zip the Folder. Try again after some time');
        }
    }


    public function moveFolder(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'folder_id' => 'required',
            'destination_folder_id' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        // Find the folder by its ID
        $folder = Folder::find($request->folder_id);

        if (!$folder) {
            return $this->returnError('Folder not found', 404);
        }

        // Find the destination folder by its ID
        $destinationFolder = Folder::find($request->destination_folder_id);

        if (!$destinationFolder) {
            return $this->returnError('Destination folder not found', 404);
        }

        // Get the current path of the folder
        $currentPath = 'projectFolder/'.$folder->getPath();

        // Get the new path based on the destination folder
        $newPath = 'projectFolder/'.$destinationFolder->getPath() . '/' . $folder->name;

        try {
                // Move the physical folder to the new location
                if (rename($currentPath, $newPath)) {
                    // Update the folder's father_folder_id in the database
                    $folder->father_folder_id = $destinationFolder->id;
                    $folder->save();

                    // Log the action
                    ActionFileSystem::create([
                        'user_id' => 1,
                        'short_description' => 'Folder "'.$folder->name.'" moved to "'.$destinationFolder->name.'" folder',
                    ]);

                    return $this->returnSuccess('Folder moved successfully');
                } else {
                    return $this->returnError('Failed to move the folder. Try again after some time');
                }


        } catch (\Throwable $th) {
            return $this->returnError('Failed to move the folder. Try again after some time');
        }
    }

    public function moveFile(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'file_id' => 'required',
            'destination_folder_id' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        // Find the file by its ID
        $file = File::find($request->file_id);

        if (!$file) {
            return $this->returnError('File not found', 404);
        }

        // Find the destination folder by its ID
        $destinationFolder = Folder::find($request->destination_folder_id);

        if (!$destinationFolder) {
            return $this->returnError('Destination folder not found', 404);
        }
        // Check if the new Folder name already exists


        if ($destinationFolder->id == $file->folder_id) {
            return $this->returnSuccess('File moved successfully');
        }

        $newFilePath = 'projectFolder/'.$destinationFolder->getPath();
        // get path father File
        $father_File_path = '';
        $father_File = Folder::find($file->folder_id);
        if ($father_File) {
            $father_File_path = $father_File->getPath();
        }
        $file_path = 'projectFolder/'. $father_File_path.'/'.$file->name;
        $file_path = $this->moveFilePh($file_path,$newFilePath);


        try {
            // Move the physical file to the new location
            if ($file_path) {
                // Update the file's folder ID in the database
                $file->folder_id = $destinationFolder->id;
                $file->save();

                // Log the action
                ActionFileSystem::create([
                    'user_id' => 1,
                    'short_description' => 'File "'.$file->name.'" moved to "'.$destinationFolder->name.'" folder',
                ]);

                return $this->returnSuccess('File moved successfully');
            } else {
                return $this->returnError('Failed to move the file. Try again after some time');
            }
        } catch (\Throwable $th) {
            return $this->returnError('Failed to move the file. Try again after some time');
        }
    }

    public function copyFolder(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'folder_id' => 'required',
            'destination_folder_id' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        // Find the folder by its ID
        $folder = Folder::find($request->folder_id);

        if (!$folder) {
            return $this->returnError('Folder not found', 404);
        }

        // Find the destination folder by its ID
        $destinationFolder = Folder::find($request->destination_folder_id);

        if (!$destinationFolder) {
            return $this->returnError('Destination folder not found', 404);
        }

        // Get the current path of the folder
        $currentPath = 'projectFolder/'.$folder->getPath();

        // Get the new path based on the destination folder
        $newPath = 'projectFolder/'.$destinationFolder->getPath() . '/' . $folder->name;

        try {
            DB::beginTransaction();

            // Copy the folder and its contents to the new location
            if ($this->copyDirectory($currentPath, $newPath)) {


                $this->recursivelyCopyFolder($folder, $destinationFolder);

                // Log the action
                ActionFileSystem::create([
                    'user_id' => 1,
                    'short_description' => 'Folder "'.$folder->name.'" copied to "'.$destinationFolder->name.'" folder',
                ]);
                DB::commit();
                return $this->returnSuccess('Folder copied successfully');
            } else {
                return $this->returnError('Failed to copy the folder. Try again after some time');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->returnError('Failed to copy the folder. Try again after some time');
        }
    }

    public function recursivelyCopyFolder($folder, $destinationFolder)
    {


        // Check if the new Folder name already exists
        $existingFolder = Folder::where('name', $folder->name)
            ->where('father_folder_id', '==', $destinationFolder->id)
            ->first();
        if (!$existingFolder) {
            // Create a new folder record in the database
            $newFolder = Folder::create([
                'name' => $folder->name,
                'father_folder_id' => $destinationFolder->id,
                'project_id' => $destinationFolder->project_id,
            ]);
        }else{
            $newFolder = $existingFolder;
        }

        // Copy the files
        foreach ($folder->files as $file) {
            File::create([
                'name' => $file->name,
                'folder_id' => $newFolder->id,
                'description' => $file->description,
                'type' => $file->type,
            ]);
        }

        // Copy the subfolders recursively
        foreach ($folder->childrenFolders as $childFolder) {
            $this->recursivelyCopyFolder($childFolder, $newFolder);
        }
    }

    public function copyFile(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'file_id' => 'required',
            'destination_folder_id' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->returnErrorValidate($validate->errors());
        }

        // Find the file by its ID
        $file = File::find($request->file_id);

        if (!$file) {
            return $this->returnError('File not found', 404);
        }

        // Find the destination folder by its ID
        $destinationFolder = Folder::find($request->destination_folder_id);

        if (!$destinationFolder) {
            return $this->returnError('Destination folder not found', 404);
        }

        // Copy the file to the destination folder
        $newFilePath = 'projectFolder/'.$destinationFolder->getPath();
        // get path father File
        $father_File_path = '';
        $father_File = Folder::find($file->folder_id);
        if ($father_File) {
            $father_File_path = $father_File->getPath();
        }
        $file_path = 'projectFolder/'. $father_File_path.'/'.$file->name;
        $file_path = $this->copyFilePh($file_path,$newFilePath);

        try {

            $filename = basename($file_path);
            // Create a new file record in the database
            $newFile = File::create([
                'name' => $filename,
                'folder_id' => $destinationFolder->id,
                'type' => $file->type,
                'description' => $file->description,
            ]);

            // Log the action
            ActionFileSystem::create([
                'user_id' => 1,
                'short_description' => 'File "'.$file->name.'" copied to "'.$destinationFolder->name.'" folder',
            ]);

            return $this->returnSuccess('File copied successfully');
        }catch (\Throwable $th) {
            return $this->returnError('Failed to move the folder. Try again after some time');
        }
    }

}

