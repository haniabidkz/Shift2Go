<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractComment extends Model
{
     protected $fillable = [
        'contract_id',
        'comment',
        'created_by',
    ];

    public function DefaultProfilePic()
    {

        $profile_pic = \Auth::user()->profile_pic;

        $default_profile_pic = 'uploads/profile_pic/avatar.png';
        if(!empty($profile_pic)) {
            $default_profile_pic = 'uploads/profile_pic/'.$profile_pic;
        }
        return $default_profile_pic;
    }

    public function clientcomments()
    {
       return  $this->hasOne(Profile::class,'user_id', 'created_by');
    }





}
