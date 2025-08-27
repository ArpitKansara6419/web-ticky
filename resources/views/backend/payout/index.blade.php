@extends('layouts.app')

@section('title', 'Payout')

@section('content')

<div class="">

    <div class="mb-4 flex justify-between items-center">
        <div class="text-center">
        </div>
    </div>

    <div class="card">
        {{-- card-body  --}}
        <div class="card-body ">
            <h4 class="text-2xl flex font-extrabold  gap-2">
                <svg class="w-8 h-8  text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                {{ ucfirst($engineer['first_name']).' '.ucfirst($engineer['last_name']) }}'s Payslip
            </h4>
            {{-- data-table  --}}
            <div class="">
                <table id="search-table" class="w-full border rounded-xl" style="border-radius: 12px">
                    <thead>
                        <tr>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Sr.
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Payment Date
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Total Tickets
                                </span>
                            </th>

                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Total Payout
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Pay Status
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Action
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($payouts))
                        @foreach ($payouts as $key => $payout)
                        <tr>
                            <td class="px-auto  py-4">
                                <span class="flex items-center justify-center ">
                                    {{ $key + 1 }}
                                </span>
                            </td>
                            <td class="px-auto  py-4">
                                <span class="flex items-center justify-center">
                                    {{ $payout->payment_date->format('Y-m-d') }}
                                </span>
                            </td>
                            <td class="px-auto  py-4">
                                <div class="flex items-center justify-center cursor-pointer gap-4 ticketPop"
                                    data-modal-target="static-modal"
                                    data-modal-toggle="static-modal"
                                    data-ticketwork-ids="{{ is_array($payout->ticket_work_ids) ? implode(',', $payout->ticket_work_ids) : $payout->ticket_work_ids }}">
                                    <span>{{ is_array($payout->ticket_work_ids) ? count($payout->ticket_work_ids) : 0 }}</span>
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.5 12A2.5 2.5 0 0 1 21 9.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 1 0 5V17a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                                    </svg>
                                </div>
                            </td>
                            <td class="px-auto  py-4">
                                <span class="flex items-center justify-center">
                                    @php
                                    $currencyIcon = '' ;
                                    if ($payout?->engineer?->enggCharge?->currency_type == 'dollar') {
                                    $currencyIcon = '$';
                                    } else if ($payout?->engineer?->enggCharge?->currency_type == 'pound') {
                                    $currencyIcon = '£';
                                    } else if ($payout?->engineer?->enggCharge?->currency_type == 'euro') {
                                    $currencyIcon = '€';
                                    } else if ($payout?->engineer?->enggCharge?->currency_type == 'zloty') {
                                    $currencyIcon = 'zł';
                                    }
                                    @endphp
                                    {{$currencyIcon}} {{ $payout->gross_pay - $payout->ZUS - $payout->PIT }}
                                </span>
                            </td>
                            <td class="px-auto  py-4">
                                <div class=" flex justify-center items-center">
                                    @if ($payout->payment_status === 'paid')
                                    <x-badge type="success" label="Paid" class="" />
                                    @elseif($payout->payment_status === 'pending')
                                    <x-badge type="info" label="Pending" class="" />
                                    @elseif($payout->payment_status === 'failed')
                                    <x-badge type="warning" label="Failed" class="" />
                                    @endif
                                </div>
                                <!-- <x-badge type="success" label="{{ $payout->payment_status }}" class="" /> -->
                            </td>
                            <td class="px-auto  py-4">

                                <div class="flex justify-center items-center gap-2 ">
                                    <a href="{{ route('engineer.slip', ['engineerId' => $payout->engineer->id, 'payoutId' => $payout->id]) }}">
                                        <button type="button" title="Payout Slip"
                                            class="editBtn  bg-green-200  font-medium rounded-lg text-sm p-2">
                                            <svg class="w-5 h-5 text-green-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </a>

                                    <a href="{{ route('engineer-work.slip', ['ticket_works_id' => implode(',', (array) $payout->ticket_work_ids)]) }}">
                                        <button type="button" title="Payout Work Slip"
                                            class="editBtn  bg-teal-200  font-medium rounded-lg text-sm p-2">
                                            <svg class="w-5 h-5 text-teal-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </a>

                                    <div>
                                        <form id="deleteForm_{{$payout->id}}" action="{{ route('payout.destroy', $payout->id) }}" method="Post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" title="Delete"
                                                data-payout-id="{{$payout->id}}"
                                                class="deleteBtn  bg-red-200  font-medium rounded-lg text-sm p-2">
                                                <svg class="w-5 h-5 text-red-400 " aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                </svg>
                                                {{-- Delete --}}
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="10">
                                <div>
                                    <span> No record found. </span>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Main modal -->
            <div id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center         items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-[65vw] max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Total Ticket
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <div class="overflow-hidden rounded-xl border">
                                <table class="w-full rounded-xl">
                                    <thead>
                                        <tr class="bg-gray-300">
                                            <th class="p-3 text-center text-gray-800">Sr.No</th>
                                            <th class="p-3 text-center text-gray-800">Ticket</th>
                                            <th class="p-3 text-center text-gray-800">Date</th>
                                            <th class="p-3 text-center text-gray-800">Customer</th>
                                            <th class="p-3 text-center text-gray-800">Hours</th>
                                            <th class="p-3 text-center text-gray-800">Gross Pay</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ticketTableBody">
                                        <!-- Dynamic data will be inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            @if (session('toast'))
            <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}" />
            @elseif($errors->any())
            <x-toast-message type="danger" message="Oops, Something went wrong, Check the form again!" />
            @endif

        </div>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
        <script>
            if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#search-table", {
                    searchable: false,
                    sortable: false,
                    header: true,
                    // perPage: 5,
                    paging: false,
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
    var  currencySymbols = @json(config('currency.symbols'));
    $(document).ready(function() {
        var payoutsData = @json($payouts);

        $('.deleteBtn ').click(function() {
            var payoutId = $(this).data('payout-id');
            const confirmation = confirm('Are you sure you want to delete this Payout & related data?');
            if (confirmation) {
                $(`#deleteForm_${payoutId}`).submit();
            }
        });


        $(document).on('click', '.ticketPop', function() {
            const ticketWorkIds = $(this).data('ticketwork-ids');

            if (!ticketWorkIds || (Array.isArray(ticketWorkIds) && ticketWorkIds.length === 0) || (typeof ticketWorkIds === 'string' && ticketWorkIds.trim() === '')) {
                console.warn('No ticket IDs found.');
                $('#ticketTableBody').html('<tr><td colspan="6" class="p-3 text-center text-gray-500">No ticket IDs provided</td></tr>');
                return;
            }

            $.ajax({
                url: "{{ route('payoutTicketDetials') }}",
                method: "GET",
                data: {
                    ticketWorkIds: ticketWorkIds
                },
                success: function(response) {
                    if (response.success && Array.isArray(response.ticketWorks) && response.ticketWorks.length > 0) {
                        renderTable(response.ticketWorks);
                    } else {
                        console.warn('No tickets found.');
                        $('#ticketTableBody').html('<tr><td colspan="6" class="p-3 text-center text-gray-500">No tickets found</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching ticket details:', error);
                    $('#ticketTableBody').html('<tr><td colspan="6" class="p-3 text-center text-red-500">Failed to fetch ticket details</td></tr>');
                }
            });
        });


        function renderTable(tickets) {
            let tableBody = $('#ticketTableBody'); // Target the tbody element
            tableBody.empty(); // Clear existing rows

            if (!tickets.length) {
                tableBody.append('<tr><td colspan="6" class="p-3 text-center text-gray-500">No tickets found</td></tr>');
                return;
            }

            console.log('tickets====', tickets);

            tickets.forEach((ticket, index) => {
                let currency = currencySymbols[ticket.currency];
                let row = `
            <tr class="border-t">
                <td class="p-3 text-center">${index + 1}</td>
                <td class="p-3">
                    <div class="font-semibold">${ticket.ticket.ticket_code}</div>
                    <div class="text-sm text-gray-500">${ticket.ticket.task_name || 'N/A'}</div>
                </td>
                <td class="p-3">${formatDate(ticket.ticket.task_start_date)}</td>
                <td class="p-3">${ticket.ticket.client_name}</td>
                <td class="p-3">${ticket.total_work_time}</td>
                <td class="p-3 text-center">${currency} ${ticket.daily_gross_pay}</td>
            </tr>
            `;
                tableBody.append(row);
            });
        }
    });

    // Helper function to format date
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        let date = new Date(dateString);
        return date.toLocaleDateString('en-GB'); // Formats as DD/MM/YYYY
    }
</script>
@endsection