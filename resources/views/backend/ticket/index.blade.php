@extends('layouts.app')

@section('title', 'Ticket')

@section('content')

<div class="overflow-x-hidden">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Tickets
            </h3>
            <div class="text-center">
                @can($ModuleEnum::TICKET_CREATE->value)
                <a href="{{ route('ticket.create') }}">
                    <button id="drawerBtn"
                        class="text-white bg-primary-light-one focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                        type="button">
                        Create Ticket
                    </button>
                </a>
                @endcan
            </div>
        </div>

        {{-- card-body  --}}
        <div class="card-body relative ">

            {{-- data-table  --}}
            <div class="border border-1 border-gray-200 overflow-x-auto dark:border-gray-700 rounded-xl p-4">
                <table id="search-table" class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Code
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Customer
                                </span>
                            </th>
                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center w-[17rem]">
                                    Lead & Task
                                </span>
                            </th>
                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Date & Time
                                </span>
                            </th>
                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center ">
                                    Engineer Assigned
                                </span>


                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center ">
                                    Status
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center ">
                                    Action
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                        <tr>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                #{{ $ticket['ticket_code'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">

                                    <img class="w-10 h-10 rounded-full border"
                                        src="{{ $ticket['customer']['profile_image'] ? asset('storage/' . $ticket['customer']['profile_image']) : asset('user_profiles/user/user.png') }}"
                                        alt="Rounded avatar">

                                    <div class="">
                                        <p class="capitalize leading-4">{{ $ticket['customer']['name'] }}</p>
                                        <p class="text-gray-400">{{ $ticket['customer']['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                <p class="">Lead : {{ $ticket['lead']['lead_code'] ?? '-' }}</p>
                                <p class="w-[17rem] text-wrap">Task : {{ $ticket['task_name'] }}</p>
                                <p>Location : {{ $ticket['city'] }} | {{ $ticket['country'] }}</p>
                            </td>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                <p class="">
                                    Timezone : {{ $ticket['timezone'] ?? '--' }}
                                    ({{ fetchTimezone($ticket['timezone'])['gmtOffsetName'] ?? '' }})

                                </p>
                                @if($ticket['task_start_date'] != $ticket['task_end_date'])
                                    <p class="">
                                        Start : {{ $ticket['ticket_start_date_tz'] }}

                                    </p>
                                    <p class="">
                                        End &nbsp; : {{ $ticket['ticket_end_date_tz'] }}

                                    </p>
                                @else
                                    <p class="">Date : {{ $ticket['ticket_start_date_tz'] }}</p>
                                @endif
                                <p class="">Time : {{ $ticket['ticket_time_tz'] }}</p>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if (isset($ticket['engineer']) && !empty($ticket['engineer']))
                                <div
                                    class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                    <img class="w-10 h-10 rounded-full border"
                                        src="{{ $ticket['engineer']['profile_image'] ? asset('storage/' . $ticket['engineer']['profile_image']) : asset('user_profiles/user/user.png') }}"
                                        alt="Rounded avatar">
                                    <div class="">
                                        <p class="capitalize leading-4">{{ $ticket['engineer']['first_name'] }}
                                            {{ $ticket['engineer']['last_name'] }}
                                        </p>
                                        <p class="text-gray-400 truncate w-36"
                                            title="{{ $ticket['engineer']['email'] }}">
                                            {{ $ticket['engineer']['email'] }}
                                        </p>
                                    </div>

                                    {{-- change button set in bottom --}}
                                    {{-- <br> --}}

                                    @if ( ($ticket['status'] === 'inprogress' || $ticket['status'] === 'hold' || $ticket['status'] === 'close' || $ticket['status'] === 'expired' ))
                                    @else
                                    <button class="engineer-change-btn mr-3" type="button"
                                        data-ticket-id="{{ $ticket['id'] }}"
                                        data-modal-target="change-enginner-model"
                                        data-modal-toggle="change-enginner-model">
                                        Change
                                    </button>
                                    @endif
                                </div>
                                @else
                                <div class="flex flex-col justify-center items-center gap-3">
                                    <span>Not Assign</span>
                                    <button class="engineer-change-btn text-green-300 hover:text-green-500" type="button"
                                        data-ticket-id="{{ $ticket['id'] }}"
                                        data-modal-target="change-enginner-model"
                                        data-modal-toggle="change-enginner-model">
                                        Assign
                                    </button>
                                </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($ticket['status'] === 'inprogress')
                                <x-badge type="success" label="In Progress" class="" />
                                @elseif($ticket['status'] === 'hold')
                                <x-badge type="info" label="On Hold" class="" />
                                @elseif($ticket['status'] === 'break')
                                <x-badge type="warning" label="On Break" class="" />
                                @elseif($ticket['status'] === 'close')
                                <x-badge type="success" label="Close" class="" />
                                @elseif($ticket['status'] === 'expired')
                                <x-badge type="danger" label="Expired" class="" />
                                @else
                                @if($ticket['engineer_status'] == 'offered')
                                <x-badge type="purple" label="Offered" class="" />
                                @elseif($ticket['engineer_status'] == 'accepted')
                                <x-badge type="purple" label="Accepted" class="" />
                                @elseif($ticket['engineer_status'] == 'accepted' && date('Y-m-d') == $ticket['task_start_date'])
                                <x-badge type="purple" label="Not Started" class="" />
                                @endif
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex gap-1 ">

                                    @can($ModuleEnum::TICKET_DETAIL->value)
                                    <a href="{{ route('ticket.show', $ticket->id) }}">
                                        <button
                                            type="button"
                                            title="View"
                                            class="text-white bg-gradient-to-r from-blue-400 via-blue-400 to-blue-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-blue-600 shadow-lg dark:shadow-lg  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>

                                            <span class="sr-only">Icon description</span>
                                            {{-- Show  --}}
                                        </button>
                                    </a>
                                    @endcan

                                    @can($ModuleEnum::TICKET_EDIT->value)
                                    @if ($ticket['status'] != 'close')
                                    <a href="{{ route('ticket.edit', $ticket->id) }}">
                                        <button type="button" title="Edit"
                                            data-customer-id="{{ $ticket->id }}"
                                            class="editBtn  text-white bg-green-400 from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600 font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                            {{-- Edit  --}}
                                        </button>
                                    </a>
                                    @endif
                                    @endcan

                                    @can($ModuleEnum::TICKET_DELETE->value)
                                    <form id="deleteForm_{{$ticket->id}}" action="{{ route('ticket.destroy', $ticket->id) }}"
                                        method="Post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="Delete" id='deleteBtn'
                                            data-ticket-id="{{$ticket->id}}"
                                            class="deleteBtn text-white flex bg-red-400 from-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700 shadow-lg dark:shadow-lg font-medium rounded-lg text-sm px-5 py-2 text-center">
                                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                            </svg>
                                            {{-- Delete --}}
                                        </button>
                                    </form>
                                    @endcan
                                </div>

                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                    {{-- @php
                            print_r($errors->all());
                        @endphp  --}}

                </table>
            </div>

            {{-- toast-message component --}}
            @if (session('toast'))
            <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}"
                {{--  error="{{ session('toast')['error'] }}" --}} />
            @elseif($errors->any())
            <x-toast-message type="danger" message="Oops, Something went wrong, Check the form again!" />
            @endif
        </div>


        <div id="change-enginner-model" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Change Engineer
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="change-enginner-model">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form id="ticket-engineer-form" action="{{ route('engineer-change.update') }}" method="POST"
                        class="">
                        @csrf
                        <div class="grid gap-3 p-4 grid-cols-1">
                            <input type="hidden" name="ticket_id" id="ticket_id" value="" required="" />
                            @php
                            $status = array_map(function ($engineer) {
                            return [
                            'name' =>
                            ($engineer['first_name'] ?? '') . ' ' . ($engineer['last_name'] ?? ''),
                            'value' => $engineer['id'] ?? '',
                            ];
                            }, $engineers ?? []);
                            @endphp

                            <div class="customer">
                                <label class="text-sm dark:text-white">Engineers</label>
                                <x-input-dropdown name="engineer" id="engineer" placeholder="Select Engineers"
                                    class="" :options="$status" optionalLabel="name" optionalValue="value"
                                    value="" />
                            </div>

                            <hr class="md:col-span-2 dark:opacity-20" />

                            <div class="md:col-span-2 mt-2">
                                <button type="submit" id="btn-update-engineer"
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
        <script>
            $(document).ready(function() {
                $('.deleteBtn').click(function() {
                    const confirmation = confirm(
                        'Are you sure you want to delete this customer & related data ?')
                    if (confirmation) {
                        var ticketId = $(this).data('ticket-id');
                        $(`#deleteForm_${ticketId}`).submit()
                    }
                })

                $(document).on('click', '.engineer-change-btn', function() {
                    let ticket_id = $(this).data('ticket-id');
                    $('#ticket_id').val(ticket_id);
                });

                $(document).on('click', '#btn-update-engineer', function() {
                    // var lead_status = $('#lead_status').val();
                    // console.log('lead_status', lead_status);
                    const confirmation = confirm('Are you sure you want to update engineer?')
                    if (confirmation) {
                        $('#ticket-engineer-form').submit()
                    }
                });

                // $(document).on('change', '#lead_status', function() {
                //     var currentVal = $(this).val();
                //     if (currentVal === 'reschedule') {
                //         $('.reschedule_date_container').removeClass('hidden');
                //     } else {
                //         $('.reschedule_date_container').addClass('hidden');
                //     }
                // });


            })
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