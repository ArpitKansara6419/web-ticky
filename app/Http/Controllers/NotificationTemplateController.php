<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DailyWorkNote;
use App\Models\Lead;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Engineer;
use App\Models\EngineerDailyWorkExpense;
use App\Models\TicketWork;
use App\Models\WorkBreak;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\EngineerDropDownResource;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use PhpParser\Node\Expr\Cast\String_;
use Kreait\Firebase\Factory;
use PhpParser\Node\Expr\Empty_;

use function PHPUnit\Framework\returnSelf;

class NotificationTemplateController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Tempate', 'url' => "/notification_template"],
        ];
        $notifications = NotificationTemplate::orderBy('id', 'desc')->get();


        return view('backend.notification_template.index', [
            'breadcrumbs'   => $breadcrumbs,
            'notifications' => $notifications,
        ]);
    }

    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/notification-template"],
            ['name' => 'Tempate', 'url' => "/notification-template"],
            ['name' => 'Create', 'url' => ""],
        ];

        return view('backend.notification_template.form', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                "id"            => "nullable|integer|exists:notification_templates,id", // Fixed table name
                "template_name" => "required",
                "title"         => "required",
                "sub_title"     => "required",
                "slug"          => "required",
                "description"   => "required",
                "status"        => "required|in:1,0"
            ]);

            // Start a database transaction
            DB::beginTransaction();

            // Check if updating or creating a new template
            if (!empty($validatedData['id'])) {
                $template = NotificationTemplate::find($validatedData['id']);

                if ($template) {
                    $template->update($validatedData);
                    $message = 'Template updated successfully.';
                } else {
                    return redirect()->back()->with('toast', [
                        'type'    => 'warning',
                        'message' => 'Invalid template ID. Update failed.'
                    ])->withInput();
                }
            } else {
                $template = NotificationTemplate::create($validatedData);
                $message = 'Template added successfully.';
            }

            DB::commit(); // Commit transaction

            return redirect()->route('notification_template.index')->with('toast', [
                'type'    => 'success',
                'message' => $message
            ]);
        } catch (\Exception $e) {
            // Roll back transaction in case of errors
            DB::rollBack();

            return redirect()->back()->withInput()->with('toast', [
                'type'    => 'danger',
                'message' => 'Failed to save Notification Template data. Please try again.',
                'error'   => $e->getMessage()
            ]);
        }
    }


    public function edit(string $id)
    {

        try {

            $notification = NotificationTemplate::findOrFail($id);


            $breadcrumbs = [
                ['name' => 'Home', 'url' => "/notification-template"],
                ['name' => 'Notification Template', 'url' => "/notification-template"],
                ['name' => 'Edit', 'url' => ""],
            ];
            return view('backend/notification_template/form', [
                'notification' => $notification,
                'breadcrumbs' => $breadcrumbs
            ])->with('toast', [
                'type' => 'success',
                'message' => 'Notification data fetched successfully'
            ]);
        } catch (\Exception $e) {

            Log::error('Error fetching Notification details for ID ' . $id . ': ' . $e->getMessage());

            return back()->with('toast', [
                'type' => 'warning',
                'message' => 'Something went wrong while fetching data, try again'
            ]);
        }
    }

    public function destroy(string $id)
    {
        try {
            NotificationTemplate::findOrFail($id)->delete();
            session()->flash('toast', [
                'type'    => 'success',
                'message' => 'Template Removed Successfully.'
            ]);

            return redirect()->route('notification_template.index');
        } catch (\Exception $e) {

            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Something went wrong, Please Try Again.',
                'error'   => $e->getMessage()
            ]);

            return redirect()->route('notification_template.index');
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }

        $notification->is_read = true;
        $notification->save();

        // return redirect()->back()->with('success', 'Notification marked as read.');
        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read.',
            'all_count' => Notification::latest()->count(),
            'unread_count' => Notification::where('is_read', false)->latest()->count(),
            'read_count' => Notification::where('is_read', true)->latest()->count(),
        ]);
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read.',
            'all_count' => Notification::latest()->count(),
            'unread_count' => Notification::where('is_read', false)->latest()->count(),
            'read_count' => Notification::where('is_read', true)->latest()->count(),
        ]);

        // return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
