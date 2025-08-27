@extends('layouts.app')

@section('title', 'Roles')

@section('styles')
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" /> -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.tailwindcss.css" />

@endsection

@section('content')

<div class="grid">

    <div class="card">


        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Permission To ({{ $role->name }})
            </h3>
            <div class="text-center">
            </div>
        </div>


        <div class="mt-4 relative">


            <form action="{{ route('role.permissionUpdate', $role->id) }}" method="POST">
                @csrf
                <div class="space-y-6">
                    @foreach ($permissions as $module => $modulePermissions)
                    <div class="border rounded p-4 shadow-sm">
                        <h3 class="text-lg font-semibold mb-2 capitalize">{{ $module }} Module</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($modulePermissions as $permission)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    class="form-checkbox text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <span class="text-gray-700">{{ ucwords(str_replace('_', ' ', $permission->name)) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Update Permissions
                    </button>
                </div>
            </form>
            <!-- <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4 " style="">
                <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="search-table">
                    <thead>
                        <tr>
                            <th>Module Name</th>
                            <th>Can View?</th>
                            <th>Can Add?</th>
                            <th>Can Edit?</th>
                            <th>Can Delete?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $row)
                            <tr>
                                <td>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> -->



        </div>




        <div id="add-role-model" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Create New Role
                        </h3>
                        <button type="button"
                            class="add_close text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="add-role-model">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form id="form_store" action="{{ route('role_store') }}" method="POST"
                        class="">
                        @csrf
                        <div class="grid gap-3 p-4 grid-cols-1">
                            <div class="mt-4">
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full"
                                    type="text"
                                    name="name" required autocomplete="new-password" />
                            </div>
                            <hr class="md:col-span-2 dark:opacity-20" />
                            <div class="md:col-span-2 mt-2">
                                <button type="submit" id=""
                                    class="text-white w-full inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Save
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="edit-role-model" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Update Role
                        </h3>
                        <button type="button"
                            class="edit_close text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="edit-role-model">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form id="form_update" action="#" method="PUT"
                        class="">
                        @csrf
                        <input type="hidden" name="role_id" id="role_id">
                        <div class="grid gap-3 p-4 grid-cols-1">
                            <div class="mt-4">
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="edit_name" class="block mt-1 w-full"
                                    type="text"
                                    name="name" required autocomplete="new-password" />
                            </div>
                            <hr class="md:col-span-2 dark:opacity-20" />
                            <div class="md:col-span-2 mt-2">
                                <button type="submit" id="btn-update-role"
                                    class="text-white w-full inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Update
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection

@section('scripts')

@endsection