<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewStoreRequest;
use App\Models\Review;
use App\Models\Ticket;
use App\Repositories\Interface\ReviewRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected $reviewRepository;

    public function __construct(
        ReviewRepositoryInterface $reviewRepository
    )
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function store(ReviewStoreRequest $request)
    {
        $request->validated();

        $engineer_id = Auth::guard('api_engineer')->user()->id;

        $check = Ticket::where('id', $request->ticket_id)
            ->where('engineer_id', $engineer_id)
            ->first();

        if(!$check)
        {
            return response()->json([
                'status' => false,
                'message' => 'Ticket number is invalid.'
            ], 404);
        }

        $exist = Review::where('ticket_id', $request->ticket_id)
            ->where('engineer_id', $engineer_id)
            ->where('customer_id', $check->customer_id)
            ->first();

        if($exist)
        {
            return response()->json([
                'status' => false,
                'message' => 'Review already given.'
            ], 403);
        }

        $data = [
            'engineer_id' => $check->engineer_id,
            'customer_id' => $check->customer_id,
            'ticket_id' => $check->id,
            'ratings' => $request->ratings,
            'description' => $request->description,
        ];

        $this->reviewRepository->createOrUpdate($data);

        return response()->json([
            'status' => true,
            'message' => 'Thank you, for submitting the review.'
        ], 200);
    }
}
