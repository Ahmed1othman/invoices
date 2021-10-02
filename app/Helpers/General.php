<?php

    use Illuminate\Support\Facades\Config;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;


    function get_default_lang(){
        return   Config::get('app.locale');
    }



    function uploadFile($file,$folder, $file_name){
        $file->move($folder,$file_name);
    }

    function deleteFile($folder,$path){
        Storage::disk($folder)->delete($path);
    }

function deletefolder($folder,$path){
    Storage::disk($folder)->deleteDirectory($path);
}



