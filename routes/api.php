<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EngineerController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\EngineerNotificationController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\TaskReminderController;
use App\Http\Controllers\Api\TimezoneController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EngineerAuthenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function(){
    return response()->json(['message' => 'Api is working.']);
});

//Auth Api
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register',[AuthController::class, 'register']);
Route::post('/resend-otp',[AuthController::class, 'resendOTP']);
Route::post('/verify-otp',[AuthController::class, 'verifyOTP']);
Route::post('/update-profile/{id}',[AuthController::class, 'updateProfile']);
Route::post('/update-profile-image-verification/{id}',[AuthController::class, 'updateProfileImageVerification']);
Route::get('/get-profile/{id}',[AuthController::class, 'getProfile']);
Route::post('/forgot-password',[AuthController::class, 'forgotPassword']);
Route::post('reset-password',[AuthController::class, 'resetPassword']);
Route::post('change-password',[AuthController::class, 'changePassword']);
Route::middleware('auth:api_engineer')->group(function () {});

// Dashboard Api
// Route::get('/dashboard/{engineer_id}',[EngineerController::class, 'dashboardData']);

// Master Data Api
Route::get('/get-options/{type}',[EngineerController::class, 'getMasterData']);

//Eng. Document Api
Route::post('/engineer/delete-account', [EngineerController::class, 'deleteAccount']);
Route::post('/engineer/document', [EngineerController::class, 'saveDocument']);
Route::get('/engineer/documents/{user_id}', [EngineerController::class, 'listDocuments']);
Route::get('/engineer/document-details/{document_id}', [EngineerController::class, 'getDocumentDetails']);
Route::post('/engineer/document/delete/{document_id}', [EngineerController::class, 'deleteDocument']);

//Eng. Education Api
Route::post('/engineer/education', [EngineerController::class, 'saveEducation']);

// Eng. profile status
Route::get('/engineer/profile-status/{id}', [EngineerController::class, 'engineerProfileStatus']);

Route::get('/engineer/educations/{user_id}', [EngineerController::class, 'listEducations']);
Route::get('/engineer/education-details/{education_id}', [EngineerController::class, 'getEducationDetails']);
Route::post('/engineer/education/delete/{education_id}', [EngineerController::class, 'deleteEducation']);

//Eng. Vehicale Api
Route::post('/engineer/travel-detail/', [EngineerController::class, 'saveTravelDetail']);
Route::get('/engineer/travel-detail/{engineer_id}', [EngineerController::class, 'getTravelDetail']);

//Eng. Payment Detail Api
Route::post('/engineer/payment-detail/', [EngineerController::class, 'savePaymentDetail']);
Route::get('/engineer/payment-detail/{engineer_id}', [EngineerController::class, 'getPaymentDetail']);

//Eng. Language Skill Api
Route::post('/engineer/language/', [EngineerController::class, 'saveLanguageSkill']);
Route::get('/engineer/language/list/{engineer_id}', [EngineerController::class, 'listLanguageSkills']);
Route::get('/engineer/language-detail/{language_id}', [EngineerController::class, 'getLanguageSkillDetail']);
Route::post('/engineer/language/delete', [EngineerController::class, 'deleteLanguageSkill']);

// Engineer Skills API
Route::post('/engineer/skills', [EngineerController::class, 'saveSkill']);
Route::get('/engineer/skills/list/{engineer_id}', [EngineerController::class, 'listSkills']);
Route::get('/engineer/skill-detail/{skill_id}', [EngineerController::class, 'getSkillDetail']);
Route::post('/engineer/skills/delete', [EngineerController::class, 'deleteSkill']);

// Engineer Industry Experience API
Route::post('/engineer/industry-experience/', [EngineerController::class, 'saveIndustryExperience']);
Route::get('/engineer/industry-experience/list/{engineer_id}', [EngineerController::class, 'listIndustryExperience']);
Route::get('/engineer/industry-experience-detail/{experience_id}', [EngineerController::class, 'getIndustryExperienceDetail']);
Route::post('/engineer/industry-experience/delete', [EngineerController::class, 'deleteIndustryExperience']);

/// Engineer Right To Work
Route::post('/engineer/right-to-work', [EngineerController::class, 'saveRightToWork']);
Route::get('/engineer/right-to-work/{engineer_id}', [EngineerController::class, 'getRightToWorkRecord']);
Route::post('/engineer/right-to-work/reset', [EngineerController::class, 'deleteRightToWork']);

/// Engineer Certification
Route::post('/engineer/certification', [EngineerController::class, 'saveTechnicalCertification']);
Route::get('/engineer/certification/{id}', [EngineerController::class, 'getTechnicalCertificationDetail']);
Route::post('/engineer/certification/delete', [EngineerController::class, 'deleteTechnicalCertification']);
Route::get('/engineer/certifications/{user_id}', [EngineerController::class, 'listTechnicalCertifications']);

/////  Engineer Ticket 
Route::post('/engineer-ticket/list', [TicketController::class, 'ticketList']);
Route::get('/engineer-ticket/detail/{ticket_id}', [TicketController::class, 'ticketDetail']);
Route::post('/engineer-ticket/start-work', [TicketController::class, 'ticketStartWork']);
Route::post('/engineer-ticket/end-work', [TicketController::class, 'ticketEndWork']);
Route::post('/engineer/work-note', [TicketController::class, 'engWorkNote']);
Route::delete('/engineer/work-note-remove/{daily_work_note}', [TicketController::class, 'remove']);
Route::get('/engineer/work-note-list', [TicketController::class, 'engWorkNoteList']);
Route::post('/engineer-ticket/status-update', [TicketController::class, 'engTicketStatusUpdate']);
Route::post('/engineer/ticket-break/start', [TicketController::class, 'engTicketBreakStart']);
Route::post('/engineer/ticket-break/end', [TicketController::class, 'engTicketBreakEnd']);
Route::get('/engineer/ticket-break/list', [TicketController::class, 'engTicketBreakList']);

/////  Engineer Ticket Expense
Route::post('/engineer/daily-work-expense', [TicketController::class, 'engineerDailyWorExpense']);
Route::delete('/engineer/daily-work-expense/{daily_work_expense}', [TicketController::class, 'removeDailyWorkExpense']);
Route::get('/engineer/daily-work-expense/list', [TicketController::class, 'engineerDailyWorExpenseList']);

///// Engineer Leave Application
Route::get('/engineer/leaves/{id}', [EngineerController::class, 'leaveApplicationList']);
Route::post('/engineer/leave/save', [EngineerController::class, 'leaveApplication']);
Route::post('/engineer/leave/delete', [EngineerController::class, 'deleteLeaveApplication']);

Route::get('/engineer/attendance/{id}', [AttendanceController::class, 'getMonthlyAttendance']);
Route::get('/engineer/attendance/{id}/{date}', [AttendanceController::class, 'getAttendanceDetail']);
// Route::get('/engineer/holidays', [AttendanceController::class, 'holidayList']);
Route::get('/engineer/calendar/{id}/{start_date}/{end_date}', [AttendanceController::class, 'getCalendarList']);

//// Master Data
Route::prefix('master-data')->group(function () {
    Route::get('/', [MasterDataController::class, 'index']); // List all records
    Route::post('/', [MasterDataController::class, 'store']); // Create or update a record
    Route::put('/{id}', [MasterDataController::class, 'update']); // Update an existing record
    Route::delete('/{id}', [MasterDataController::class, 'destroy']); // Delete a record
});


//  Notifications

Route::get('/ticket-notifications/update-daily', [NotificationController::class, 'updateDailyNotification']);
Route::post('/ticket-notifications/send-daily', [NotificationController::class, 'sendDailyNotification']);
Route::get('/ticket-notifications/work-not-start', [NotificationController::class, 'workNotStartNotification']);

Route::get('/ticket-notifications/list/{engineerId}', [NotificationController::class, 'appNotificationsList']);


Route::get('/cron/allocate-engineer-monthly-leave', [EngineerController::class, 'allocateMonthlyLeave']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('')->middleware([EngineerAuthenticate::class])->group(function(){

    Route::get('logout', [AuthController::class, 'logout']);

    Route::post('update-device-token', [EngineerController::class, 'updateDeviceToken']);

    Route::get('/dashboard/{engineer_id}',[EngineerController::class, 'dashboardData']);

    Route::prefix('engineer-notifications')->group(function(){
        Route::get('/', [EngineerNotificationController::class, 'show']);
        Route::put('seen/{engineer_notification?}', [EngineerNotificationController::class, 'updateSeen']);
    });

    Route::prefix('task-reminder')->group(function(){
        Route::put('update/{task_reminder}', [TaskReminderController::class, 'respnseUser']);
    });


    Route::prefix('review')->group(function(){
        Route::post('store', [ReviewController::class, 'store']);
    });

    Route::prefix('engineer')->group(function(){
        Route::get('leave', [EngineerController::class, 'leave']);
    });

    /**
     * Moved to Authenticate with TOKEN
     */
    Route::get('/engineer/holidays', [AttendanceController::class, 'holidayList']);
});

Route::prefix('timezones')->group(function(){
    Route::get('/', [TimezoneController::class, 'index']);
});