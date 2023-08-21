<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

use Validator;

use DB;

use Exception;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        // dd("hdkjfhd");
        try {
            $validatedData = Validator::make(
                $request->all(),
                array(
                    'email' => 'email|required',
                    'password' => 'required'
                )
            );
            
            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $loginData = $request->all();
            if (!auth()->attempt($loginData)) {
                return response()->json(['message' => "Invalid Credentials", 'code' => 1, 'error' => true], 200);
               
            }

             
            // if(auth()->user()->acount_type != 1){
            //     return response()->json(['message' => " You are Not Authorized to Company", 'code' => 1, 'error' => true], 200);

            // }

          

            $user = auth()->user();
           
            $token = auth()->user()->createToken('authToken')->accessToken;
            $user->access_token = $token;
             return response()->json(['message' => "Great! Login Successfully", 'data' => $user, 'error' => false], 200);

            //        if(!$user->email_verified_at and $user->account_type == 0) {
            //            $user->sendEmailVerificationNotification();
            //            return response()->json(['message' => "Sorry! Verify your email First", 'data' => $user, 'code' => 2,'error'=> false, 'isVerified' =>false], 200);
            //        }

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }

    public function managerLogin(Request $request)
    {
        // dd("hdkjfhd");
        try {
            $validatedData = Validator::make(
                $request->all(),
                array(
                    'email' => 'email|required',
                    'password' => 'required'
                )
            );
            
            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            $loginData = $request->all();
            if (!auth()->attempt($loginData)) {
                return response()->json(['message' => "Invalid Credentials", 'code' => 1, 'error' => true], 200);
               
            }

             
            if(auth()->user()->acount_type != 2){
                return response()->json(['message' => " You are Not Authorized to Manager", 'code' => 1, 'error' => true], 200);

            }

          

            $user = auth()->user();
           
            $token = auth()->user()->createToken('authToken')->accessToken;
            $user->access_token = $token;
             return response()->json(['message' => "Great! Manager Login Successfully", 'data' => $user, 'error' => false], 200);

            //        if(!$user->email_verified_at and $user->account_type == 0) {
            //            $user->sendEmailVerificationNotification();
            //            return response()->json(['message' => "Sorry! Verify your email First", 'data' => $user, 'code' => 2,'error'=> false, 'isVerified' =>false], 200);
            //        }

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }
}
