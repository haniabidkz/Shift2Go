<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiTemplateController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BreakController;

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BankTransferController;
use App\Http\Controllers\CoingatePaymentController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DailyViewController;
use App\Http\Controllers\EmbargoController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeesettingController;
use App\Http\Controllers\FlutterwavePaymentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IpRestrictController;
use App\Http\Controllers\IyziPayController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LogbookcategoriesController;
use App\Http\Controllers\LoginDetailController;
use App\Http\Controllers\MercadoPaymentController;
use App\Http\Controllers\MolliePaymentController;
use App\Http\Controllers\NotificationTemplatesController;
use App\Http\Controllers\PastemployeesController;
use App\Http\Controllers\PayfastController;
use App\Http\Controllers\PaymentWallPaymentController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaySlipController;
use App\Http\Controllers\PaystackPaymentController;
use App\Http\Controllers\PaytmPaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RotasController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\SkrillPaymentController;
use App\Http\Controllers\SspayController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\ToyyibpayController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCouponController;
use App\Http\Controllers\UserViewController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ZoomMeetingController;
// use Artisan;

/*
|--------------------------------------------------------------------------port_rotas_popup
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/config-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return redirect()->back()->with('success', 'Clear Cache successfully.');
});

Route::resource('/userLogin', LoginDetailController::class)->middleware(['auth','XSS']);

Route::get('/', [HomeController::class,'index'])->name('home')->middleware(['XSS']);

Route::post('/rota-date-change', [RotasController::class,'rota_date_change'])->name('rota.date.change')->middleware(['XSS']);
Route::post('/slug-match', [RotasController::class,'slug_match'])->name('slug.match')->middleware(['XSS']);
Route::get('/rotas/share/{slug}', [RotasController::class,'share_rotas'])->name('rotas.share')->middleware(['XSS']);
Route::post('/rotas/share_rotas_link', [RotasController::class,'share_rotas_link'])->name('rotas.share_rotas_link')->middleware(['auth','XSS']);
Route::get('/rotas/share_rotas_popup', [RotasController::class,'share_rotas_popup'])->name('rotas.share_popup')->middleware(['auth','XSS']);
Route::post('/rotas/shift_disable_reply', [RotasController::class,'shift_disable_reply'])->name('rotas.shift.disable.reply')->middleware(['auth','XSS']);
Route::get('/rotas/shift_disable_response/{id}', [RotasController::class,'shift_disable_response'])->name('rotas.shift.response')->middleware(['auth','XSS']);
Route::post('/rotas/shift_disable', [RotasController::class,'shift_disable'])->name('rotas.shift.disable')->middleware(['auth','XSS']);
Route::get('/rotas/shift_cancel/{id}', [RotasController::class,'shift_cancel'])->name('rotas.shift.cancel')->middleware(['auth','XSS']);
Route::get('/rotas/print_rotas_popup', [RotasController::class,'print_rotas_popup'])->name('rotas.print_rotas_popup')->middleware(['auth','XSS']);

Route::get('/rotas/export_rotas_popup', [RotasController::class,'export_rotas_popup'])->name('rotas.export_rotas_popup')->middleware(['auth','XSS']);
Route::post('rotas/export', [RotasController::class,'exportrotasInvoice'])->name('rotas.export')->middleware(['XSS']);

Route::group(['middleware' => ['verified']], function (){

Route::get('breaks/{id}', [BreakController::class, 'break'])->name('breaks');

// Route::resource('/break', BreakController::class)->middleware(['auth','XSS']);
Route::resource('/attendance', AttendanceController::class)->middleware(['auth','XSS']);
Route::post('/clock_in', [AttendanceController::class,'inattendance'])->name('clock_in.userattendance')->middleware(['auth','XSS']);
Route::put('/clock_out', [AttendanceController::class,'outattendance'])->name('clock_out.userattendance')->middleware(['auth','XSS']);
Route::get('import/attendance/file', [AttendanceController::class, 'importFile'])->name('attendance.file.import');
Route::post('import/attendance', [AttendanceController::class, 'import'])->name('attendance.import');

Route::get('attendanceemployee/bulkattendance', [AttendanceController::class, 'bulkAttendance'])->name('attendanceemployee.bulkattendance')->middleware(['auth','XSS']);

Route::post('attendanceemployee/bulkattendance', [AttendanceController::class,'bulkAttendanceData'])->name('attendanceemployee.bulkattendance')->middleware(['auth','XSS']);

Route::resource('/home', HomeController::class)->middleware(['auth','XSS']);

Route::any('/get_rota_data',[HomeController::class, 'get_rota_data'])->name('get_rota_data');

Route::post('/dashboard/location_filter', [HomeController::class,'location_filter'])->name('dashboard.location_filter')->middleware(['auth','XSS']);
Route::resource('/dashboard', HomeController::class)->middleware(['auth','XSS','verified']);
Route::post('dayview_filter', [DailyViewController::class,'dayview_filter'])->name('dayview_filter')->middleware(['XSS']);
Route::resource('/day', DailyViewController::class)->middleware(['auth','XSS']);
Route::post('userviewfilter', [UserViewController::class,'userviewfilter'])->name('userviewfilter')->middleware(['XSS']);
Route::resource('/user-view', UserViewController::class)->middleware(['auth','XSS']);

Route::post('copy_week_sheet', [RotasController::class,'copy_week_sheet'])->name('copy.week.sheet')->middleware(['auth','XSS']);
Route::post('hideavailability', [RotasController::class,'hideavailability'])->name('hideavailability')->middleware(['auth','XSS']);
Route::post('hideleave', [RotasController::class,'hideleave'])->name('hideleave')->middleware(['auth','XSS']);
Route::post('hidedayoff', [RotasController::class,'hidedayoff'])->name('hidedayoff')->middleware(['auth','XSS']);
Route::post('rotas/print', [RotasController::class,'printrotasInvoice'])->name('rotas.print')->middleware(['XSS']);

Route::post('/rotas/send_email_rotas', [RotasController::class,'send_email_rotas'])->name('rotas.send_email_rotas')->middleware(['auth','XSS']);
Route::get('/rotas/add_remove_employee', [RotasController::class,'add_remove_employee'])->name('rotas.add_remove_employee')->middleware(['auth','XSS']);
Route::get('/rotas/add_remove_employee_popup', [RotasController::class,'add_remove_employee_popup'])->name('rotas.add_remove_employee_popup')->middleware(['auth','XSS']);
Route::post('/rotas/add_dayoff', [RotasController::class,'add_dayoff'])->name('rotas.add_dayoff')->middleware(['auth','XSS']);
Route::post('/rotas/shift_copy', [RotasController::class,'shift_copy'])->name('rotas.shift_copy')->middleware(['auth','XSS']);
Route::post('/rotas/publish_week', [RotasController::class,'publish_week'])->name('rotas.publish_week')->middleware(['auth','XSS']);
Route::post('/rotas/un_publish_week', [RotasController::class,'un_publish_week'])->name('rotas.un_publish_week')->middleware(['auth','XSS']);
Route::post('/rotas/clear_week', [RotasController::class,'clear_week'])->name('rotas.clear_week')->middleware(['auth','XSS']);
Route::post('/rotas/week_sheet', [RotasController::class,'week_sheet'])->name('rotas.week_sheet')->middleware(['auth','XSS']);
Route::resource('/rotas', RotasController::class)->middleware(['auth','XSS']);

Route::post('/change-password', [ProfileController::class,'updatePassword'])->name('update.password');
Route::get('/profile/{id?}', [ProfileController::class,'index'])->name('profile')->middleware(['auth','XSS']);
Route::resource('/profile', ProfileController::class)->middleware(['auth','XSS']);

Route::any('/employee/addpassword/{id}', [EmployeeController::class,'addpassword'])->name('employee.addpassword')->middleware(['auth','XSS']);
Route::get('/employee/set_password/{id}', [EmployeeController::class,'set_password'])->name('employee.set_password')->middleware(['auth','XSS']);

Route::any('/employee/addpin/{id}', [EmployeeController::class,'addpin'])->name('employee.addpin')->middleware(['auth','XSS']);
Route::get('set_pin/{id}', [EmployeeController::class,'set_pin'])->name('set_pin')->middleware(['auth','XSS']);

Route::get('/employee/manage_permission/{id}', [EmployeeController::class,'manage_permission'])->name('employee.manage_permission')->middleware(['auth','XSS']);
Route::any('employee/restore/{id}', [EmployeeController::class,'restore'])->name('employee.restore')->middleware(['auth','XSS']);
Route::post('employee/send-invitation/{id}', [EmployeeController::class,'send_invitation'])->name('employee.send_invitation')->middleware(['auth','XSS']);
Route::resource('/employees', EmployeeController::class)->middleware(['auth','XSS']);

Route::get('/change/mode',[EmployeeController::class,'changeMode'])->name('change.mode');

Route::resource('/locations', LocationController::class)->middleware(['auth','XSS']);

Route::resource('/roles', RoleController::class)->middleware(['auth','XSS']);

Route::resource('/past-employees', PastemployeesController::class)->middleware(['auth','XSS']);

Route::resource('/groups', GroupController::class)->middleware(['auth','XSS']);

Route::get('/holidays/annual-leave/{id}', [LeaveController::class,'annual_leave'])->name('holidays.annual_leave')->middleware(['auth','XSS']);
Route::get('/holidays/view-leave-response/{id}', [LeaveController::class,'view_leave_response'])->name('holidays.view-leave-response')->middleware(['auth','XSS']);
Route::get('/holidays/view-leave/{id}', [LeaveController::class,'view_leave'])->name('holidays.view_leave')->middleware(['auth','XSS']);
Route::post('/holidays/annual-leave-response/{id}', [LeaveController::class,'annual_leave_response'])->name('holidays.annual-leave-response')->middleware(['auth','XSS']);
Route::post('/holidays/leave_sheet', [LeaveController::class,'leave_sheet'])->name('holidays.leave_sheet')->middleware(['auth','XSS']);
Route::resource('/holidays', LeaveController::class)->middleware(['auth','XSS']);

Route::resource('/embargoes', EmbargoController::class)->middleware(['auth','XSS']);

Route::resource('/rules', RulesController::class)->middleware(['auth','XSS']);

Route::get('/leave-request/reply/{id}', [LeaveRequestController::class,'reply'])->name('leave-request.reply')->middleware(['auth','XSS']);
Route::post('/leave-request/response/{id}', [LeaveRequestController::class,'reply_response'])->name('leave-request.response')->middleware(['auth','XSS']);
Route::post('/leave-request/response-delete/{id}', [LeaveRequestController::class,'response_delete'])->name('leave-request.response-delete')->middleware(['auth','XSS']);
Route::resource('/leave-request', LeaveRequestController::class)->middleware(['auth','XSS']);

Route::get('/reports/{id?}', [ReportController::class,'index'])->name('reports')->middleware(['auth','XSS']);
Route::resource('/reports', ReportController::class)->middleware(['auth','XSS']);

Route::resource('/availabilities', AvailabilityController::class)->middleware(['auth','XSS']);

Route::post('payment-setting', [EmployeesettingController::class,'savePaymentSettings'])->name('payment.setting')->middleware(['auth','XSS']);
Route::post('email-setting', [EmployeesettingController::class,'saveEmailSettings'])->name('email.setting')->middleware(['auth','XSS']);
Route::post('pusher-setting', [EmployeesettingController::class,'pusherSetting'])->name('pusher.setting');

Route::post('test', [EmployeesettingController::class,'testMail'])->name('test.mail')->middleware(['auth','XSS']);
Route::post('test-mail', [EmployeesettingController::class,'testSendMail'])->name('test.send.mail')->middleware(['auth','XSS']);



Route::get('/leave-request/reply/{id}', [LeaveRequestController::class,'reply'])->name('leave-request.reply')->middleware(['auth','XSS']);
Route::get('/setting/saveBusinessSettings', [EmployeesettingController::class,'saveBusinessSettings'])->name('setting.saveBusinessSettings')->middleware(['auth','XSS']);
Route::post('/setting/saveZoomSettings/{id}', [EmployeesettingController::class,'saveZoomSettings'])->name('setting.ZoomSettings')->middleware(['auth','XSS']);
Route::any('/setting/saveCompanySetting/{id}', [EmployeesettingController::class,'saveCompanySettings'])->name('setting.CompanySettings')->middleware(['auth']);
Route::resource('/setting', EmployeesettingController::class)->middleware(['auth','XSS']);

Route::resource('/user', UserController::class)->middleware(['auth','XSS']);
Route::resource('/plan', PlanController::class)->middleware(['auth','XSS']);
Route::get('user/{id}/plan', [UserController::class,'upgradePlan'])->name('plan.upgrade')->middleware(['auth', 'XSS']);
Route::get('user/{id}/plan/{pid}', [UserController::class,'activePlan'])->name('plan.active')->middleware(['auth', 'XSS']);
Route::post('plan-pay-with-paypal', [PaypalController::class,'planPayWithPaypal'])->name('plan.pay.with.paypal')->middleware(['auth', 'XSS']);
Route::get('{id}/{amount}/{coupon}/plan-get-payment-status', [PaypalController::class,'planGetPaymentStatus'])->name('plan.get.payment.status')->middleware(['auth', 'XSS']);


Route::group(['middleware' => ['auth', 'XSS']], function (){
    Route::resource('coupon', CouponController::class);
});

Route::get('/apply-coupon', [CouponController::class,'applyCoupon'])->name('apply.coupon')->middleware(['auth', 'XSS']);

Route::group(['middleware' => ['auth', 'XSS']], function (){
    Route::get('order', [StripePaymentController::class,'index'])->name('order.index');
    Route::get('/stripe/{code}', [StripePaymentController::class,'stripe'])->name('stripe');
    Route::post('/stripe', [StripePaymentController::class,'stripePost'])->name('stripe.post');
});

Route::post('/bankpay', [BankTransferController::class,'bankpayPost'])->name('bankpay.post')->middleware(['auth', 'XSS']);
Route::post('/PaymentApproval/{order}',[BankTransferController::class,'bankPaymentApproval'])->name('bankPaymentApproval.response')->middleware(['auth', 'XSS']);
Route::resource('bankpays', BankTransferController::class)->middleware(['auth', 'XSS']);


Route::group([ 'middleware' => [ 'auth', 'XSS', ], ], function () {
    Route::get('change-language/{lang}', [LanguageController::class,'changeLanguage'])->name('change.language');
    Route::get('manage-language/{lang}', [LanguageController::class,'manageLanguage'])->name('manage.language');
    Route::post('store-language-data/{lang}', [LanguageController::class,'storeLanguageData'])->name('store.language.data');
    Route::get('create-language', [LanguageController::class,'createLanguage'])->name('create.language');
    Route::post('store-language', [LanguageController::class,'storeLanguage'])->name('store.language');
    Route::delete('lang/{lang}',[LanguageController::class,'destroyLang'])->name('lang.destroy');
});

//================================= Custom Landing Page ====================================//

// Route::get('/landingpage', 'LandingPageSectionsController@index')->name('custom_landing_page.index')->middleware(['auth','XSS']);

//================================= Payment Gateways  ====================================//

Route::post('/plan-pay-with-paystack',[PaystackPaymentController::class,'planPayWithPaystack'])->name('plan.pay.with.paystack')->middleware(['auth','XSS']);
Route::get('/plan/paystack/{pay_id}/{plan_id}', [PaystackPaymentController::class,'getPaymentStatus'])->name('plan.paystack');

Route::post('/plan-pay-with-flaterwave',[FlutterwavePaymentController::class,'planPayWithFlutterwave'])->name('plan.pay.with.flaterwave')->middleware(['auth','XSS']);
Route::get('/plan/flaterwave/{txref}/{plan_id}', [FlutterwavePaymentController::class,'getPaymentStatus'])->name('plan.flaterwave');

Route::post('/plan-pay-with-razorpay',[RazorpayPaymentController::class,'planPayWithRazorpay'])->name('plan.pay.with.razorpay')->middleware(['auth','XSS']);
Route::get('/plan/razorpay/{txref}/{plan_id}', [RazorpayPaymentController::Class,'getPaymentStatus'])->name('plan.razorpay');

Route::post('/plan-pay-with-paytm',[PaytmPaymentController::class,'planPayWithPaytm'])->name('plan.pay.with.paytm')->middleware(['auth','XSS']);
Route::post('/plan/paytm/{plan}', [PaytmPaymentController::class,'getPaymentStatus'])->name('plan.paytm');

Route::post('/plan-pay-with-mercado',[MercadoPaymentController::class,'planPayWithMercado'])->name('plan.pay.with.mercado')->middleware(['auth','XSS']);
Route::get('/plan/mercado/{plan}', [MercadoPaymentController::class,'getPaymentStatus'])->name('plan.mercado.callback');

Route::post('/plan-pay-with-mollie',[MolliePaymentController::class,'planPayWithMollie'])->name('plan.pay.with.mollie')->middleware(['auth','XSS']);
Route::get('/plan/mollie/{plan}', [MolliePaymentController::class,'getPaymentStatus'])->name('plan.mollie');

Route::post('/plan-pay-with-skrill',[SkrillPaymentController::class,'planPayWithSkrill'])->name('plan.pay.with.skrill')->middleware(['auth','XSS']);
Route::get('/plan/skrill/{plan}', [SkrillPaymentController::class,'getPaymentStatus'])->name('plan.skrill');

Route::any('/plan-pay-with-coingate',[CoingatePaymentController::class,'planPayWithCoingate'])->name('plan.pay.with.coingate')->middleware(['auth','XSS']);
Route::any('/plan/coingate/{plan}', [CoingatePaymentController::class,'getPaymentStatus'])->name('plan.coingate');

Route::post('/paymentwalls' , [PaymentWallPaymentController::class,'paymentwall'])->name('plan.paymentwallpayment')->middleware(['XSS']);
Route::post('/plan-pay-with-paymentwall/{plan}',[PaymentWallPaymentController::class,'planPayWithPaymentWall'])->name('plan.pay.with.paymentwall')->middleware(['XSS']);
Route::get('/plans/{flag}', [PaymentWallPaymentController::class,'planeerror'])->name('error.plan.show');

Route::post('/pay-with-toyyibpay', [ToyyibpayController::class, 'charge'])->name('plan.toyyibpaypayment')->middleware(['auth','XSS']);
Route::get('/plan/toyyibpay/{planId}/{getAmount}/{couponCode}', [ToyyibpayController::class, 'status'])->name('plan.status');

Route::post('payfast-plan', [PayfastController::class, 'index'])->name('payfast.payment')->middleware(['auth']);
Route::get('payfast-plan/{success}', [PayfastController::class, 'success'])->name('payfast.payment.success')->middleware(['auth']);

Route::group(['middleware' => ['auth','XSS','revalidate',],], function (){
    Route::resource('plan_request', PlanRequestController::class);
});

// Plan Request Module
Route::get('plan_request', [PlanRequestController::class,'index'])->name('plan_request.index')->middleware(['auth','XSS',]);
Route::get('request_frequency/{id}', [PlanRequestController::class,'requestView'])->name('request.view')->middleware(['auth','XSS',]);
Route::get('request_send/{id}', [PlanRequestController::class,'userRequest'])->name('send.request')->middleware(['auth','XSS',]);
Route::get('request_response/{id}/{response}', [PlanRequestController::class,'acceptRequest'])->name('response.request')->middleware(['auth','XSS',]);
Route::get('request_cancel/{id}', [PlanRequestController::class,'cancelRequest'])->name('request.cancel')->middleware(['auth','XSS',]);
// End Plan Request Module

//=======================================Import/Export======================================//
Route::get('import/employee/file', [EmployeeController::class,'importFile'])->name('employee.file.import');
Route::post('import/employee', [EmployeeController::class,'import'])->name('employee.import');
Route::get('export/employee', [EmployeeController::class,'export'])->name('employee.export');

Route::get('export/location', [LocationController::class,'export'])->name('location.export');

Route::get('export/leave', [LeaveRequestController::class,'export'])->name('leave.export');

Route::get('export/availability' , [AvailabilityController::class,'export'])->name('availability.export');

// Route::get('export/rotas' , [RotasController::class,'export'])->name('rotas.export');

//================================= ZOOM Page ====================================//
Route::resource('/zoom-meeting', ZoomMeetingController::class)->middleware(['auth','XSS']);
Route::any('zoommeeting/calendar', [ZoomMeetingController::class,'calender'])->name('zoommeeting.calender')->middleware(['auth','XSS']);

Route::any('/get_event_data',[ZoomMeetingController::class, 'get_event_data'])->name('get_event_data');

//================================= Slack ====================================//
Route::post('setting/slack',[EmployeesettingController::class,'slack'])->name('slack.setting');

//================================= Telegram ====================================//
Route::post('setting/telegram',[EmployeesettingController::class,'telegram'])->name('telegram.setting');

//===================================ReCaptcha====================================//
Route::post('/recaptcha-settings',[EmployeesettingController::class,'recaptchaSettingStore'])->name('recaptcha.settings.store')->middleware(['auth','XSS']);

//===================================Storage setting====================================//

Route::post('/storage-settings',[EmployeesettingController::class,'storageSettingStore'])->name('storage.setting.store')->middleware(['auth','XSS']);

//===================================Google Calendar Setting====================================//

Route::post('/google-settings',[EmployeesettingController::class,'saveGoogleCalendaSetting'])->name('setting.GoogleCalendaSetting')->middleware(['auth','XSS']);

//=================================SEO setting====================================//

Route::post('/seo-settings',[EmployeesettingController::class,'saveSeoSetting'])->name('seo.setting.store')->middleware(['auth','XSS']);

//=============================================Webhook===================================================
Route::resource('/webhook', WebhookController::class)->middleware(['auth','XSS']);
Route::post('webhooks/response/get', [WebhookController::class, 'WebhookResponse'])->name('webhooks.response.get');

//=================================Cookie setting====================================//

Route::post('/cookie-setting',[EmployeesettingController::class,'saveCookieSetting'])->name('dashboard-store')->middleware(['auth','XSS']);
Route::any('/cookie-consent',[EmployeesettingController::class,'CookieConsent'])->name('cookie-consent');

//=================================chatgpt setting====================================//




//=================================FORGOT PASSWORD==================================//
Route::any('user-reset-password/{id}', [UserController::class,'userPassword'])->name('user.reset');
Route::post('user-reset-password/{id}', [UserController::class,'userPasswordReset'])->name('user.password.update');

Route::any('employee-reset-password/{id}', [EmployeeController::class,'employeePassword'])->name('employee.reset');
Route::post('employee-reset-password/{id}', [EmployeeController::class,'employeePasswordReset'])->name('employee.password.update');

Route::any('employee-reset-pin/{id}', [EmployeeController::class,'employeePin'])->name('employee.resetPin');
Route::post('employee-reset-pin/{id}', [EmployeeController::class,'employeePinReset'])->name('employee.pin.update');

//======================================Contract Module ==================================//
Route::resource('/contract_type', ContractTypeController::class)->middleware(['auth','XSS']);
Route::resource('/contract', ContractController::class)->middleware(['auth','XSS']);
Route::post('/contract_description/{id}' , [ContractController::class,'contractdescription'])->name('contract.description');
Route::any('/contract_comments/{id}', [ContractController::class,'contract_comments'])->name('contract.comments')->middleware(['auth','XSS']);
Route::any('/contract_comments_destroy/{id}/comments/{cid}', [ContractController::class,'contract_comments_destroy'])->name('comment.destroy')->middleware(['auth','XSS']);
Route::any('/contract_notes/{id}', [ContractController::class,'contract_notes'])->name('contract.notes')->middleware(['auth','XSS']);
Route::any('/contract_notes_destroy/{id}/notes/{nid}', [ContractController::class,'contract_notes_destroy'])->name('notes.destroy')->middleware(['auth','XSS']);
Route::post('/contract_status_edit/{id}', [ContractController::class,'contract_status_edit'])->name('contract.status')->middleware(['auth','XSS']);
Route::any('/contract_attachments/{id}', [ContractController::class,'contract_attachments'])->name('contract.attachments')->middleware(['auth','XSS']);
Route::any('/contract_attachments_destroy/{id}/attachments/{aid}', [ContractController::class,'contract_attachments_destroy'])->name('attachments.destroy')->middleware(['auth','XSS']);
Route::any('/fileDownload/{id}/file/{aid}', [ContractController::class,'fileDownload'])->name('contracts.file.download')->middleware(['auth','XSS']);
// Route::get('/contract/{id}/file/{fid}', ['as' => 'contracts.file.download','uses' => 'ContractController@fileDownload',])->middleware(['auth','XSS']);
Route::get('contract/{id}/send', [ContractController::class,'emailsend'])->name('contract.send');
Route::get('contract_copy/{id}', [ContractController::class,'copycontrat'])->name('contract.copy');
Route::any('contract_copys/{id}', [ContractController::class,'copycontratdata'])->name('contract.copydata');
Route::get('contract/pdf/{id}', [ContractController::class,'contract_preview'])->name('contract.pdf');
Route::get('contract/pdf_download/{id}', [ContractController::class,'contract_download'])->name('contract.pdf.download');
Route::any('contract/signature/{id}', [ContractController::class,'signture'])->name('contract.signature');
Route::any('contract/signature_data/{id}', [ContractController::class,'signture_data'])->name('contract.signaturedata');

Route::get('create/ip', [EmployeesettingController::class, 'createIp'])->name('create.ip');
Route::post('create/ip', [EmployeesettingController::class, 'storeIp'])->name('store.ip');
Route::get('edit/ip/{id}', [EmployeesettingController::class, 'editIp'])->name('edit.ip');
Route::post('edit/ip/{id}', [EmployeesettingController::class, 'updateIp'])->name('update.ip');
Route::delete('destroy/ip/{id}', [EmployeesettingController::class, 'destroyIp'])->name('destroy.ip');

Route::resource('/timesheet', TimeSheetController::class)->middleware(['auth','XSS']);
Route::get('import/timesheet/file', [TimeSheetController::class, 'importFile'])->name('timesheet.file.import');
Route::post('import/timesheet', [TimeSheetController::class, 'import'])->name('timesheet.import');
Route::get('export/timesheet', [TimeSheetController::class, 'export'])->name('timesheet.export');
Route::get('export/timesheet/export', [ReportController::class, 'exportTimeshhetReport'])->name('timesheet.report.export');
Route::post('/timesheet/changelocation/', [TimeSheetController::class,'changelocation'])->name('timesheet.changelocation')->middleware(['auth','XSS']);

Route::get('payslip/paysalary/{id}/{date}', [PaySlipController::class, 'paysalary'])->name('payslip.paysalary')->middleware(['auth','XSS',]);
Route::get('payslip/bulk_pay_create/{date}', [PaySlipController::class, 'bulk_pay_create'])->name('payslip.bulk_pay_create')->middleware(['auth','XSS',]);
Route::post('payslip/bulkpayment/{date}', [PaySlipController::class, 'bulkpayment'])->name('payslip.bulkpayment')->middleware(['auth','XSS',]);
Route::post('payslip/search_json', [PaySlipController::class, 'search_json'])->name('payslip.search_json')->middleware(['auth','XSS',]);
Route::get('payslip/employeepayslip', [PaySlipController::class, 'employeepayslip'])->name('payslip.employeepayslip')->middleware(['auth','XSS',]);
Route::get('payslip/showemployee/{id}', [PaySlipController::class, 'showemployee'])->name('payslip.showemployee')->middleware(['auth','XSS',]);
Route::get('payslip/editemployee/{id}', [PaySlipController::class, 'editemployee'])->name('payslip.editemployee')->middleware(['auth','XSS',]);
Route::post('payslip/editemployee/{id}', [PaySlipController::class, 'updateEmployee'])->name('payslip.updateemployee')->middleware(['auth','XSS',]);
Route::get('payslip/pdf/{id}/{m}', [PaySlipController::class, 'pdf'])->name('payslip.pdf')->middleware(['auth','XSS',]);
Route::get('payslip/payslipPdf/{id}', [PaySlipController::class, 'payslipPdf'])->name('payslip.payslipPdf')->middleware(['auth','XSS',]);
Route::get('payslip/send/{id}/{m}', [PaySlipController::class, 'send'])->name('payslip.send')->middleware(['auth','XSS',]);
Route::get('payslip/delete/{id}', [PaySlipController::class, 'destroy'])->name('payslip.delete')->middleware(['auth','XSS',]);
Route::resource('payslip', PaySlipController::class)->middleware(['auth','XSS',]);
 // payslip export
Route::post('export/payslip', [PaySlipController::class, 'PayslipExport'])->name('payslip.export');

//********************************************** Notifiction **************************************************//

// Route::resource('notifiction', PaySlipController::class)->middleware(['auth','XSS',]);

Route::resource('notification-templates', NotificationTemplatesController::class)->middleware(['auth','XSS',]);
Route::get('notification-templates/{id?}/{lang?}/{type?}', [NotificationTemplatesController::class, 'index'])
->name('notification-templates.index')->middleware(['auth','XSS',]);

// Route::post('chatgptkey',[SettingController::class,'chatgptkey'])->name('settings.chatgptkey');
Route::post('/Chatgpt-setting',[EmployeesettingController::class,'chatgptkey'])->name('settings.chatgptkey')->middleware(['auth','XSS']);
Route::get('generate/{template_name}',[AiTemplateController::class,'create'])->name('generate');
Route::post('generate/keywords/{id}',[AiTemplateController::class,'getKeywords'])->name('generate.keywords');
Route::post('generate/response',[AiTemplateController::class,'AiGenerate'])->name('generate.response');

Route::get('grammar/{template}',[AiTemplateController::class,'grammar'])->name('grammar')->middleware(['auth','XSS']);;
Route::post('grammar/response',[AiTemplateController::class,'grammarProcess'])->name('grammar.response')->middleware(['auth','XSS']);;

//********************************************** iyzipay **************************************************//

Route::post('iyzipay/prepare', [IyziPayController::class, 'initiatePayment'])->name('iyzipay.payment.init')->middleware(['auth','XSS']);;
Route::post('iyzipay/callback/plan/{id}/{amount}/{coupan_code?}', [IyziPayController::class, 'iyzipayCallback'])->name('iyzipay.payment.callback');


//********************************************** sspay route  **************************************************//

Route::post('/sspay', [SspayController::class,'SspayPaymentPrepare'])->name('plan.sspaypayment');
Route::get('sspay-payment-plan/{plan_id}/{amount}/{couponCode}', [SspayController::class, 'SspayPlanGetPayment'])->middleware(['auth'])->name('plan.sspay.callback');
});


