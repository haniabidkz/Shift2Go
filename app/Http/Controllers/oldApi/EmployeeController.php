<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Report;
use App\Models\Rotas;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
class EmployeeController extends Controller
{
    public function companyEmployees()
    {
        $user = Auth::user();
        if(Auth::user()->type == 'company')
        {
            $employees = User::where('created_by', '=', Auth::id())->where('type', '=', 'employee')->get();
        return response()->json(['message' => "Employee list fetched succcessfully", 'data' => $employees, 'error' => false], 200);

        // return view('employee.index',compact('employees','roles_select','box'));
         }



        //  $user = Auth::user();
         if(Auth::user()->type == 'employee')
         {
            // dd("dnkvdfkv");
             $company = User::where('id', '=', Auth::id())->where('type', '=', 'employee')->first();
            //  dd($employees->created_by);
             $employees = User::where('created_by', $company->created_by)
             ->join('employee_roles','employee_roles.created_by','=', 'users.created_by')
             ->select('users.*','employee_roles.*')
             ->get();


         return response()->json(['message' => "Employee list fetched succcessfully", 'data' => $employees, 'error' => false], 200);     
           }
}
}