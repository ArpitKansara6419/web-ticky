<?php

namespace App\Http\Controllers;

use App\DataTables\RoleDataTable;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Repositories\Interface\RoleRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleRepository;

    public function __construct(
        RoleRepositoryInterface $roleRepository
    ){
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        return view('backend.role.index');
    }

    public function store(RoleStoreRequest $request)
    {
        $request->validated();

        $data = $request->processData();
        // dd($data);

        $this->roleRepository->createOrUpdate($data);

        return response()->json([
            'status' => true,
            'message' => 'Role created successfully.'
        ], 200);
    }

    public function dataTable(RoleDataTable $roleDataTable)
    {
        return $roleDataTable->ajax();
    }

    public function edit(Role $role)
    {
        return response()->json([
            'status' => true,
            'message' => 'Fetch roles successfully.',
            'data' => $role
        ], 200);
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        $request->validated();

        $data = $request->processData();

        $this->roleRepository->createOrUpdate($data, $role->id);

        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully.'
        ], 200);
    }

    public function remove(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully removed role',
        ]);
    }

    public function permissionView(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('_', $permission->name)[0]; // Group by first word (e.g., "category")
        });
        return view('backend.role.permission', compact('role', 'permissions'));
    }

    public function permissionUpdate(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Permissions updated successfully.');
    }
}
