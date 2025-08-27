<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SystemUserController extends Controller
{
    protected $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return view('backend.system-users.index');
    }

    public function store(UserStoreRequest $request)
    {
        $request->validated();

        $data = $request->processData();
        
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->createOrUpdate($data);

        $user->syncRoles($request->role_id);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully.'
        ], 200);
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $request->validated();

        $data = $request->processData();
        
        if($request->password)
        {
            $data['password'] = Hash::make($data['password']);
        }else{
            unset($data['password']);
        }
        
        $user = $this->userRepository->createOrUpdate($data, $user->id);

        $user->syncRoles($request->role_id);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully.'
        ], 200);
    }

    public function edit(User $user)
    {
        $userRole = $user->roles->first();
        return response()->json([
            'status' => true,
            'message' => 'Fetch user successfully.',
            'data' => $user,
            'user_role' => $userRole
        ], 200);
    }

    public function dataTable(UserDataTable $userDataTable)
    {
        return $userDataTable->ajax();
    }

    public function remove(User $user): JsonResponse
    {
        $user->delete();

        // $user->removeRole('admin');

        return response()->json([
            'status' => true,
            'message' => 'Successfully removed user',
        ]);
    }
}
