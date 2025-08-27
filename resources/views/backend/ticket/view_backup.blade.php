@extends('layouts.app')

@section('title', 'Engineer Payout')

@section('content')

<div class="">
    <div class="card">
        <input type="hidden" id="storage_link" name="storage_link" value="<?php echo asset('storage/'); ?>">
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Ticket Details
            </h3>
            <div class="text-center">
            </div>
        </div>
        {{-- card-body  --}}
        <div class="card-body">
            <div class="p-6 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-lg">
                <!-- Profile Info -->
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <div class="rounded-full border-4 border-gray-600 p-3 bg-white dark:bg-gray-700 shadow-md">
                            <svg class="w-[70px] h-[70px] text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.3"
                                    d="M18.5 12A2.5 2.5 0 0 1 21 9.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 1 0 5V17a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-gray-800 dark:text-white">
                        Ticket Code: <span class="text-blue-600 dark:text-blue-400">{{ $ticket['ticket_code'] }}</span>
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="grid md:grid-cols-3 gap-3 mt-6 text-gray-800 dark:text-gray-200 text-sm">
                    <div class="flex justify-between items-center bg-white dark:bg-gray-700 p-3 rounded-lg shadow">
                        <strong>Customer:</strong>
                        <span class="text-blue-600 dark:text-blue-400">{{ $ticket['customer']['customer_code'] }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white dark:bg-gray-700 p-3 rounded-lg shadow">
                        <strong>Customer Name:</strong>
                        <span>{{ $ticket['client_name'] }}</span>
                    </div>
                    @if (isset($ticket['engineer']['first_name']))
                    <div class="flex justify-between items-center bg-white dark:bg-gray-700 p-3 rounded-lg shadow">
                        <strong>Engineer:</strong>
                        <span>{{ $ticket['engineer']['engineer_code'] }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center bg-white dark:bg-gray-700 p-3 rounded-lg shadow">
                        <strong>Ticket Status:</strong>
                        <div>
                            @if ($ticket['status'] === 'inprogress')
                            <x-badge type="success" label="In Progress" />
                            @elseif($ticket['status'] === 'hold')
                            <x-badge type="info" label="On Hold" />
                            @elseif($ticket['status'] === 'break')
                            <x-badge type="warning" label="On Break" />
                            @elseif($ticket['status'] === 'close')
                            <x-badge type="pink" label="Closed" />
                            @else
                            <x-badge type="purple" label="Not Started" />
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-center bg-white dark:bg-gray-700 p-3 rounded-lg shadow">
                        <strong>Start Date:</strong>
                        <span>{{ $ticket->task_start_date }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white dark:bg-gray-700 p-3 rounded-lg shadow">
                        <strong>End Date:</strong>
                        <span>{{ $ticket->task_end_date }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white dark:bg-gray-700 p-3 rounded-lg shadow">
                        <strong>Time:</strong>
                        <span>{{ $ticket->task_time }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class=" flex  justify-end text-green-400 hover:text-green-500 mt-3 cursor-pointer group ">
    <button data-drawer-target="drawer-right-example" data-drawer-show="drawer-right-example"
        data-drawer-placement="right" aria-controls="drawer-right-example">
        More Details
    </button>
    <svg class="w-6 h-6 group-hover:translate-x-2 duration-500" aria-hidden="true"
        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M19 12H5m14 0-4 4m4-4-4-4" />
    </svg>
</div>

<div class="card-header flex justify-between items-center">
    <h4 class="text-2xl font-extrabold">
        Ticket Work History
    </h4>
    <div class="text-center">
    </div>
</div>
<div class="mb-4 mt-4 border-b border-gray-200 dark:border-gray-700">
    <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4">
        <table id="work-table">
            <thead>
                <tr>
                    <th class="bg-blue-100 dark:bg-gray-900">
                        <span class="flex items-center">
                            SR.No
                        </span>
                    </th>
                    <th class="bg-blue-100  dark:bg-gray-900">
                        <span class="flex items-center">
                            Ticket Code
                        </span>
                    </th>
                    <th class="bg-blue-100 dark:bg-gray-900">
                        <span class="flex items-center">
                            Engineer
                        </span>
                    </th>
                    <th class="bg-blue-100 dark:bg-gray-900">
                        <span class="flex items-center">
                            Work Date
                        </span>
                    </th>
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
                            Total Work Time
                        </span>
                    </th>
                    <th class="bg-blue-100  dark:bg-gray-900">
                        <span class="flex items-center">
                            Action
                        </span>
                    </th>
                    <!-- <th class="bg-blue-100  dark:bg-gray-900">
                                            <span class="flex items-center">
                                                Documents
                                            </span>
                                        </th> -->
                </tr>
            </thead>
            <tbody>
                @foreach ($ticket_works as $ticket_work)
                <tr>
                    <td class="capitalize">
                        {{ $loop->iteration }}
                    </td>
                    <td class="capitalize">
                        {{ $ticket_work->ticket->ticket_code }}
                    </td>
                    <td class="capitalize">
                        {{ $ticket['engineer']['first_name'] . ' ' . $ticket['engineer']['last_name'] ?? '-' }}
                    </td>
                    <td class="capitalize">
                        <p class="leading-4">{{ $ticket_work['work_start_date'] ?? '-' }}</p>
                    </td>
                    <td class="capitalize">
                        <p class="leading-4">{{ $ticket_work['start_time'] ?? '-' }}</p>
                    </td>
                    <td class="capitalize">
                        <p class="leading-4">{{ $ticket_work['end_time'] ?? '-' }}</p>
                    </td>
                    <td class="capitalize">
                        <p class="leading-4"> {{ $ticket_work['total_work_time'] ?? '-' }}</p>
                    </td>
                    <td>
                        <button type="button" title="View"
                            data-ticket-work-id="{{ $ticket_work->id }}"
                            data-drawer-target="table-drawer" data-drawer-show="table-drawer"
                            data-drawer-placement="right" aria-controls="table-drawer" id='notesViewBtn'
                            class="note-view-btn editBtn  text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
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
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- DRAWER -->
<div id="drawer-right-example"
    class="fixed top-[10%] right-0 z-40 h-auto p-6 transition-transform translate-x-full rounded-lg bg-white w-[38vw] dark:bg-gray-800 shadow-lg"
    tabindex="-1" aria-labelledby="drawer-right-label">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h5 id="drawer-right-label"
            class="inline-flex items-center text-lg font-semibold text-gray-700 dark:text-gray-200">
            <svg class="w-5 h-5 mr-2 text-gray-800 dark:text-white" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                    clip-rule="evenodd" />
            </svg>
            Ticket Details
        </h5>
        <button type="button" data-drawer-hide="drawer-right-example"
            aria-controls="drawer-right-example"
            class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>

    <!-- Scrollable Content -->
    <div class="h-[70vh] overflow-y-auto space-y-6">
        <!-- Ticket Info Section -->
        <section>
            <h6 class="text-lg font-semibold text-gray-600 dark:text-gray-100 border-b pb-2 mb-4">Ticket
                Information</h6>
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Ticket Code:</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->ticket_code ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Task Name:</span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->task_name ?? '-' }}</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-2">
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Customer Name:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->customer->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Engineer Assigned:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket['engineer']['first_name'] . ' ' . $ticket['engineer']['last_name'] ?? '-' }}</span>
                </div>

            </div>
            <div class="grid grid-cols-3 gap-3 mt-2">
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Start Date:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($ticket->task_start_date)->format('d M, Y') ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">End Date:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($ticket->task_end_date)->format('d M, Y') ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Task Time:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->task_time ?? '-' }}</span>
                </div>
            </div>
        </section>

        <!-- Additional Costs Section -->
        <section>
            <h6 class="text-lg font-semibold text-gray-600 dark:text-gray-100 border-b pb-2 mb-4">
                Additional Costs</h6>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Rate Type:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->rate_type ?? '-' }} </span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Rate:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket_currency }} {{ $ticket->standard_rate ?? 0 }}</span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Travel Cost:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket_currency }} {{ $ticket->travel_cost ?? 0 }}</span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Tool Cost:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket_currency }} {{ $ticket->tool_cost ?? 0 }}</span>
                </div>
                {{-- <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Food Expenses:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket_currency }} {{ $ticket->food_expenses ?? 0 }}</span>
            </div> --}}
            {{-- <div>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Miscellaneous
                        Expenses:</span>
                    <span
                        class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket_currency }} {{ $ticket->misc_expenses ?? 0 }}</span>
    </div> --}}
    <div>
        <span class="block text-sm text-gray-500 dark:text-gray-400">Currency:</span>
        <span class="font-medium text-gray-700 dark:text-green-500 text-lg">
            {{ $ticket_currency }}
        </span>
    </div>
</div>
</section>

<!-- Address Information Section -->
<section>
    <h6 class="text-lg font-semibold text-gray-600 dark:text-gray-100 border-b pb-2 mb-4">Address
        Information</h6>
    <div class="grid grid-cols-2 gap-6">
        <div>
            <span class="block text-sm text-gray-500 dark:text-gray-400">Apartment: </span>
            <span
                class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->apartment ?? '-' }}</span>
        </div>
        <div>
            <span class="block text-sm text-gray-500 dark:text-gray-400">Address Line 1:</span>
            <span
                class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->add_line_1 ?? '-' }}</span>
        </div>
        <div>
            <span class="block text-sm text-gray-500 dark:text-gray-400">City:</span>
            <span
                class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->city ?? '-' }}</span>
        </div>
        <div>
            <span class="block text-sm text-gray-500 dark:text-gray-400">Zipcode:</span>
            <span class="font-medium text-gray-700 dark:text-gray-200">
                {{ $ticket->zipcode ?? '-' }}</span>
        </div>
        <div>
            <span class="block text-sm text-gray-500 dark:text-gray-400">Country:</span>
            <span
                class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->country ?? '-' }}</span>
        </div>
    </div>
</section>

<!-- Extraa Details Section -->
<section>
    <h6 class="text-lg font-semibold text-gray-600 dark:text-gray-100 border-b pb-2 mb-4">Extra
        Information</h6>
    <div class="grid grid-cols-1 gap-6">
        <div>
            <div class="flex gap-2">
                <svg data-tooltip-target="tooltip-content1"
                    class="w-5 h-5 text-gray-800  dark:text-white hover:text-gray-600 dark:hover:text-gray-500 duration-700 hover:cursor-pointer"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="currentColor" viewBox="0 0 24 24"
                    onclick="copyToClipboard('{{ $ticket->poc_details }}')"
                    title="Copy to clipboard">
                    <path fill-rule="evenodd"
                        d="M18 3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1V9a4 4 0 0 0-4-4h-3a1.99 1.99 0 0 0-1 .267V5a2 2 0 0 1 2-2h7Z"
                        clip-rule="evenodd" />
                    <path fill-rule="evenodd"
                        d="M8 7.054V11H4.2a2 2 0 0 1 .281-.432l2.46-2.87A2 2 0 0 1 8 7.054ZM10 7v4a2 2 0 0 1-2 2H4v6a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z"
                        clip-rule="evenodd" />
                </svg>
                <div id="tooltip-content1" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-1 text-sm  text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    copy to clipboard
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <span class="block text-sm text-gray-500 dark:text-gray-400">POC Details:</span>
            </div>
            <span
                class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->poc_details ?? '-' }}</span>
        </div>

        <div>
            <div class="flex gap-2">
                <svg data-tooltip-target="tooltip-content2"
                    class="w-5 h-5 text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-500 duration-700 hover:cursor-pointer"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="currentColor" viewBox="0 0 24 24"
                    onclick="copyToClipboard('{{ $ticket->re_details }}')"
                    title="Copy to clipboard">
                    <path fill-rule="evenodd"
                        d="M18 3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1V9a4 4 0 0 0-4-4h-3a1.99 1.99 0 0 0-1 .267V5a2 2 0 0 1 2-2h7Z"
                        clip-rule="evenodd" />
                    <path fill-rule="evenodd"
                        d="M8 7.054V11H4.2a2 2 0 0 1 .281-.432l2.46-2.87A2 2 0 0 1 8 7.054ZM10 7v4a2 2 0 0 1-2 2H4v6a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z"
                        clip-rule="evenodd" />
                </svg>
                <div id="tooltip-content2" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-1 text-sm  text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    copy to clipboard
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <span class="block text-sm text-gray-500 dark:text-gray-400">RE Details:</span>
            </div>
            <span
                class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->re_details ?? '-' }}</span>
        </div>
        <div>
            <div class="flex gap-2">
                <svg data-tooltip-target="tooltip-content3"
                    class="w-5 h-5 text-gray-800 dark:text-white hover:text-gray-600 dark:hover:text-gray-500 duration-700 hover:cursor-pointer"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="currentColor" viewBox="0 0 24 24"
                    onclick="copyToClipboard('{{ $ticket->call_invites }}')"
                    title="Copy to clipboard">
                    <path fill-rule="evenodd"
                        d="M18 3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1V9a4 4 0 0 0-4-4h-3a1.99 1.99 0 0 0-1 .267V5a2 2 0 0 1 2-2h7Z"
                        clip-rule="evenodd" />
                    <path fill-rule="evenodd"
                        d="M8 7.054V11H4.2a2 2 0 0 1 .281-.432l2.46-2.87A2 2 0 0 1 8 7.054ZM10 7v4a2 2 0 0 1-2 2H4v6a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z"
                        clip-rule="evenodd" />
                </svg>
                <div id="tooltip-content3" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-1 text-sm  text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    copy to clipboard
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <span class="block text-sm text-gray-500 dark:text-gray-400">Call Invites:</span>
            </div>
            <span
                class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->call_invites ?? '-' }}</span>
        </div>
    </div>
</section>

<!-- Documents Section -->
<section>
    <h6 class="text-lg font-semibold text-gray-600 dark:text-gray-100 border-b pb-2 mb-4">Additional Documents</h6>
    <div class="flex gap-6">
        @if (!empty($ticket['documents']))
        <div>
            <span class="block text-sm text-gray-500 dark:text-gray-400">Documents:</span>
            <div class="p-3 flex gap-3 flex-wrap">
                <div class="w-36 h-36 flex flex-col items-center justify-center border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-900">
                    <img src="{{ asset('assets/doc-icon.webp') }}" alt="Document Icon" class="w-28 h-24 mb-2" />
                    <a href="{{ asset('storage/' . $ticket['documents']) }}" target="_blank" class="text-blue-500 dark:text-blue-300 text-sm hover:underline">View</a>
                </div>
            </div>
        </div>
        @endif

        @if (!empty($ticket['ref_sign_sheet']))
        <div>
            <span class="block text-sm text-gray-500 dark:text-gray-400">Sign of Sheet:</span>
            <div class="p-3 flex gap-3 flex-wrap">
                <div class="w-36 h-36 flex flex-col items-center justify-center border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-900">
                    <img src="{{ asset('assets/doc-icon.webp') }}" alt="Signature Sheet" class="w-28 h-24 mb-2" />
                    <a href="{{ asset('storage/' . $ticket['ref_sign_sheet']) }}" target="_blank" class="text-blue-500 dark:text-blue-300 text-sm hover:underline">View</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

</div>

<!-- Footer Section -->
<div class="flex justify-end mt-6">
    <button type="button" data-drawer-hide="drawer-right-example"
        aria-controls="drawer-right-example"
        class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 focus:ring-4 focus:ring-teal-300 dark:focus:ring-teal-900">
        Close
    </button>
</div>
</div>


<!-- Table  Drawer -->
<div id="table-drawer"
    class="fixed top-[10%] right-0 z-40 h-auto p-6 transition-transform translate-x-full rounded-lg bg-white w-[26vw] dark:bg-gray-800 shadow-lg"
    tabindex="-1" aria-labelledby="drawer-right-label">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h5 id="drawer-right-label"
            class="inline-flex items-center text-lg font-semibold text-gray-700 dark:text-gray-200">
            <svg class="w-5 h-5 mr-2 text-gray-800 dark:text-white" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                    clip-rule="evenodd" />
            </svg>
            Ticket Details
        </h5>
        <button type="button" data-drawer-hide="table-drawer" aria-controls="table-drawer"
            class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>

    <!-- Scrollable Content -->
    <div class="h-[70vh] overflow-y-auto space-y-6">
        <div id="notes-container" class="p-4 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 shadow-md mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Notes</h2>
            <ul class="list-disc pl-5 space-y-2 text-gray-700 dark:text-gray-100"></ul>
        </div>

        <div id="attachment-container" class="p-4 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 shadow-md mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Attachments</h2>
            <div class="flex flex-wrap gap-2"></div>
        </div>

        <div id="break-container" class="p-4 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Break Details</h2>
            <div class="space-y-4"></div>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="flex justify-end mt-6">
        <button type="button" data-drawer-hide="table-drawer" aria-controls="table-drawer"
            class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 focus:ring-4 focus:ring-teal-300 dark:focus:ring-teal-900">
            Close
        </button>
    </div>
</div>

</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script>
    var ticket = @json($ticket);
    console.log('ticket', ticket);

    if (document.getElementById("work-table") && typeof simpleDatatables.DataTable !== 'undefined') {
        const dataTable = new simpleDatatables.DataTable("#work-table", {
            searchable: false,
            sortable: true,
            header: true,
            perPage: 5,
            paging: true,
        });
    }
    if (document.getElementById("notes-table") && typeof simpleDatatables.DataTable !== 'undefined') {
        const dataTable = new simpleDatatables.DataTable("#notes-table", {
            searchable: false,
            sortable: true,
            header: true,
            perPage: 5,
            paging: true,
        });
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Optional: Provide feedback to the user (e.g., tooltip, text change)
            alert('Copied to clipboard!');
        }).catch(function(error) {
            console.error('Error copying text: ', error);
        });
    }

    $(document).ready(function() {
        $('.note-view-btn').on('click', function() {
            const workId = $(this).data('ticket-work-id');
            console.log('workId', workId);
            let storageLink = $('#storage_link').val();

            $.ajax({
                url: `/get-work-notes/${workId}`,
                type: 'GET',
                success: function(response) {
                    console.log('Response:', response);
                    let noteHTML = '';
                    let attachmentHTML = '';
                    let breakHTML = '';
                    let breakNo = 1;

                    response.ticket_notes.forEach(note => {
                        console.log('ticket note', note)
                        if (note.note != '' && note.note != 'null' && note.note != null) {
                            noteHTML += `<li class="text-gray-700 dark:text-gray-100">${note.note}</li>`;
                        }
                        if (note.documents != '' && note.documents != 'null' && note.documents != null) {
                            JSON.parse(note.documents).forEach(attachment => {
                                attachmentHTML += `
                                <div class="w-36 h-36 flex flex-col items-center justify-center border border-gray-300 dark:border-gray-700 rounded-md  bg-gray-50 dark:bg-gray-900">
                                    <img src='/assets/doc-icon.webp' alt="Document Icon" class="w-28 h-24 mb-2" />
                                    <a href="${storageLink}/${attachment}" target="_blank" class="text-blue-500 dark:text-blue-300 text-sm hover:underline">View</a>
                                </div>`;
                            });
                        }
                    });

                    response.work_breaks.forEach(breaks => {
                        breakHTML += `
                            <div class="p-4 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 shadow-sm">
                                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 pb-2 mb-4 border-b">Break ${breakNo}</h3>
                                <ul class=" flex gap-5 justify-center items-center">
                                   <li class="flex flex-col">
                                        <div class="text-gray-700 dark:text-gray-100"> ${breaks.start_time || '-'} </div>
                                        <div class="text-gray-700 dark:text-gray-100"> Start Time </div>
                                    </li>
                                     <li class="flex flex-col">
                                        <div class="text-gray-700 dark:text-gray-100"> ${breaks.end_time || '-'} </div>
                                        <div class="text-gray-700 dark:text-gray-100"> End Time </div>
                                    </li>
                                     <li class="flex flex-col">
                                        <div class="text-gray-700 dark:text-gray-100"> ${breaks.total_break_time || '-'} </div>
                                        <div class="text-gray-700 dark:text-gray-100"> Total Time </div>
                                    </li>
                                </ul>
                            </div>`;
                        breakNo++;
                    });

                    $('#notes-container ul').html(noteHTML);
                    $('#attachment-container div').html(attachmentHTML);
                    $('#break-container div').html(breakHTML);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            });

        });
    });
</script>

@endsection