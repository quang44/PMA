<?php
 namespace App\Services;

 class UploadFileService{

     function uploadFile($imageName,$path){
             $image = $imageName;
             $realImage = $image->hashName();
             $newPath = $path . "/$realImage";
             $image->store($path, 'local');
             return $newPath;
     }


 }
