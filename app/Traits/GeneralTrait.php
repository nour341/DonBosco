<?php


namespace App\Traits;


use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;
trait GeneralTrait
{
    public function returnSuccess($msg = '',
                                  $code = 200)
    {
        return response()->json([
            'state'=>true,
            'message'=> $msg,
            'code'=> $code
        ]);
    }


    public function returnError($msg='',$code=401){

        return response()->json([
            'state'=>false,
            'message'=> $msg,
            'code'=> $code,
        ]);

    }
    public function returnErrorValidate($msg='',$code=400){

        return response()->json([
            'state'=>false,
            'message'=> $msg->first(),
            'code'=> $code,
        ]);

    }

    public function returnData($key,$value,$msg='',$code = 200){

        return response()->json([
            'state'=>true,
            'message'=> $msg,
            $key=> $value,
            'code'=> $code,
        ]);
    }

    function renameFilePh($oldFilePath, $newFileName, $destinationPath): string
    {
        $fileExtension = pathinfo($oldFilePath, PATHINFO_EXTENSION);
        $newFullFileName = $newFileName . "." . $fileExtension;
        $newFilePath = $destinationPath . "/" . $newFullFileName;
        $version = 1;

        // Check if the new file name already exists, and append a version number if needed
        while (file_exists($newFilePath)) {
            $newFullFileName = $newFileName . " (" . $version . ")." . $fileExtension;
            $newFilePath = $destinationPath . "/" . $newFullFileName;
            $version++;
        }

        // Rename the file
        rename($oldFilePath, $newFilePath);

        return $newFilePath;
    }

    function renameFolderPh($oldPath, $newPath)
    {
        if (is_dir($oldPath)) {
            if (rename($oldPath, $newPath)) {
                return true;
            } else {
                return false;
            }
        }

        // Folder does not exist
        return false;
    }

    function removeFolder($path): bool
    {
        if (is_dir($path)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($iterator as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }

            if (rmdir($path)) {
                return true;
            } else {
                return false;
            }
        }

        // Folder does not exist
        return false;
    }

    function createFolder($path): bool
    {
        if (!is_dir($path)) {
            // Create the folder if it doesn't already exist
            if (mkdir($path, 0777, true)) {
                return true;
            }
            else {
                return false;
            }
        } else {
            // Folder already exists
            return false;
        }
    }

    function moveFilePh($sourcePath, $destinationPath): string
    {
        $fileExtension = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $fileName = pathinfo($sourcePath, PATHINFO_FILENAME);
        $fullFileName = $fileName . "." . $fileExtension;
        $version = 1;

        while (file_exists($destinationPath . "/" . $fullFileName)) {
            $fullFileName = $fileName . " (" . $version . ")." . $fileExtension;
            $version++;
        }

        // Move the file to the destination folder
        $success = rename($sourcePath, $destinationPath . "/" . $fullFileName);

        if (!$success) {
            return false;
        }

        $pathFileName = $destinationPath . "/" . $fullFileName;
        return $pathFileName;
    }

    function copyFilePh($sourcePath, $destinationPath): string
    {
        $fileExtension = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $fileName = pathinfo($sourcePath, PATHINFO_FILENAME);
        $fullFileName = $fileName . "." . $fileExtension;
        $version = 1;

        while (file_exists($destinationPath . "/" . $fullFileName)) {
            $fullFileName = $fileName . " (" . $version . ")." . $fileExtension;
            $version++;
        }

        // Copy the file to the destination folder
        copy($sourcePath, $destinationPath . "/" . $fullFileName);

        $pathFileName = $destinationPath . "/" . $fullFileName;
        return $pathFileName;
    }


    function saveFile($photo, $path): string
    {
        $file_extension = pathinfo($photo->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
        $full_file_name = $file_name . "." . $file_extension;
        $version = 1;

        while (file_exists($path . "/" . $full_file_name)) {
            $full_file_name = $file_name . " (" . $version . ")." . $file_extension;
            $version++;
        }

        $photo->move($path, $full_file_name);
        $path_file_name = $path . "/" . $full_file_name;
        return $path_file_name;
    }



    function downloadZip($path_folder): bool|string
    {
        if (!File::isDirectory($path_folder)) {
            return false;
        }

        $files = File::allFiles($path_folder);

        if (empty($files)) {
            return false;
        }

        $zipFile = public_path('download.zip');

        $zip = new ZipArchive;
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // folder zip
            foreach ($files as $file) {
                $relativePath = $file->getRelativePath();
                $zip->addFile($file->getPathname(), $relativePath . '/' . $file->getFilename());
            }

            // file zip
            foreach ($files as $file=>$value) {
                if (file_exists($path_folder.'/'.basename($value)))
                    $zip->addFile($value,basename($value) );
            }
            $zip->close();

            return $zipFile;
        }

        return false;
    }


    private function copyDirectory($source, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $directory = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $dirPath = $destination . '/' . $iterator->getSubPathName();
                if (!is_dir($dirPath)) {
                    mkdir($dirPath, 0777, true);
                }
            } else {
                $newFilePath = $destination . '/' . $iterator->getSubPathName();
                copy($item, $newFilePath);
            }
        }

        return true;
    }


    function getFileType($file): string
    {
        $file_extension = $file->getClientOriginalExtension();
        switch ($file_extension) {
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'txt':
                return 'text/plain';
            case 'doc':
            case 'docx':
                return 'application/msword';
            case 'xls':
            case 'xlsx':
                return 'application/vnd.ms-excel';
            case 'ppt':
            case 'pptx':
                return 'application/vnd.ms-powerpoint';
            case 'pdf':
                return 'application/pdf';
            case 'zip':
                return 'application/zip';
            case 'rar':
                return 'application/x-rar-compressed';
            case 'mp3':
                return 'audio/mpeg';
            case 'mp4':
                return 'video/mp4';
            case 'odt':
                return 'application/vnd.oasis.opendocument.text';
            case 'ods':
                return 'application/vnd.oasis.opendocument.spreadsheet';
            case 'odp':
                return 'application/vnd.oasis.opendocument.presentation';
            case 'rtf':
                return 'application/rtf';
            // Add more cases for additional file types if needed
            default:
                return 'application/octet-stream';
        }
    }

    function removeFile($photo)
    {
        $file_path = public_path($photo);
        if (file_exists($file_path))
            unlink($file_path);

    }

}
