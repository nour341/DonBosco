<?php


namespace App\Traits;


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

    public function returnData($value,$msg='',$code = 200){

        return response()->json([
                'state'=>true,
                'message'=> $msg,
                'data'=> $value,
                'code'=> $code,
            ]);
    }




    function saveImage($photo,$path){

        $file_extension=$photo->getClientOriginalName();
        $file_name=time().".".$file_extension;
        $photo->move($path,$file_name);
        $path_file_name = $path."/".$file_name;
        return $path_file_name;

    }

    function deletImage($photo)
    {
        $file_path = public_path($photo);
        if (file_exists($file_path))
            unlink($file_path);

    }

    }
