<?php
namespace App\Traits;

trait Upload{
    public static function store_signature($base64, $user_id){
        $folderPath = public_path('user-uploads/signature/'.$user_id.'/');

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }
       
        $image_parts = explode(";base64,", $base64);
             
        $image_type_aux = explode("image/", $image_parts[0]);
           
        $image_type = $image_type_aux[1];
           
        $image_base64 = base64_decode($image_parts[1]);
 
        $signature = uniqid() . '.'.$image_type;
           
        $file = $folderPath . $signature;
        
        $relative_path = 'user-uploads/signature/'.$user_id.'/'.$signature;
 
        file_put_contents($file, $image_base64);

        return $relative_path;
    }
}