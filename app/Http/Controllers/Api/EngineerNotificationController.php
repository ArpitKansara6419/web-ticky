<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EngineerNotificationRS;
use App\Models\EngineerNotification;
use App\Repositories\Interface\EngineerNotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EngineerNotificationController extends Controller
{
    protected $engineerRepository;

    public function __construct(
        EngineerNotificationRepositoryInterface $engineerRepository
    )
    {
        $this->engineerRepository = $engineerRepository;
    }

    /**
     * Json Response
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request) : JsonResponse
    {
        $engineer_id = Auth::guard('api_engineer')->user()->id;
        $notification = $this->engineerRepository->getAll(
            start : $request->get('start', null),
            rawperpage : $request->get('rawperpage', null),
            where : [
                'engineer_id' => $engineer_id
            ]
        );
        if($notification['data']->count() == 0)
        {
            return response()->json([
                'status' => false,
                'total' => $notification['total'],
                'total_unseen' => $notification['total_unseen'],
                'message' => 'Notifications are not available.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'total' => $notification['total'],
            'total_unseen' => $notification['total_unseen'],
            'message' => 'Found notifications.',
            'data' => EngineerNotificationRS::collection($notification['data']),
        ], 200);
    }

    /**
     * Update seen unseen
     *
     * @param Request $request
     * @param [type] $engineer_notification
     * @return JsonResponse
     */
    public function updateSeen(Request $request, $engineer_notification) : JsonResponse
    {
        $engineerNotification = EngineerNotification::find($engineer_notification);

        $engineer_id = Auth::guard('api_engineer')->user()->id;

        if($engineer_notification === "all")
        {
            $this->engineerRepository->updateSeenAll($engineer_id);

            return response()->json([
                'status' => false,
                'message' => 'Notifications seen all.'
            ], 200);
        }

        if(!$engineerNotification || isset($engineerNotification->engineer_id) && $engineerNotification->engineer_id != $engineer_id)
        {
            return response()->json([
                'status' => false,
                'message' => 'Notifications not exist.'
            ], 404);
        }

        if($engineerNotification->is_seen === 0)
        {
            $engineerNotification->is_seen = 1;
            $engineerNotification->seen_at = now();
            $engineerNotification->save();
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Notification seen.'
        ], 200);

    }
}
