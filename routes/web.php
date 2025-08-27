<?php

use App\Enums\ModuleEnum;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EngineerController as ApiEngineerController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EngineerController as ControllersEngineerController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\Frontend\EngineerController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\ChangePassword;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\CustomerPayoutController;
use App\Http\Controllers\CustomerTemplateController;
use App\Http\Controllers\CustomTemplateController;
use App\Http\Controllers\LeaveAllotController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationTemplateController;
use App\Http\Controllers\EngineerLeaveController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SystemUserController;
use App\Models\CustomerPayout;
use App\Models\Engineer;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    return 'Cache cleared';
});

Route::get('/dev-storage-link', function () {
    Artisan::call('storage:link');
    Artisan::call('config:cache');
    return 'Storage linked succesfully';
});

Route::get('/dashboard', [DashboardController::class, 'getDashboard'])->middleware(['auth', 'verified', 'permission:'.ModuleEnum::DASHBOARD_ACCESS->value])->name('dashboard');
// Route::get('/calendar/events', [DashboardController::class, 'getEvents'])->name('events.get');
Route::get('/events/get', [DashboardController::class, 'getEvents'])->name('events.get');
Route::get('/ticket-calendar-events/get', [DashboardController::class, 'getMonthlyTickets'])->name('calendar-ticket-events.get');

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'getDashboard'])->middleware('permission:'.ModuleEnum::DASHBOARD_ACCESS->value);

    Route::post('filter-dashboard-stastics', [DashboardController::class, 'filterDashboardStastics'])->name('filter-dashboard-stastics');

    Route::post('notification-lazy-load', [DashboardController::class, 'notificationLazyLoad'])->name('notificationLazyLoad');

    Route::get('/test-notificaiton', [ControllersEngineerController::class, 'testPushNotification']);

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password-change.store');

    Route::prefix('customer')->group(function(){
        Route::get('/', [CustomerController::class, 'index'])
            ->name('customer.index')->middleware('permission:'.ModuleEnum::CUSTOMER_LIST->value);

        Route::post('ajax-data', [CustomerController::class, 'ajaxData'])
            ->name('customer.dataTable');

        Route::get('create', [CustomerController::class, 'create'])
            ->name('customer.create')->middleware('permission:'.ModuleEnum::CUSTOMER_CREATE->value);

        Route::post('store', [CustomerController::class, 'store'])
            ->name('customer.store')->middleware('permission:'.ModuleEnum::CUSTOMER_CREATE->value);

        Route::get('{customer}', [CustomerController::class, 'show'])
            ->name('customer.show')->middleware('permission:'.ModuleEnum::CUSTOMER_LIST->value);

        Route::get('{customer}/edit', [CustomerController::class, 'edit'])
            ->name('customer.edit')->middleware('permission:'.ModuleEnum::CUSTOMER_EDIT->value);

        Route::put('{customer}', [CustomerController::class, 'update'])
            ->name('customer.update')->middleware('permission:'.ModuleEnum::CUSTOMER_EDIT->value);

        Route::delete('{customer}', [CustomerController::class, 'destroy'])
            ->name('customer.destroy')->middleware('permission:'.ModuleEnum::CUSTOMER_DELETE->value);
    });
    Route::resource('customer', CustomerController::class);
    
    Route::delete('/document-delete/', [CustomerController::class, 'deleteDoc'])->name('customer.delete-doc');
    Route::get('/customer/lead/{id}', [CustomerController::class, 'getLeadList'])->name('customer.lead')->middleware(['permission:'.ModuleEnum::CUSTOMER_LEAD->value]);
    Route::get('/get-customer-details/{id}', [CustomerController::class, 'getCustomerDetails'])->name('customer.details');

    Route::prefix('customer-invoice')->group(function(){
        Route::get('/', [CustomerPayoutController::class, 'index'])
            ->name('customer-invoice.index')->middleware('permission:'.ModuleEnum::CUSTOMER_INVOICES_LIST->value);
        Route::get('{customer}', [CustomerPayoutController::class, 'show'])
            ->name('customer-invoice.show')->middleware('permission:'.ModuleEnum::CUSTOMER_INVOICE_RECEIVABLE_DETAIL->value);
    });
    Route::resource('customer-invoice', CustomerPayoutController::class);
    Route::post('/invoice-status-update', [CustomerPayoutController::class, 'invoiceStatusUpdate'])->name('invoice-status.paid');
    Route::get('/invoicing-redirection', [CustomerPayoutController::class, 'customerInvoiceRedirection'])->name('invoicing.redirect');

    Route::resource('admin', AdminController::class);

    



    Route::get('adminDashboard', [DashboardController::class, 'getDashboard'])->name('dash.index');

    Route::resource('engg', ControllersEngineerController::class);
    Route::prefix('engg')->group(function(){
        Route::get('/', [ControllersEngineerController::class, 'index'])
            ->name('engg.index')->middleware('permission:'.ModuleEnum::ENGINEER_LIST->value);
        Route::get('{engg}', [ControllersEngineerController::class, 'show'])
            ->name('engg.show')->middleware('permission:'.ModuleEnum::ENGINEER_DETAIL->value);
        Route::delete('{engg}', [ControllersEngineerController::class, 'destroy'])
            ->name('engg.destroy')->middleware('permission:'.ModuleEnum::ENGINEER_DELETE->value);
        Route::get('timezone/{lead}', [ControllersEngineerController::class, 'engineerByTimezone'])
            ->name('engg.engineerByTimezone');
    });
    Route::get('/engineers/ajax-pagination', [ControllersEngineerController::class, 'ajaxPagination']);
    Route::get('engg-edit', [ControllersEngineerController::class, 'engEdit'])->name('engg.editPage');
    Route::resource('lead', LeadController::class);
    Route::prefix('lead')->group(function(){
        Route::get('/', [LeadController::class, 'index'])
            ->name('lead.index')->middleware('permission:'.ModuleEnum::LEAD_LIST->value);
        Route::get('create', [LeadController::class, 'create'])
            ->name('lead.create')->middleware('permission:'.ModuleEnum::LEAD_CREATE->value);
        Route::post('datatable', [LeadController::class, 'dataTable'])
            ->name('lead.dataTable');
    });
    Route::get('/get-lead-details/{id}', [LeadController::class, 'getLeadDetails'])->name('lead.details');
    Route::post('lead-statu-update', [LeadController::class, 'ChangeLeadStatus'])->name('lead-status.update');
    Route::post('ticket-eng-update', [TicketController::class, 'ChangeTicketEng'])->name('engineer-change.update');

    // Ticket
    Route::resource('ticket', TicketController::class);
    Route::prefix('ticket')->group(function(){
        Route::get('/', [TicketController::class, 'index'])
            ->name('ticket.index')->middleware('permission:'.ModuleEnum::TICKET_LIST->value);
        Route::get('create', [TicketController::class, 'create'])
            ->name('ticket.create')->middleware('permission:'.ModuleEnum::TICKET_CREATE->value);
        Route::get('{ticket}/edit', [TicketController::class, 'edit'])
            ->name('ticket.edit')->middleware('permission:'.ModuleEnum::TICKET_EDIT->value);
        Route::get('{ticket}', [TicketController::class, 'show'])
            ->name('ticket.show')->middleware('permission:'.ModuleEnum::TICKET_DETAIL->value);
        Route::post('datatable', [TicketController::class, 'dataTable'])
            ->name('ticket.dataTable');
        Route::get('edit-ticket-work/{ticketWork}', [TicketController::class, 'editTicketWork'])
            ->name('editTicketWork');
        Route::post('update-ticket-work/{ticketWork}', [TicketController::class, 'updateTicketWorkAdjust'])
            ->name('updateTicketWorkAdjust');
        Route::post('store-ticket-work/{ticket}', [TicketController::class, 'storeNewWorkTicket'])
            ->name('storeNewWorkTicket');
            
    });
    Route::get('overtime', [TicketController::class, 'OvertimeIndex'])->name('overtime.index');
    Route::post('overtime-status-update', [TicketController::class, 'overtimeStatusUpdate'])->name('overtime-aproval-status.update');
    Route::get("/get-work-notes/{id}", [TicketController::class, 'getWorkNotes'])->name('getWorknotes');
    Route::post('work-status-update', [TicketController::class, 'workStatusUpdate']);
    Route::get('/ticket-fetch/fetch-popup', [TicketController::class, 'fetchPopup'])->name('ticket.fetchPopup');
    Route::get('/eng-ticket-fetch/fetch-popup', [PayoutController::class, 'fetchPopup'])->name('engTicket.fetchPopup');

    Route::post('ticket-work/updateAmount/', [TicketController::class, 'updateAmount'])->name('updateAmount');

    // Master Data
    Route::resource('master', MasterDataController::class);

    // Holiday
    Route::resource('holiday', HolidayController::class);
    Route::prefix('holiday')->group(function(){
        Route::get('', [HolidayController::class, 'index'])
            ->name('holiday.index')->middleware('permission:'.ModuleEnum::SETTING_HOLIDAY_LIST->value);

        Route::get('create', [HolidayController::class, 'create'])
            ->name('holiday.create')->middleware('permission:'.ModuleEnum::SETTING_HOLIDAY_CREATE->value);

        Route::post('store', [HolidayController::class, 'store'])
            ->name('holiday.store')->middleware('permission:'.ModuleEnum::SETTING_HOLIDAY_CREATE->value);


        Route::get('{holiday}/edit', [HolidayController::class, 'edit'])
            ->name('holiday.edit')->middleware('permission:'.ModuleEnum::SETTING_HOLIDAY_EDIT->value);

        Route::put('{holiday}', [HolidayController::class, 'update'])
            ->name('holiday.update')->middleware('permission:'.ModuleEnum::SETTING_HOLIDAY_EDIT->value);

        Route::delete('{holiday}', [HolidayController::class, 'destroy'])
            ->name('holiday.destroy')->middleware('permission:'.ModuleEnum::SETTING_HOLIDAY_DELETE->value);
        
        Route::post('datatable', [HolidayController::class, 'dataTable'])
            ->name('holiday.dataTable');
        
        Route::get('countries/list', [HolidayController::class, 'getCountries'])
            ->name('holiday.countries');

        Route::post('sync', [HolidayController::class, 'sync'])
            ->name('holiday.sync');

        Route::post('active-inactive/{holiday}', [HolidayController::class, 'activeInactive'])
            ->name('holiday.activeInactive');
    });

    Route::prefix('engineer')->group(function(){
        Route::post('datatable', [ControllersEngineerController::class, 'dataTable'])
            ->name('engineer.dataTable');
    });

    // Attendance
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index')->middleware('permission:'.ModuleEnum::ATTENDANCE_LIST->value);
    Route::get('/attendance/fetch-table', [AttendanceController::class, 'fetchTable'])->name('attendance.fetchTable');
    Route::get('/attendance/fetch-popup', [AttendanceController::class, 'fetchPopup'])->name('attendance.fetchPopup');

    // Employee Leave Approval

    Route::get('leaves', [EngineerLeaveController::class, 'dashboard'])->name('leave.dashboard')->middleware('permission:'.ModuleEnum::LEAVES_LIST->value);
    Route::post('leaves-status-update', [EngineerLeaveController::class, 'updateLeaveStatus'])->name('leave-status.update');
    Route::get('engineer-leave/balance/{engineerId}', [EngineerLeaveController::class, 'getEngineerLeaveBalance'])->name('get.leave-balance');
    Route::post('/leave/{id}/approve', [EngineerLeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{id}/reject', [EngineerLeaveController::class, 'reject'])->name('leave.reject');
    Route::post('engineer-leave/datatable', [EngineerLeaveController::class, 'dataTable'])
            ->name('engineerLeave.dataTable');

    // Route::get('leaves', [AttendanceController::class, 'leaveIndex'])->name('leaves.index');
    // Route::post('leave-status-update', [AttendanceController::class, 'updateLeaveStatus'])->name('leave-aproval-status.update');

    Route::get('leave-allot', [AttendanceController::class, 'leaveAllot'])->name('leave-allot');
    Route::post('leave-allot', [AttendanceController::class, 'storeLeaveAllot'])->name('leave-allot.store');

    Route::post('engg-details', [ControllersEngineerController::class, 'engJobDetailsUpdate'])->name('engineer-jobdetails.update');

    // Payout
    Route::get('engineer-payouts', [PayoutController::class, 'allEngPayout'])->name('alleng.payout')->middleware('permission:'.ModuleEnum::EngineerPayouts_LIST->value);
    Route::get('/engineer-monthly-payouts', [PayoutController::class, 'engineerMonthlyPayouts'])->name('eng-monthly.payout');
    Route::prefix('payout')->group(function(){
        Route::get('/', [PayoutController::class, 'index'])
            ->name('payout.index')->middleware('permission:'.ModuleEnum::EngineerPayouts_PAYSLIPS->value);
        Route::get('/{id}', [PayoutController::class, 'show'])
            ->name('payout.show')->middleware('permission:'.ModuleEnum::ENGINEER_PAYOUT->value);
    });
    Route::resource('payout', PayoutController::class);
    Route::get('/engineer-ticket-payout', [PayoutController::class, 'engineerTicketPayout'])->name('engineer-ticket-payout.redirect');
    Route::post('payout/daily-payout', [PayoutController::class, 'dailyPayoutUpdate'])->name('daily-payout.update');
    Route::post('customer/daily-payout', [CustomerPayoutController::class, 'dailyPayoutUpdate'])->name('customer-daily-payout.update');
    Route::get('all-customer-payout', [CustomerPayoutController::class, 'allCustomerPayout'])->name('all-customer.payout')->middleware('permission:'.ModuleEnum::CUSTOMER_RECEIVABLE_LIST->value);
    Route::post('/all-customer-payout-filter-data', [CustomerPayoutController::class, 'customerPayoutFilter'])->name('all-customer-payout.filter');

    // ticket details for payout
    Route::get('/eng-paypit/ticket-details', [PayoutController::class, 'ticketDetails'])->name('payoutTicketDetials');

    Route::delete('customer-payout/delete/{id}', [CustomerPayoutController::class, 'destroy'])->name('customer-payout.destroy');

    Route::get('payout/get-ticket-data/{id}', [PayoutController::class, 'getTicketData'])->name('payout.ticket-data');
    Route::get('customer/get-ticket-data/{id}', [PayoutController::class, 'getCustomerTicketData'])->name('customer.payout-data');
    Route::post('update-payable-amount', [PayoutController::class, 'updatePayableAmount'])->name('update-payable-amount');
    Route::post('update-eng-payable-amount', [PayoutController::class, 'updateEngPayableAmount'])->name('update-eng-payable-amount');
    Route::get('/customer-payouts/fetch-popup', [CustomerPayoutController::class, 'fetchPopup'])->name('customer-payout.fetchPopup');

    // Eng salary Slip
    Route::get('engineer-pay-slip/{engineerId}/{payoutId}', [ControllersEngineerController::class, 'EngineerSlip'])->name('engineer.slip');
    Route::get('engineer-pay-slip-test', [ControllersEngineerController::class, 'EngineerSlipTest'])->name('engineer.slipTest');
    Route::post('/save-zus-pit', [PayoutController::class, 'saveZusPit']);


    //Eng Work Slip
    Route::get('engineer-work-slip/{ticket_works_id}', [ControllersEngineerController::class, 'EngineerWorkSlip'])->name('engineer-work.slip');
    Route::get('engineer-work-slip-test', [ControllersEngineerController::class, 'EngineerWorkSlipTest'])->name('engineer-work.slipTest');

    //Client Invoice
    Route::get('client-invoice/{id}', [CustomerController::class, 'clientInvoice'])->name('client.invoice');
    Route::get('client-invoice-test', [CustomerController::class, 'clientInvoiceTest'])->name('client.invoiceTest');

    //Client Breakup
    Route::get('client-breakup/{id}', [CustomerController::class, 'clientBreakup'])->name('client.breakup');
    Route::get('client-breakup-test', [CustomerController::class, 'clientBreakupTest'])->name('client.breakupTest');

    Route::get('ticket-invoice/{id}', [TicketController::class, 'ticketInvoice'])->name('ticket.invoice');
    Route::get('payout-slip/{id}', [PayoutController::class, 'payoutSlip'])->name('payout.slip');
    Route::get('customer/payout-slip/{id}', [PayoutController::class, 'customerPayoutSlip'])->name('customer-payout.slip');

    Route::get('/engineer/daily-work-expense/{workId}', [TicketController::class, 'getWorkExpense']);
    Route::post('/other-pay', [TicketController::class, 'otherpayUpdate'])->name('other-pay.update');

    Route::get('notification/exp-certi', [NotificationController::class, 'getExpCertificate']);
    Route::get('notification/exp-doc', [NotificationController::class, 'getExpDoc']);
    Route::get('notification/right-to-work', [NotificationController::class, 'getExpRightToWork']);
    Route::get('notification/brthday-wish', [NotificationController::class, 'sendBirthdayNotifications']);
    Route::get('notification/work-anniversary-wish', [NotificationController::class, 'sendWorkAnniversaryNotifications']);
    Route::post('/get-ticket-chart-data', [DashboardController::class, 'getTicketChartData'])->name('getTicketChartData');

    //  Notification templates
    Route::get('/notification-template', [NotificationTemplateController::class, 'index',])->name('notification_template.index')->middleware('permission:'.ModuleEnum::NOTIFICATION_TEMPLATE_LIST->value);
    Route::get('/notification-template/create', [NotificationTemplateController::class, 'create',])->name('notification_template.create')->middleware('permission:'.ModuleEnum::NOTIFICATION_TEMPLATE_CREATE->value);
    Route::post('/notification-template', [NotificationTemplateController::class, 'store',])->name('notification_template.store')->middleware('permission:'.ModuleEnum::NOTIFICATION_TEMPLATE_CREATE->value);
    Route::get('/notification-template/edit/{id}', [NotificationTemplateController::class, 'edit',])->name('notification_template.edit')->middleware('permission:'.ModuleEnum::NOTIFICATION_TEMPLATE_EDIT->value);
    Route::delete('/notification-template/delete/{id}', [NotificationTemplateController::class, 'destroy',])->name('notification_template.destroy')->middleware('permission:'.ModuleEnum::NOTIFICATION_TEMPLATE_DELETE->value);

    Route::get('/notifications/mark-as-read/{notificationId}', [NotificationTemplateController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('/notifications/mark-all-as-read', [NotificationTemplateController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // custom template  

    Route::get('/custom-template/create', [CustomTemplateController::class, 'create',])->name('custom_template.create')->middleware('permission:'.ModuleEnum::CUSTOM_NOTIFICATION_CREATE->value);
    Route::post('/custom-template/store', [CustomTemplateController::class, 'store',])->name('custom_template.store')->middleware('permission:'.ModuleEnum::CUSTOM_NOTIFICATION_CREATE->value);

    ///////  Testing Routes  ////////////////////////
    Route::get('/test/ticket/end-work', [TicketController::class, 'testTicketEndWork',]);

    /**
     * Roles Routes
     */
    Route::prefix('role')->group(function(){
        Route::get('/', [RoleController::class, 'index'])->name('role_list')->middleware('permission:'.ModuleEnum::SETTING_ROLE_LIST->value);
        Route::get('edit/{role}', [RoleController::class, 'edit'])->name('role_edit');

        Route::get('permission/{role}', [RoleController::class, 'permissionView'])->name('role.permissionView')->middleware('permission:'.ModuleEnum::SETTING_ROLE_PERMISSION->value);
        Route::post('permission/update/{role}', [RoleController::class, 'permissionUpdate'])->name('role.permissionUpdate')->middleware('permission:'.ModuleEnum::SETTING_ROLE_PERMISSION->value);

        Route::get('ajax-datatable', [RoleController::class, 'dataTable'])->name('role_ajax_data_table');

        Route::put('update/{role}', [RoleController::class, 'update'])->name('role_update')->middleware('permission:'.ModuleEnum::SETTING_ROLE_PERMISSION->value);
        Route::post('store', [RoleController::class, 'store'])->name('role_store')->middleware('permission:'.ModuleEnum::SETTING_ROLE_CREATE->value);

        Route::delete('remove/{role}', [RoleController::class, 'remove'])->name('role_remove')->middleware('permission:'.ModuleEnum::SETTING_ROLE_DELETE->value);
    });

    /**
     * System User's Routes
     */
    Route::prefix('system-users')->group(function(){
        Route::get('/', [SystemUserController::class, 'index'])->name('user_list')->middleware('permission:'.ModuleEnum::SETTING_SYSTEM_USERS_LIST->value);
        Route::get('edit/{user}', [SystemUserController::class, 'edit'])->name('user_edit')->middleware('permission:'.ModuleEnum::SETTING_SYSTEM_USERS_EDIT->value);

        Route::get('ajax-datatable', [SystemUserController::class, 'dataTable'])->name('user_ajax_data_table');

        Route::put('update/{user}', [SystemUserController::class, 'update'])->name('user_update')->middleware('permission:'.ModuleEnum::SETTING_SYSTEM_USERS_EDIT->value);
        Route::post('store', [SystemUserController::class, 'store'])->name('user_store')->middleware('permission:'.ModuleEnum::SETTING_SYSTEM_USERS_CREATE->value);

        Route::delete('remove/{user}', [SystemUserController::class, 'remove'])->name('user_remove')->middleware('permission:'.ModuleEnum::SETTING_SYSTEM_USERS_DELETE->value);
    });

    /**
     * Bank's Routes
     */
    Route::prefix('banks')->group(function(){
        Route::get('/', [BankController::class, 'index'])->name('bank_list');
        Route::get('edit/{bank}', [BankController::class, 'edit'])->name('bank_edit');

        Route::get('ajax-datatable', [BankController::class, 'dataTable'])->name('bank_ajax_data_table');

        Route::put('update/{bank}', [BankController::class, 'update'])->name('bank_update');
        Route::post('store', [BankController::class, 'store'])->name('bank_store');

        Route::delete('remove/{bank}', [BankController::class, 'remove'])->name('bank_remove');

        Route::post('active-inactive/{bank}', [BankController::class, 'activeInactive'])->name('bank_active_inactive');
    });
});

////////////////   Cron Jobs  //////////////////////

// Last day of month
Route::get('/job-monthly-leave-allotment', [CronJobController::class, 'monthlyLeaveAllotment']);

// Every Minute
Route::get('/job-ticket-start-reminder-notification', [CronJobController::class, 'ticketStartReminder']);

Route::get('/noti-test', [CronJobController::class, 'notificationTest']);

Route::get('/job-ticket-start-notifications-every-minute', [CronJobController::class, 'ticketNotificationsEveryMinute']);

// Every 15 days
Route::get('/job-guidline-reminder-notification', [CronJobController::class, 'guidelinesReminders']);

Route::get('/job-apply-planed-leave', [CronJobController::class, 'applyPlanLeave']);

// Every minute
Route::get('/job-ticket-inprogress-work-update', [CronJobController::class, 'inprogressTicketWorkUpdate']);

Route::get('/job-monthly-payout-status-update', [CronJobController::class, 'monthlyPayoutStatusUpdate']);


require __DIR__ . '/auth.php';

Route::get('/manipulate-tickets-if-no-action-taken', [CronController::class, 'manipulateTicketsIfNoActionTaken']);

Route::get('/offerred-tickets-cron', [CronController::class, 'offerredTicketNotificationsCron']);

Route::get('/dispatch-task-reminder', [CronController::class, 'dispatchTaskReminder']);
Route::get('/close-hold-reminder-before-day-end', [CronController::class, 'tickeCloseHoldReminder']);
Route::get('/work-update-reminder', [CronController::class, 'inProgressTicketReminderForWorkUpdate']);