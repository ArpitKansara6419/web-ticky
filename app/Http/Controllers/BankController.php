<?php

namespace App\Http\Controllers;

use App\DataTables\BankDataTable;
use App\Http\Requests\BankStoreRequest;
use App\Http\Requests\BankUpdateRequest;
use App\Models\Bank;
use App\Models\BankTypes;
use App\Repositories\Interface\BankRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BankController extends Controller
{
    protected $bankRepository;

    public function __construct(
        BankRepositoryInterface $bankRepository
    )
    {
        $this->bankRepository = $bankRepository;
    }

    public function index()
    {
        $bankTypes = BankTypes::all();
        return view('backend.bank.index', compact('bankTypes'));
    }

    public function store(BankStoreRequest $request)
    {
        $request->validated();

        $data = $request->processData();

        $bank = $this->bankRepository->createOrUpdate($data);

        return response()->json([
            'status' => true,
            'message' => 'Bank new created successfully.'
        ], 200);
    }

    public function update(Bank $bank, BankUpdateRequest $request)
    {
        $request->validated();

        $data = $request->processData();
        
        $bank = $this->bankRepository->createOrUpdate($data, $bank->id);

        return response()->json([
            'status' => true,
            'message' => 'Existing bank! updated successfully.'
        ], 200);
    }

    public function edit(Bank $bank)
    {
        return response()->json([
            'status' => true,
            'message' => 'Fetch bank successfully.',
            'data' => $bank
        ], 200);
    }

    public function dataTable(BankDataTable $bankDataTable)
    {
        return $bankDataTable->ajax();
    }

    public function remove(Bank $bank): JsonResponse
    {
        $bank->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully removed bank',
        ]);
    }

    public function activeInactive(Bank $bank)
    {
        if($bank->is_active == 1)
        {
            $bank->is_active = 0;
            $message = "Successfully, inactive bank";
        }else{
            $bank->is_active = 1;
            $message = "Successfully, active bank";
        }
        $bank->save();

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
