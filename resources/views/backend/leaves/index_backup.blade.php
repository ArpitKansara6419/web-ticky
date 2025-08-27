@extends('layouts.app')

@section('title', 'Engineers Leaves')

@section('content')

<div class="grid">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Engineer Leave Applications
            </h3>
            {{-- <div class="text-center">
                    <a href="{{route('engg.create')}}">
            <button
                id="drawerBtn"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                type="button">
                Add Engineer Leaves
            </button>
            </a>
        </div> --}}
    </div>

    {{-- card-body  --}}
    <div class="mt-4 relative">

        {{-- data-table  --}}


        <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4">
            <table id="leave-table" class="">
                <thead>
                    <tr class="">
                        <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Sr.
                            </span>
                        </th>
                        <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Engineer
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Form Date
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                To Date
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Notes
                            </span>
                        </th>
                       
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Leave Approval Status
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $leave )
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="capitalize px-6 py-4">

                            <div
                                class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                <img class="w-10 h-10 rounded-full border"
                                    src="{{ $leave['engineer']['profile_image'] ? asset('storage/profiles/' . $leave['engineer']['profile_image']) : asset('user_profiles/user/user.png') }}"
                                    alt="Rounded avatar">
                                <div class="">
                                    <p class="capitalize leading-4">{{ $leave['engineer']['first_name'] }}
                                        {{ $leave['engineer']['last_name'] }}</p>
                                    <p class="text-gray-400 truncate w-36">
                                        <a href="{{ route('engg.show', $leave->engineer->id ) }}" class="text-decoration hover:dark:text-gray-300 hover:text-gray-800"> {{ $leave['engineer']['engineer_code'] }} </a></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{$leave->from_date}}</td>
                        <td class="px-6 py-4">{{$leave->to_date}}</td>
                        <td class="px-6 py-4">{{$leave->note}}</td>
                        <td class="px-6 py-4">
                            <!-- {{$leave->leave_approve_status === 1 ? 'Approved' : 'Pending'}} -->
                            @if ($leave['leave_approve_status'] == 'pending')
                            <x-badge type="warning" label="Pending" class="" />
                            @elseif($leave['leave_approve_status'] == 'approved')
                            <x-badge type="success" label="Approved" class="" />
                            @elseif($leave['leave_approve_status'] == 'reject')
                            <x-badge type="info" label="Reject" class="" />
                            @endif
                            {{-- change button set in bottom --}}
                            {{-- <br> --}}
                            <button
                                class="leave-status-change-btn"
                                type="button"
                                data-leave-id="{{$leave['id']}}"
                                data-leave-status="{{$leave['leave_approve_status']}}"
                                data-modal-target="change-status-modal"
                                data-modal-toggle="change-status-modal">
                                Change
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        {{-- toast-message component --}}
        @if(session('toast'))
        <x-toast-message
            type="{{ session('toast')['type'] }}"
            message="{{ session('toast')['message'] }}"
            {{--  error="{{ session('toast')['error'] }}" --}} />
        @endif

    </div>

    <div id="change-status-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Change Leave approval status
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="change-status-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form id="leave-approval-status-form" action="{{route('leave-status.update')}}" method="POST" class="">
                    @csrf
                    <div class="grid gap-3 p-4 grid-cols-2">
                        <input type="hidden" name="leave_id" id="leave_id" value="" required="" />
                        @php
                        $status = [
                        ['name' => 'Pending' , 'value' => 'pending'],
                        ['name' => 'Approved' , 'value' => 'approved'],
                        ['name' => 'reject', 'value' => 'reject'],
                        ];
                        @endphp

                        <div class="customer">
                            <label class="text-sm dark:text-white">Status</label>
                            <x-input-dropdown
                                name="leave_approve_status"
                                id="leave_approve_status"
                                placeholder="Leave Approval Status"
                                class=""
                                :options="$status"
                                optionalLabel="name"
                                optionalValue="value"
                                value="" />
                        </div>

                        <hr class="md:col-span-2 dark:opacity-20" />

                        <div class="md:col-span-2 mt-2">
                            <button type="submit" id="btn-update-status" class="text-white w-full inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Update
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script>
        if (document.getElementById("leave-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#leave-table", {
                searchable: true,
                sortable: true,
                header: true,
                perPage: 10,
                paging: true,
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('ready!');

            // Fetch the current URL
            var currentUrl = window.location.href;

            // Check if the URL contains '/edit'
            if (currentUrl.indexOf('/edit') !== -1) {
                console.log("Helooooo");
            }
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
    <script>
        $(document).ready(function() {

            $(document).on('click', '.leave-status-change-btn', function() {
                let leave_id = $(this).data('leave-id');
                let leave_status = $(this).data('leave-status');
                $('#leave_id').val(leave_id);
                $('#leave_status').val(leave_status);
            });

            $(document).on('click', '#btn-update-status', function() {
                var leave_approve_status = $('#leave_approve_status').val();

                console.log('leave_approve_status', leave_approve_status);
                const confirmation = confirm('Are you sure you want to update status?')
                if (confirmation) {
                    $('#leave-approval-status-form').submit()
                }
            });

        })
    </script>
</div>
</div>
@endsection
