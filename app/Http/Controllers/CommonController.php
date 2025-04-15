<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommonController extends BaseController
{
    public function compressImage($sourcePath, $destinationPath, $maxSizeKB)
    {
        // Your common logic here
        // return "hello";

        if(filesize($sourcePath)>$maxSizeKB*1024){
    
            $img = Image::make($sourcePath);        
            // Save the image to a temporary path
    
            $tempPath = $destinationPath;
    
            $width = $img->width();
            $height = $img->height();
            $maxWidth = 1920; // Example max width
            $maxHeight = 1080; // Example max height
    
            //Size is critical especially for large images, it reduces the size of the image significantly
            if ($width > $maxWidth || $height > $maxHeight) {
                $img->resize($maxWidth, $maxHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
    
            $img->save($tempPath, 90);
    
            $quality = 100; // Start with high quality
    
            while (filesize($tempPath) > $maxSizeKB * 1024 && $quality > 10) {
                
                $quality -= 10; // Reduce quality by 10%
                $img->save($tempPath, $quality);
                
                // Debugging information
                // clearstatcache(); // Clear cache to get the latest file size
                clearstatcache(true, $tempPath); // Clear cache for specific file
                
            }
    
            // Move the compressed image to the final destination
            rename($tempPath, $destinationPath);
    
            return "Compression Successful".$destinationPath;
    
        }else{
            rename($sourcePath, $destinationPath);
            return "File does not need compression ".$sourcePath."<br/>"; 
        }
    }

    function haversineDistance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
    {
        $earthRadius = ($unit == 'K') ? 6371 : 3958.8; // Earth's radius in km or miles

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function teamIdCheckAssign($request){
        if(auth()->user()->usertype=="admin"){
            $teamIdValid=$request->validate([
                'team_id'=>'required|numeric|digits_between:1,20',
            ]);
            if($teamIdValid['team_id']==0){
                $teamId = null;
            }else{
                $teamId = $teamIdValid['team_id'];
            }
        }else{
        $teamId=auth()->user()->team_id;
        }
        return $teamId;
    }
}
