@extends('layouts.app')

@section('title', 'Notification Template')

@section('content')
<div class="">
    <div class="card">

        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Notification Templates
            </h3>
            <div class="text-center">
                <a href="{{ route('notification_template.create') }}">
                    <button id="drawerBtn"
                        class="text-white bg-primary-light-one hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                        type="button">
                        Create Template
                    </button>
                </a>
            </div>
        </div>
        {{-- card-body  --}}
        <div class="card-body relative ">

            {{-- data-table  --}}
            <div class="border  border-1 overflow-x-auto border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <table id="search-table" class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Sr. No
                                </span>
                            </th>
                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Template Name
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Title
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Sub Titlte
                                </span>
                            </th>
                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center ">
                                    Type
                                </span>
                            </th>

                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Slug
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Status
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Action
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notifications as $notification)
                        <tr>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                {{ $loop->iteration}}
                            </td>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                {{ $notification['template_name']}}
                            </td>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                {{ $notification['title']}}
                            </td>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                {{ $notification['sub_title']}}
                            </td>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                {{ $notification['type']}}
                            </td>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                {{ $notification['slug']}}
                            </td>
                            <td class="px-6 py-4">
                                @if ($notification['status'] == 0)
                                <x-badge type="danger" label="In Active" class="" />
                                @else
                                <x-badge type="success" label="Active" class="" />
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-1 ">

                                    @can($ModuleEnum::NOTIFICATION_TEMPLATE_EDIT->value)
                                    <a href="{{ route('notification_template.edit', $notification->id) }}">
                                        <button type="button" title="Edit"
                                            data-notification-id="{{ $notification->id }}"
                                            class="editBtn  text-white bg-green-400 from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600 font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </a>
                                    @endcan
                                    
                                    @can($ModuleEnum::NOTIFICATION_TEMPLATE_DELETE->value)
                                    <form id="deleteForm_{{$notification->id}}"
                                        action="{{ route('notification_template.destroy', $notification->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="Delete"
                                            class="deleteBtn text-white flex bg-red-400 from-red-400 via-red-500 to-red-500 
                   hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 
                   dark:focus:ring-red-700 font-medium rounded-lg text-sm px-5 py-2 text-center"
                                            data-notification-id="{{$notification->id}}">
                                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endcan


                                </div>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            @if (session('toast'))
            <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}" />
            @elseif($errors->any())
            <x-toast-message type="danger" message="Oops, Something went wrong, Check the form again!" />
            @endif

            <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
            <script>
                if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
                    const dataTable = new simpleDatatables.DataTable("#search-table", {
                        searchable: true,
                        sortable: true,
                        header: true,
                        perPage: 10,
                        paging: true,
                    });
                }
            </script>

            <style>
                .datatable-wrapper .datatable-top {
                    display: flex;
                    justify-content: content-between;
                    /* Align items to the start */
                }

                .custom-button {
                    order: 3;
                    /* Set order for your custom button */
                    margin-left: 10px;
                    /* Add some spacing */
                }
            </style>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script>
        $(function() {
            $('.deleteBtn').click(function() {
                var templateId = $(this).data('notification-id');
                var confirmation = confirm('Are you sure you want to delete this Template & related data?');

                if (confirmation) {
                    document.getElementById(`deleteForm_${templateId}`).submit();
                }
            });
        });
    </script>
    @endsection