@extends('layouts.app')

@section('title', 'Leads')

@section('content')
<div class="">
    <div class="card w-full overflow-hidden">

        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Leads
            </h3>
            <div class="text-center">
                @can($ModuleEnum::LEAD_CREATE->value)
                <a href="{{ route('lead.create') }}">
                    <button id="drawerBtn"
                        class="text-white bg-primary-light-one bg-blue-800 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                        type="button">
                        Generate Lead
                    </button>
                </a>
                @endcan
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
                                    Code
                                </span>
                            </th>
                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Date & Time
                                </span>
                            </th>
                            <th class=" bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center w-[17rem]">
                                    Lead Name
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Customer
                                </span>
                            </th>
                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center ">
                                    Lead Type
                                </span>
                            </th>

                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Location
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Status
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Ticket
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
                        @foreach ($leads as $lead)
                        <tr>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                #{{ $lead['lead_code'] }}
                            </td>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                <p class="leading-4">Date : {{ $lead['task_start_date'] ?? '-' }}</p>
                                <p>Time : {{ $lead['task_time'] }}</p>
                                @if ($lead['lead_status'] == 'reschedule')
                                <p>Follow Up : <x-badge type="warning" label="{{ $lead['reschedule_date'] }}"
                                        class="" /> </p>
                                @endif
                            </td>
                            <td class="capitalize px-6 py-4 ">
                                {{ $lead['name'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                    <img class="w-10 h-10 rounded-full border"
                                        src="{{ $lead['customer']['profile_image'] ? asset('storage/' . $lead['customer']['profile_image']) : asset('user_profiles/user/user.png') }}"
                                        alt="Rounded avatar">
                                    <div class="">
                                        <p class="capitalize leading-4">{{ $lead['customer']['name'] }}</p>
                                        <p class="text-gray-400">{{ $lead['customer']['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                {{ $lead['lead_type'] ? ucfirst(str_replace('_', ' ', $lead['lead_type'])) : '-' }}
                            </td>

                            <td class="flex gap-2  px-6 py-4 whitespace-nowrap flex-col">
                                <span>{{ $lead['city'] }}</span>
                                <span>{{ $lead['country'] }}</span>
                            </td>

                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                @if ($lead['lead_status'] == 'bid')
                                <x-badge type="warning" label="Bid" class="" />
                                @elseif($lead['lead_status'] == 'confirm')
                                <x-badge type="success" label="Confirm" class="" />
                                @elseif($lead['lead_status'] == 'reschedule')
                                <x-badge type="info" label="Reschedule" class="" />
                                @elseif($lead['lead_status'] == 'cancelled')
                                <x-badge type="danger" label="Cancelled" class="" />
                                @endif
                                {{-- change button set in bottom --}}
                                {{-- <br> --}}
                                @if (!$lead->is_ticket_created)
                                <button class="lead-status-change-btn" type="button"
                                    data-lead-id="{{ $lead['id'] }}"
                                    data-lead-status="{{ $lead['lead_status'] }}"
                                    data-reschedule-date="{{ $lead['reschedule_date'] }}"
                                    data-modal-target="change-status-modal" data-modal-toggle="change-status-modal">
                                    Change
                                </button>
                                @endif
                            </td>
                            <td class=" px-6 py-4 whitespace-nowrap">
                                {{-- @if(isset($lead->ticket->id))
                                <a href="{{ route('ticket.show', $lead->ticket->id) }}"
                                class="text-decoration hover:dark:text-gray-300 hover:text-gray-800">
                                {{ $lead?->ticket?->ticket_code }}</a>
                                @else
                                <a href="{{ route('ticket.create', [ 'lead_id' => $lead['id'], 'customer_id' => $lead['customer']['id'] ]) }}" class="text-center">
                                    <button type="button" title="Create Ticket"
                                        data-customer-id="{{ $lead->id }}"
                                        class="editBtn bg-green-400  text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                        Create Ticket
                                    </button></a>
                                @endif --}}
                                @if($lead->lead_status === 'confirm')
                                @if(isset($lead->ticket->id))
                                <a href="{{ route('ticket.show', $lead->ticket->id) }}"
                                    class="text-decoration hover:dark:text-gray-300 hover:text-gray-800">
                                    {{ $lead?->ticket?->ticket_code }}</a>
                                @else
                                <a href="{{ route('ticket.create', [ 'lead_id' => $lead['id'], 'customer_id' => $lead['customer']['id'] ]) }}" class="text-center">
                                    <button type="button" title="Create Ticket"
                                        data-customer-id="{{ $lead->id }}"
                                        class="editBtn  text-white bg-green-400 from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                        Create Ticket
                                    </button></a>
                                @endif
                                @else
                                -
                                @endif

                            </td>
                            <td>
                                <div class="flex gap-1 ">

                                    @if (!isset($lead?->ticket?->ticket_code))

                                    <a href="{{ route('lead.edit', $lead->id) }}">
                                        <button type="button" title="Edit"
                                            data-customer-id="{{ $lead->id }}"
                                            class="editBtn  text-white bg-green-400 from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
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
                                    @can($ModuleEnum::LEAD_DETAIL->value)
                                    <button type="button" title="View" data-lead-id="{{ $lead->id }}"
                                        data-modal-target="static-modal" data-modal-toggle="static-modal" id='notesViewBtn'
                                        class="lead-viewBtn text-white bg-gradient-to-r from-blue-400 via-blue-400 to-blue-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-blue-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                        <svg class="w-5 h-5 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>


                                        <span class="sr-only">Icon description</span>
                                    </button>
                                    @endcan

                                    @can($ModuleEnum::LEAD_DELETE->value)
                                    <form id="deleteForm_{{$lead->id}}" action="{{ route('lead.destroy', $lead->id) }}"
                                        method="Post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="Delete" id='deleteBtn'
                                            data-lead-id="{{$lead->id}}"
                                            class="deleteBtn text-white flex bg-red-400 from-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700   font-medium rounded-lg text-sm px-5 py-2 text-center">
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
                </table>
            </div>


            @if (session('toast'))
            <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}" />
            @elseif($errors->any())
            <x-toast-message type="danger" message="Oops, Something went wrong, Check the form again!" />
            @endif
            <div id="change-status-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Change Status
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="change-status-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form id="lead-status-form" action="{{ route('lead-status.update') }}" method="POST"
                            class="">
                            @csrf
                            <div class="grid gap-3 p-4 grid-cols-2">
                                <input type="hidden" name="lead_id" id="lead_id" value=""
                                    required="" />
                                @php
                                $status = [
                                ['name' => 'Bid', 'value' => 'bid'],
                                ['name' => 'Confirm', 'value' => 'confirm'],
                                ['name' => 'Reschedule', 'value' => 'reschedule'],
                                ['name' => 'Cancelled', 'value' => 'cancelled'],
                                ];
                                @endphp

                                <div class="customer">
                                    <label class="text-sm dark:text-white">Status</label>
                                    <x-input-dropdown name="lead_status" id="lead_status" placeholder="Lead Status"
                                        class="" :options="$status" optionalLabel="name" optionalValue="value"
                                        value="" />
                                </div>

                                <div class="reschedule_date_container hidden">
                                    <label class="text-sm dark:text-white">Reschedule Date</label>
                                    <input name="reschedule_date" id="reschedule_date" placeholder="Reschedule Date"
                                        class="border dark:border-[#5A6270] border-gray-300 rounded-lg mt-2 dark:text-white dark:bg-[#374151]"
                                        value="" type="date" min="<?php echo date('Y-m-d'); ?>" />
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

            <!-- Main modal -->
            <div id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-[60vw] max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Lead Details
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="grid grid-cols-4 p-4 gap-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Lead Code</span>
                                <strong class="font-medium text-md text-primary leadCode"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">End Client Name</span>
                                <strong class="font-medium text-md endClientName"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Task Start Time</span>
                                <strong class="font-medium text-md taskTime"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Currency Type</span>
                                <strong class="font-medium text-md currencyType"></strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Lead Type</span>
                                <strong class="font-medium text-md leadType"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Client Ticket No</span>
                                <strong class="font-medium text-md clientTicketNo"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Task Start Date</span>
                                <strong class="font-medium text-md taskStartDate"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Rate Type</span>
                                <strong class="font-medium text-md rateType"></strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Task Name</span>
                                <strong class="font-medium text-md taskName"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Task Location</span>
                                <strong class="font-medium text-md taskLocation"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Task End Date</span>
                                <strong class="font-medium text-md taskEndDate"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Monthly Rate</span>
                                <strong class="font-medium text-md monthlyRate"></strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Scope of Work</span>
                                <strong class="font-medium text-md scopeOfWork"></strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Travel Cost</span>
                                <strong class="font-medium text-md travelCost"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Tool Cost</span>
                                <strong class="font-medium text-md toolCost"></strong>
                            </div>
                        </div>
                    </div>
                </div>
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
    $(document).ready(function() {

        $('.deleteBtn').click(function() {
            const confirmation = confirm('Are you sure you want to delete this lead & related data ?')
            if (confirmation) {
                var leadId = $(this).data('lead-id');
                $(`#deleteForm_${leadId}`).submit()
            }
        })

        $(document).on('click', '.lead-status-change-btn', function() {
            let lead_id = $(this).data('lead-id');
            let lead_status = $(this).data('lead-status');
            let reschedule_date = $(this).data('reschedule-date'); // Add this if you are passing the reschedule date.

            $('#lead_id').val(lead_id);
            $('#lead_status').val(lead_status);

            // Check if the lead status is "reschedule"
            if (lead_status === 'reschedule') {
                $('.reschedule_date_container').removeClass('hidden');
                $('#reschedule_date').val(reschedule_date || ''); // Pre-fill the date if available.
                $('#reschedule_date').prop('required', true);
            } else {
                $('.reschedule_date_container').addClass('hidden');
                $('#reschedule_date').val('');
                $('#reschedule_date').prop('required', false);
            }
        });

        $(document).on('click', '#btn-update-status', function(e) {
            var lead_status = $('#lead_status').val();
            var reschedule_date = $('#reschedule_date').val();

            if (lead_status === 'reschedule' && !reschedule_date) {
                alert('Please select a Reschedule Date before submitting.');
                e.preventDefault(); // Prevent form submission
                return;
            }

            const confirmation = confirm('Are you sure you want to update status?');
            if (confirmation) {
                $('#lead-status-form').submit();
            }
        });

        $(document).on('change', '#lead_status', function() {
            var currentVal = $(this).val();
            if (currentVal === 'reschedule') {
                $('.reschedule_date_container').removeClass('hidden');
                $('#reschedule_date').prop('required', true);
            } else {
                $('.reschedule_date_container').addClass('hidden');
                $('#reschedule_date').val('');
                $('#reschedule_date').prop('required', false);
            }
        });

        $('.lead-viewBtn').on('click', function() {
            const leadId = $(this).data('lead-id');

            $.ajax({
                url: `/get-lead-details/${leadId}`,
                type: 'GET',
                success: function(response) {
                    const data = response.leadData;

                    $('.leadCode').text('#' + (data.lead_code || '-'));
                    $('.taskName').text(data.name || '-');
                    $('.leadType').text(data.lead_type || '-');
                    $('.endClientName').text(data.end_client_name || '-');
                    $('.clientTicketNo').text(data.client_ticket_no || '-');
                    $('.taskStartDate').text(data.task_start_date || '-');
                    $('.taskEndDate').text(data.task_end_date || '-');
                    $('.taskLocation').text(data.task_location || '-');
                    $('.taskTime').text(data.task_time || '-');
                    $('.scopeOfWork').text(data.scope_of_work || '-');
                    $('.rateType').text(data.rate_type || '-');
                    $('.hourlyRate').text(data.hourly_rate || '-');
                    $('.halfDayRate').text(data.half_day_rate || '-');
                    $('.fullDayRate').text(data.full_day_rate || '-');
                    $('.monthlyRate').text(data.monthly_rate || '-');
                    $('.currencyType').text(data.currency_type || '-');
                    $('.travelCost').text(data.travel_cost || '-');
                    $('.toolCost').text(data.tool_cost || '-');
                }
            });
        });

    })
</script>
@endsection