<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Employee;
use App\Models\Location;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Exception;
use Validator;
class LocationController extends ApiController
{
    public function locations()
    {
        // dd("djkfdskjfsd");
        if(Auth::user()->acount_type == 1 || Auth::user()->acount_type == 2)
        {
            $created_by = Auth::user()->get_created_by();
            $locations = Location::where('created_by', $created_by)->get();
           

            return response([

                // 'is_exist' => 0,
                'locations' => $locations,
            
                'message' => "Location fetched successfully",

                'error' => true

            ],200);
        }
        else
        {
            return response()->json(['message' => 'Locations not fetched', 'error' => true], 200);
        }
    }
}
