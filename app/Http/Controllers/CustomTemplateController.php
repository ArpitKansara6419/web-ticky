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
use App\Models\CustomTemplate;
use App\Models\NotificationTemplate;
use Google\Cloud\Storage\Notification;
use PhpParser\Node\Expr\Cast\String_;
use Kreait\Firebase\Factory;
use PhpParser\Node\Expr\Empty_;

use function PHPUnit\Framework\returnSelf;

class CustomTemplateController extends Controller
{
    // public function index()
    // {
    //     $breadcrumbs = [
    //         ['name' => 'Home', 'url' => ""],
    //         ['name' => 'Custom Tempate', 'url' => "/notification_template"],
    //     ];
    //     $notifications = NotificationTemplate::orderBy('id', 'desc')->get();


    //     return view('backend.custom_templates.index', [
    //         'breadcrumbs'   => $breadcrumbs,
    //         'notifications' => $notifications,
    //     ]);
    // }

    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Tempate', 'url' => "/notification-template"],
            ['name' => 'Create', 'url' => ""],
        ];

        $engineers = Engineer::get();
        $templates = NotificationTemplate::get();
        return view('backend.custom_template.form', [
            'breadcrumbs' => $breadcrumbs,
            'engineers'   => $engineers,
            'templates'   => $templates,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            // Validate input data
            $validatedData = $request->validate([
                "id"                     => "nullable|integer|exists:custom_template,id",
                "job_type"               => "required|array",
                "engineers"              => "nullable|array", // Expecting an array of engineer IDs
                "notification_template"  => "nullable|string",
                "notification_title"     => "nullable|string",
                "notification_subtitle"  => "nullable|string",
                "notification_text"      => "nullable|string",
                "notification_interval"  => "nullable|string",
                "time"                   => "nullable|string",
                "month"                  => "nullable|integer",
                "weekday"                => "nullable|string",
                "day"                    => "nullable|integer",
                "custom_date"            => "nullable|date",
                "end_date"               => "nullable|date",
            ]);

            // Convert engineers array to JSON if provided
            if (isset($validatedData['engineers'])) {
                $validatedData['engineers'] = json_encode($validatedData['engineers']);
            }

            if (isset($validatedData['job_type'])) {
                $validatedData['job_type'] = json_encode($validatedData['job_type']);
            }

            // Start a database transaction
            DB::beginTransaction();

            // Check if updating or creating a new template
            if (!empty($validatedData['id'])) {
                $template = CustomTemplate::find($validatedData['id']);

                if ($template) {
                    $template->update($validatedData);
                    $message = 'Custom Template updated successfully.';
                } else {
                    return redirect()->back()->with('toast', [
                        'type'    => 'warning',
                        'message' => 'Invalid template ID. Update failed.'
                    ])->withInput();
                }
            } else {
                $template = CustomTemplate::create($validatedData);
                $message = 'Custom Template added successfully.';
            }

            DB::commit(); // Commit transaction

            return redirect()->route('custom_template.create')->with('toast', [
                'type'    => 'success',
                'message' => $message
            ]);
        } catch (\Exception $e) {
            // Roll back transaction in case of errors
            DB::rollBack();

            return redirect()->back()->withInput()->with('toast', [
                'type'    => 'danger',
                'message' => 'Failed to save Custom Template data. Please try again.',
                'error'   => $e->getMessage()
            ]);
        }
    }
}
