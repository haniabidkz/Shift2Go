<?php

namespace App\Traits;
use App\Models\BusinesHour;
use App\Models\ServiceImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

trait CommonTrait
{
   public function uploadImages($images,$service_id){
        foreach ($images as $media_files) {
            $service_image  = new ServiceImage();
            $extension = $media_files->getClientOriginalExtension();

            $destination_path = public_path('/images/services');
            $name = rand().time().'.'.$extension;
            $media_files->move($destination_path, $name);
            $image_path = config('app.url').'/images/services/'.$name;
            $service_image->service_id = $service_id;
            $service_image->image = $image_path;
            $service_image->save();
        }
   }

   public function deleteServicImages($images){
        if(count($images)){
            foreach($images as $image){
                $old_image =$this->getImagePathFromURL($image->image);
                if(file_exists($old_image)){
                    unlink($old_image);
                }  
            }
        }
   }

   public function UpdateServiceImage($image,$image_id){
       
        $service_image  = ServiceImage::find($image_id);
        $extension = $image->getClientOriginalExtension();
        $destination_path = public_path('/images/services');
        $name = rand().time().'.'.$extension;
        $image->move($destination_path, $name);
        $image_path = config('app.url').'/images/services/'.$name;
        $old_image =$this->getImagePathFromURL($service_image->image);
        if(file_exists($old_image)){
            unlink($old_image);
        }
        
        $service_image->image = $image_path;
        $service_image->save();
    
    }
    public function DeleteServiceImageFromStorage($image){
        $old_image =$this->getImagePathFromURL($image);
        if(file_exists($old_image)){
            if(unlink($old_image)){
                return true;
            }
        }
        return false;
    }
    public function getImagePathFromURL($url){
        $image_array = explode('.com',$url);
        return public_path($image_array[1]);
    }
    //upload image for all app
    public function uploadImage($image,$uploading_path='/images/'){
        $extension = $image->getClientOriginalExtension();
        $destination_path = public_path($uploading_path);
        $name = rand().time().'.'.$extension;
        $image->move($destination_path, $name);
        return config('app.url').$uploading_path.$name;

   }


   public function createBusinessHoursOnSingup($user_id)
   {    //days id
        $days = ['1'=>"Monday","2"=>"Tuesday","3"=>"Wednesday","4"=>"Thursdat","5"=>"Friday","6"=>"Saturday","7"=>"Sunday"]; 
        $times =["start"=>"09:00","close"=>"20:00"];
        foreach($days as $day){
            //print_r($day);
            BusinesHour::create(['user_id'=>$user_id,'business_day'=>$day,'opening_time'=>$times["start"],'closing_time'=>$times["close"]]);
        }

   }

   public function distanceLatLong($latitude,$longitude)
   {
       if($latitude!='' && $longitude=!'')
       {
           return DB::raw("ROUND(6371 * acos(cos(radians(" . $latitude . "))
           * cos(radians(users.latitude))
           * cos(radians(users.longitude) - radians(" . $longitude . "))
           + sin(radians(" . $latitude . "))
           * sin(radians(users.latitude))),2) AS distance");
       }

       return DB::raw('0 as distance');
   }

   public function StoreResponse($latitude,$longitude){
        return [
            'users.id',
            'users.name',
            'users.profile_image',
            'users.cover_photo',
            'users.country',
            'categories.name as category_name',
            'posts.latitude',
            'posts.longitude',
            'posts.description',
            $this->distanceLatLong($latitude, $longitude)
        ];
   }
}
