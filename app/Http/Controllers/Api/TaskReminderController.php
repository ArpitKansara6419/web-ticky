<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskReminderResponseRequest;
use App\Models\TaskReminder;
use App\Repositories\Interface\TaskReminderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskReminderController extends Controller
{
    public $taskReminderRepository;

    public function __construct(
        TaskReminderRepositoryInterface $taskReminderRepository
    )
    {
        $this->taskReminderRepository = $taskReminderRepository;        
    }

    /**
     * Response of user while updating the response of task reminder
     *
     * @param TaskReminder $task_reminder
     * @param TaskReminderResponseRequest $request
     * @return JsonResponse
     */
    public function respnseUser(TaskReminder $task_reminder, TaskReminderResponseRequest $request) : JsonResponse
    {
        $request->validated();

        $engineer_id = Auth::guard('api_engineer')->user()->id;

        if($task_reminder->engineer_id != $engineer_id)
        {
            return response()->json([
                'status' => false,
                'message' => 'Task reminder not found.'
            ], 404);
        }

        if($task_reminder->user_response)
        {
            return response()->json([
                'status' => true,
                'message' => 'Task reminder already updated.'
            ], 200);
        }

        $task_reminder->user_response = $request->input('user_response');
        $task_reminder->reason = $request->reason;
        $task_reminder->save();

        return response()->json([
            'status' => true,
            'message' => 'Task reminder replied successfully.'
        ], 200);

    }
}
