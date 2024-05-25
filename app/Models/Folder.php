<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'project_id',
        'father_folder_id',
        'name',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }
    public function files()
    {
        return $this->hasMany(File::class,'folder_id');
    }

    public function childrenFolders()
    {
        return $this->hasMany(Folder::class, 'father_folder_id');
    }

    public function parentFolder()
    {
        return $this->belongsTo(Folder::class, 'father_folder_id');
    }


    public function getPath()
    {
        $path = $this->name;
        $folder = $this;

        while ($folder->father_folder_id != null) {
            $folder = Folder::find($folder->father_folder_id);
            $path =  $folder->name . '/'. $path;
        }

        return $path;
    }
    public function getParentDirectoryPath()
    {
        $getParentDirectoryPath =dirname($this->getPath());
        return $getParentDirectoryPath;
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
        $currentPath = $folder->getPath();

        // Get the new path based on the destination folder
        $newPath = $destinationFolder->getPath() . '/' . $folder->name;

        try {
            // Copy the folder and its contents to the new location
            if ($this->copyDirectory($currentPath, $newPath)) {
                // Create a new folder record in the database
                $newFolder = Folder::create([
                    'name' => $folder->name,
                    'father_folder_id' => $destinationFolder->id,
                    'project_id' => $folder->project_id,
                ]);

                // Log the action
                ActionFileSystem::create([
                    'user_id' => 1,
                    'short_description' => 'Folder "'.$folder->name.'" copied to "'.$destinationFolder->name.'" folder',
                ]);

                return $this->returnSuccess('Folder copied successfully');
            } else {
                return $this->returnError('Failed to copy the folder. Try again after some time');
            }
        } catch (\Throwable $th) {
            return $this->returnError('Failed to copy the folder. Try again after some time');
        }
    }


}



