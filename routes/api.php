<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});                                                                            

Route::post('login', 'App\Http\Controllers\Api\LoginController@login');
Route::middleware('auth:api')->group(function () {
   
 Route::get('/employees', 'App\Http\Controllers\Api\EmployeeController@companyEmployees');
 Route::post('/verifyEmploye', 'App\Http\Controllers\Api\EmployeeController@verifyEmployee');
 Route::get('/locations', 'App\Http\Controllers\Api\LocationController@locations');
 Route::post('/clockIn', 'App\Http\Controllers\Api\AttendanceController@clockIn');
 Route::post('/clockOut', 'App\Http\Controllers\Api\AttendanceController@clockOut');
 Route::post('/breakIn', 'App\Http\Controllers\Api\AttendanceController@breakIn');
 Route::post('/breakOut', 'App\Http\Controllers\Api\AttendanceController@breakOut');
 Route::post('/employee_status', 'App\Http\Controllers\Api\EmployeeController@employeeStatus');

});