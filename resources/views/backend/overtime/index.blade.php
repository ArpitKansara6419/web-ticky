@extends('layouts.app')

@section('title', 'Overtime Index')

@section('content')

    <div class="grid">

        <div class="card">

            {{-- card-header  --}}
            <div class="card-header flex justify-between items-center">
                <h3 class="font-extrabold">
                    Engineers Overtime
                </h3>
                {{-- <div class="text-center">
                    <a href="{{route('engg.create')}}">
            <button
                id="drawerBtn"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                type="button">
                Add Engineer Overtime
            </button>
            </a>
        </div> --}}
            </div>

            {{-- card-body  --}}
            <div class="mt-4 relative">

                {{-- data-table  --}}


                <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4" style="">
                    <table id="overtime-table" class="">
                        <thead>
                            <tr class="">
                                <th class="bg-blue-100  dark:bg-gray-900">
                                    <span class="flex items-center">
                                        Sr.
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:bg-gray-900">
                                    <span class="flex items-center px-8">
                                        Ticket Id
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:bg-gray-900">
                                    <span class="flex items-center">
                                        Engineer
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:bg-gray-900">
                                    <span class="flex items-center">
                                        Customer
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:bg-gray-900">
                                    <span class="flex items-center">
                                        Date
                                    </span>
                                </th>
                                <!-- <th class="bg-blue-100 dark:bg-gray-900">
                                                        <span class="flex items-center">
                                                            Work End Date
                                                        </span>
                                                    </th> -->
                                <th class="bg-blue-100 dark:bg-gray-900">
                                    <span class="flex items-center">
                                        Start Time
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:bg-gray-900">
                                    <span class="flex items-center">
                                        End Time
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:bg-gray-900">
                                    <span class="flex items-center">
                                        Total Hours
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:bg-gray-900">
                                    <span class="flex items-center">
                                        Status
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:bg-gray-900">
                                    <span class="flex items-center">
                                        Approval
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ticket_works as $ticket_work)
                                <tr>
                                    <td> {{ $loop->iteration }}</td>
                                    <td> <a href="{{ route('ticket.show', $ticket_work->ticket->id) }}"
                                            class="text-decoration hover:dark:text-gray-300 hover:text-gray-800">
                                            {{ $ticket_work->ticket?->ticket_code }}</a></td>
                                    <td class="capitalize">

                                        <div
                                            class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                            <img class="w-10 h-10 rounded-full border"
                                                src="{{ $ticket_work['engineer']['profile_image'] ? asset('storage/' . $ticket_work['engineer']['profile_image']) : asset('user_profiles/user/user.png') }}"
                                                alt="Rounded avatar">
                                            <div class="">
                                                <p class="capitalize leading-4">{{ $ticket_work['engineer']['first_name'] }}
                                                    {{ $ticket_work['engineer']['last_name'] }}</p>
                                                <p class="text-gray-400 truncate w-36">
                                                    <a href="{{ route('engg.show', $ticket_work->engineer->id) }}"
                                                        class="text-decoration hover:dark:text-gray-300 hover:text-gray-800">
                                                        {{ $ticket_work['engineer']['engineer_code'] }} </a>
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="capitalize">

                                        <div
                                            class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                            <img class="w-10 h-10 rounded-full border"
                                                src="{{ $ticket_work->ticket->customer->profile_image ? asset('storage/profiles/' . $ticket_work->ticket['customer']['profile_image']) : asset('user_profiles/user/user.png') }}"
                                                alt="Rounded avatar">
                                            <div class="">
                                                <p class="capitalize leading-4">{{ $ticket_work->ticket->customer->name }}
                                                </p>
                                                <p class="text-gray-400 truncate w-36">
                                                    <a href="{{ route('customer.show', $ticket_work->ticket->customer->id) }}"
                                                        class="text-decoration hover:dark:text-gray-300 hover:text-gray-800">
                                                        {{ $ticket_work->ticket->customer->customer_code }}
                                                    </a>
                                                </p>

                                            </div>
                                        </div>
                                    </td>

                                    <td>{{ $ticket_work->work_start_date }}</td>
                                    <!-- <td>{{ $ticket_work->work_end_date }}</td> -->
                                    <td>{{ $ticket_work->start_time }}</td>
                                    <td>{{ $ticket_work->end_time }}</td>
                                    <td>{{ $ticket_work->total_work_time }}</td>
                                    <td>
                                        @if ($ticket_work['is_overtime_approved'] == 0)
                                            <x-badge type="danger" label="No" class="" />
                                        @else
                                            <x-badge type="success" label="Yes" class="" />
                                        @endif
                                    </td>
                                    <td>
                                        <div class="inline-flex rounded-md shadow-sm" role="group">
                                            <button type="button" id="btnYes" data-overtime-id="{{ $ticket_work->id }}"
                                                data-status="1"
                                                class="status-update-btn px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 border border-transparent rounded-s-lg hover:bg-gradient-to-br hover:from-green-500 hover:via-green-600 hover:to-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-gradient-to-r dark:from-green-600 dark:via-green-700 dark:to-green-800 dark:hover:from-green-700 dark:hover:via-green-800 dark:hover:to-green-600 dark:focus:ring-green-400
                                    ">
                                                Yes
                                            </button>
                                            <button type="button" id="btnNo" data-overtime-id="{{ $ticket_work->id }}"
                                                data-status="0"
                                                class="status-update-btn px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 border border-transparent rounded-e-lg hover:bg-gradient-to-br hover:from-red-600 hover:via-red-700 hover:to-red-800 focus:z-10 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-gradient-to-r dark:from-red-700 dark:via-red-800 dark:to-red-900 dark:hover:from-red-800 dark:hover:via-red-900 dark:hover:to-red-700 dark:focus:ring-red-400">

                                                No
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                {{-- toast-message component --}}
                @if (session('toast'))
                    <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}"
                        {{--  error="{{ session('toast')['error'] }}" --}} />
                @endif

            </div>

            <div id="change-status-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Change Overtime approval status
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="change-status-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form id="overtime-approval-status-form" action="{{ route('overtime-aproval-status.update') }}"
                            method="POST" class="">
                            @csrf
                            <div class="grid gap-3 p-4 grid-cols-2">
                                <input type="hidden" name="overtime_id" id="overtime_id" value=""
                                    required="" />
                                @php
                                    $status = [['name' => 'Yes', 'value' => '1'], ['name' => 'No', 'value' => '0']];
                                @endphp

                                <div class="customer">
                                    <label class="text-sm dark:text-white">Status</label>
                                    <input type="hidden" id="overtimeve_approve_status" name="overtimeve_approve_status"
                                        value="">
                                    <!-- <x-input-dropdown
                                                            name="overtimeve_approve_status"
                                                            id="overtime_approve_status"
                                                            placeholder="Overtime Approval Status"
                                                            class=""
                                                            :options="$status"
                                                            optionalLabel="name"
                                                            optionalValue="value"
                                                            value="" /> -->
                                </div>

                                <hr class="md:col-span-2 dark:opacity-20" />

                                <div class="md:col-span-2 mt-2">
                                    <button type="submit" id="btn-update-status"
                                        class="text-white w-full inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
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
                if (document.getElementById("overtime-table") && typeof simpleDatatables.DataTable !== 'undefined') {
                    const dataTable = new simpleDatatables.DataTable("#overtime-table", {
                        searchable: true,
                        sortable: true,
                        header: true,
                        perPage: 5,
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
                    $('.status-update-btn').on('click', function() {
                        const overtime_id = $(this).data('overtime-id'); // Fetch engineer ID
                        const overtimeve_approve_status = $(this).data(
                            'status'); // Fetch status (1 for Yes, 0 for No)

                        if (confirm("Are you sure you want to change the overtime approval status?")) {
                            $.ajax({
                                url: '{{ route('overtime-aproval-status.update') }}', // Replace with your route
                                method: 'POST',
                                data: {
                                    overtime_id: overtime_id,
                                    overtimeve_approve_status: overtimeve_approve_status,
                                    _token: '{{ csrf_token() }}' // CSRF token for security
                                },
                                success: function(response) {
                                    alert(response.message || "Approval status updated successfully.");
                                    // Optionally update the UI or reload the table
                                    location.reload();

                                },
                                error: function(xhr, status, error) {
                                    alert('Error updating approval status. Please try again.');
                                }
                            });
                        }
                    });
                });
            </script>
        </div>
    </div>
@endsection
