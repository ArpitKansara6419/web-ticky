@extends('layouts.app')

@section('title', 'Engineer Payout')

@section('content')

    <div class="">
        <div class="card">
            <input type="hidden" id="storage_link" name="storage_link" value="<?php echo asset('storage/'); ?>">
            <div class="card-header mt-4 flex justify-between items-center">
                <h4 class="text-2xl font-extrabold flex items-center gap-2">
                    <svg class="w-7 h-7 text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.5 12A2.5 2.5 0 0 1 21 9.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 1 0 5V17a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                    </svg>
                    Ticket Details
                    <button class="hidden" data-modal-target="customer-ticket-summary-modal"
                        data-modal-toggle="customer-ticket-summary-modal">

                    </button>
                </h4>
                <div class="text-center">
                </div>
            </div>
            {{-- card-body  --}}
            <div class="card-body">
                <div class="gap-6 grid bg-white shadow-sm rounded-lg border border-gray-300 p-5">
                    <div class="grid-span-12 border border-gray-300 shadow-sm rounded-lg">
                        <div class="flex bg-gray-200 rounded-t-lg p-4 justify-between">
                            <div class="flex gap-10">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Ticket Code</span>
                                    <strong class="font-semibold text-md text-primary">{{ $ticket['ticket_code'] }}</strong>
                                </div>

                                <div class="flex flex-col">
                                    <strong class="text-gray-500 font-extralight text-4xl">|</strong>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Start Date</span>
                                    <strong
                                        class="font-semibold text-md">{{ \Carbon\Carbon::parse($ticket->ticket_start_date_time)->format('d-m-Y') }}
                                    </strong>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">End Date</span>
                                    <strong
                                        class="font-semibold text-md">{{ \Carbon\Carbon::parse($ticket->ticket_end_date_time)->format('d-m-Y') }}</strong>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Timezone</span>
                                    <strong class="font-semibold text-md">
                                        {{ $ticket->timezone ?? '--' }}
                                        ({{ fetchTimezone($ticket->timezone)['gmtOffsetName'] ?? '' }})
                                    </strong>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Task Start Time</span>
                                    <strong
                                        class="font-semibold text-md text-red-700">{{ $ticket->ticket_time_tz }}</strong>
                                </div>
                            </div>
                            <div>
                                @if ($ticket['status'] === 'inprogress')
                                    <x-badge type="inprogress" label="In Progress" class="" />
                                @elseif($ticket['status'] === 'hold')
                                    <x-badge type="hold" label="On Hold" class="" />
                                @elseif($ticket['status'] === 'break')
                                    <x-badge type="break" label="On Break" class="" />
                                @elseif($ticket['status'] === 'close')
                                    <x-badge type="close" label="Close" class="" />
                                @elseif($ticket['status'] === 'expired')
                                    <x-badge type="expired" label="Expired" class="" />
                                @else
                                    @if ($ticket['engineer_status'] == 'offered')
                                        <x-badge type="offered" label="Offered" class="" />
                                    @elseif($ticket['engineer_status'] == 'accepted')
                                        <x-badge type="accepted" label="Accepted" class="" />
                                    @elseif($ticket['engineer_status'] == 'accepted' && date('Y-m-d') == $ticket['task_start_date'])
                                        <x-badge type="expired" label="Not Started" class="" />
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="p-4 rounded-b-lg flex justify-between ">

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Customer'Id</span>
                                <strong class="font-semibold text-md">{{ $ticket['customer']['customer_code'] }}</strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Customer Name</span>
                                <strong class="font-semibold text-md">{{ $ticket['client_name'] }}</strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Task Name</span>
                                <strong class="font-semibold text-md">{{ $ticket->task_name ?? '-' }}</strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Engineer Code</span>
                                <strong class="font-semibold text-md">{{ $ticket['engineer']['engineer_code'] }}</strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Assigned Engineer </span>
                                <strong
                                    class="font-semibold text-md">{{ $ticket['engineer']['first_name'] . ' ' . $ticket['engineer']['last_name'] ?? '-' }}</strong>
                            </div>

                        </div>
                        <div class="p-4  rounded-b-lg flex justify-between ">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Scope Of Work</span>
                                <strong class="font-semibold text-md">{{ $ticket['scope_of_work'] ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-2">
                        <div class="col-span-5 border border-gray-300 shadow-sm rounded-lg">
                            <div class="flex bg-gray-200 rounded-t-lg p-3 justify-between items-center">
                                <div class="text-[1rem] font-semibold">Additional Costs</div>
                                <div class="grid grid-cols-2 gap-1">
                                    <div
                                        class="bg-blue-100 text-primary border border-primary text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-700 dark:text-blue-300">
                                        Hourly Rate <span class="ms-4 text-xs">{{ $ticket_currency }}
                                            {{ $lead?->hourly_rate ?? 0 }}</span> </div>

                                    <div
                                        class="bg-blue-100 text-primary border border-primary text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-700 dark:text-blue-300">
                                        Full Day Rate <span class="ms-4 text-xs">{{ $ticket_currency }}
                                            {{ $lead?->full_day_rate ?? 0 }}</span> </div>

                                    <div
                                        class="bg-blue-100 text-primary border border-primary text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-700 dark:text-blue-300">
                                        Half Day Rate <span class="ms-4 text-xs">{{ $ticket_currency }}
                                            {{ $lead?->half_day_rate ?? 0 }}</span> </div>

                                    <div
                                        class="bg-blue-100 text-primary border border-primary text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-700 dark:text-blue-300">
                                        Monthly Rate <span class="ms-4 text-xs">{{ $ticket_currency }}
                                            {{ $lead?->monthly_rate ?? 0 }}</span> </div>
                                </div>
                            </div>
                            <div class="p-4 rounded-b-lg flex justify-between ">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Rate Type</span>
                                    <strong
                                        class="font-semibold text-md">{{ ucfirst($ticket->rate_type) ?? '-' }}</strong>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Rate</span>
                                    <strong class="font-semibold text-md">{{ $ticket_currency }}
                                        {{ $ticket->standard_rate ?? 0 }}</strong>
                                </div>


                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Travel Cost</span>
                                    <strong class="font-semibold text-md">{{ $ticket_currency }}
                                        {{ $ticket->travel_cost ?? 0 }}</strong>
                                </div>


                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Tool Cost</span>
                                    <strong class="font-semibold text-md">{{ $ticket_currency }}
                                        {{ $ticket->tool_cost ?? 0 }}</strong>
                                </div>

                            </div>
                        </div>

                        <div class="col-span-3 border border-gray-300 shadow-sm rounded-lg">
                            <div class="bg-gray-200 rounded-t-lg p-3">
                                <div class="text-[1rem] font-semibold">Additional Documents</div>
                            </div>
                            <div class="p-4 rounded-b-lg flex justify-between ">

                                <div>
                                    @if (!empty($ticket['documents']))
                                        <a href="{{ !empty($ticket['documents']) ? asset('storage/' . $ticket['documents']) : '' }}"
                                            target="_blank">
                                            <div class="flex items-center">
                                                <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                                <span class="text-gray-500 text-sm">Documents</span>
                                            </div>
                                        </a>
                                    @else
                                        <div class="flex items-center">
                                            <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                            <span class="text-gray-500 text-sm">Documents</span>
                                        </div>
                                    @endif
                                    @if (empty($ticket['documents']))
                                        <p class="text-gray-500 text-sm text-center mt-3">
                                            No document found.
                                        </p>
                                    @endif
                                </div>

                                <div>
                                    @if (!empty($ticket['ref_sign_sheet']))
                                        <a href="{{ !empty($ticket['ref_sign_sheet']) ? asset('storage/' . $ticket['ref_sign_sheet']) : '#' }}"
                                            target="_blank">
                                            <div class="flex items-center">
                                                <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                                <span class="text-gray-500 text-sm">Sign of Sheet</span>
                                            </div>
                                        </a>
                                    @else
                                        <div class="flex items-center">
                                            <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                            <span class="text-gray-500 text-sm">Sign of Sheet</span>
                                        </div>
                                    @endif
                                    @if (empty($ticket['ref_sign_sheet']))
                                        <p class="text-gray-500 text-sm text-center mt-3">
                                            No document found.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-span-4 border border-gray-300 shadow-sm rounded-lg">
                            <div class="bg-gray-200 rounded-t-lg p-3 ">
                                <div class="text-[1rem] font-semibold">Location</div>
                            </div>
                            <div class="p-4 rounded-b-lg flex flex-col gap-2">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Address Line 1</span>
                                    <strong class="font-semibold text-md">{{ $ticket->add_line_1 ?? '-' }} </strong>
                                </div>
                                <div class="flex gap-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-500">City</span>
                                        <strong class="font-semibold text-md">{{ $ticket->city ?? '-' }} </strong>
                                    </div>

                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-500">Country</span>
                                        <strong class="font-semibold text-md">{{ $ticket->country ?? '-' }} </strong>
                                    </div>

                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-500">Zip Code</span>
                                        <strong class="font-semibold text-md">{{ $ticket->zipcode ?? '-' }} </strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="grid-span-12 border border-gray-300 shadow-sm rounded-lg">
                        <div class="flex bg-gray-200 rounded-t-lg p-4 justify-between">
                            <div class="text-[1rem] font-semibold">Extra Information </div>
                        </div>
                        <div class="p-4  rounded-b-lg flex justify-between ">
                            <div class="flex flex-col">
                                <span class="font-semibold text-lg">POC Details</span>
                                <strong
                                    class="text-sm text-gray-500 font-extralight">{{ $ticket->poc_details ?? ' NA ' }}</strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-semibold text-lg">RE Details</span>
                                <strong
                                    class="text-sm text-gray-500 font-extralight">{{ $ticket->re_details ?? ' NA ' }}</strong>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-semibold text-lg">Call Invites</span>
                                <strong
                                    class="text-sm text-gray-500 font-extralight">{{ $ticket->call_invites ?? ' NA ' }}</strong>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-header mt-4 flex justify-between items-center">
                    <h4 class="text-2xl font-extrabold flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3M3.22302 14C4.13247 18.008 7.71683 21 12 21c4.9706 0 9-4.0294 9-9 0-4.97056-4.0294-9-9-9-3.72916 0-6.92858 2.26806-8.29409 5.5M7 9H3V5" />
                        </svg>
                        Ticket Work History
                    </h4>
                    <div>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded toggleModal"
                        type="button">
                        ADJUST NEW WORK HOURS
                    </button>
                    </div>
                </div>
                <div class="my-4 border-b border-gray-300 dark:border-gray-700">
                    <div class="border border-1 border-gray-200 dark:border-gray-700 p-4 rounded-lg overflow-hidden">
                        <table id="work-table" class="w-full border-collapse">
                            <thead>
                                <tr>
                                    <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            SR.No
                                        </span>
                                    </th>
                                    <th class="bg-gray-200 text-gray-800  dark:bg-gray-900">
                                        <span class="flex items-center">
                                            Ticket Code
                                        </span>
                                    </th>
                                    <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            Engineer
                                        </span>
                                    </th>
                                    <!-- <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            Start Date
                                        </span>
                                    </th>
                                    <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            Start Time
                                        </span>
                                    </th>
                                    <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            End Date
                                        </span>
                                    </th>
                                    <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            End Time
                                        </span>
                                    </th> -->
                                    <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            Work Logs
                                        </span>
                                    </th>
                                    <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            Total Time
                                        </span>
                                    </th>
                                    <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            Total Break Time
                                        </span>
                                    </th>
                                    <th class="bg-gray-200 text-gray-800 dark:bg-gray-900">
                                        <span class="flex items-center">
                                            Total Actual Time
                                        </span>
                                    </th>
                                    <th class="bg-gray-200 text-gray-800  dark:bg-gray-900">
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
                                        <td class="capitalize text-gray-800 font-medium">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="capitalize text-gray-800 font-medium">
                                            {{ $ticket_work->ticket->ticket_code }}
                                        </td>
                                        <td class="capitalize text-gray-800 font-medium">
                                            {{ $ticket['engineer']['first_name'] . ' ' . $ticket['engineer']['last_name'] ?? '-' }}
                                        </td>
                                        <!-- <td class="capitalize text-gray-800 font-medium">
                                        <p class="leading-4">{{ $ticket_work['work_start_date'] ?? '-' }}</p>
                                    </td>
                                    <td class="capitalize text-gray-800 font-medium">
                                        <p class="leading-4">{{ $ticket_work['start_time'] ?? '-' }}</p>
                                    </td>
                                    <td class="capitalize text-gray-800 font-medium">
                                        <p class="leading-4">{{ $ticket_work['work_end_date'] ?? '-' }}</p>
                                    </td>
                                    <td class="capitalize text-gray-800 font-medium">
                                        <p class="leading-4">{{ $ticket_work['end_time'] ?? '-' }}</p>
                                    </td> -->
                                        <td class="capitalize text-gray-800 font-medium">
                                            @php
                                                $work_start_dt = '';
                                                $work_start_time = '';
                                                if ($ticket_work['work_start_date']) {
                                                    $work_start_dt = utcToTimezone(
                                                        $ticket_work['work_start_date'],
                                                        $ticket['timezone'],
                                                    )->format('Y-m-d');
                                                    if ($ticket_work['start_time']) {
                                                        $work_start_time = utcToTimezone(
                                                            $ticket_work['work_start_date'] .
                                                                ' ' .
                                                                $ticket_work['start_time'],
                                                            $ticket['timezone'],
                                                        )->format('H:i:s');
                                                    }
                                                }

                                                $work_end_dt = '';
                                                $work_end_time = '';
                                                if ($ticket_work['work_end_date']) {
                                                    $work_end_dt = utcToTimezone(
                                                        $ticket_work['work_end_date'],
                                                        $ticket['timezone'],
                                                    )->format('Y-m-d');
                                                    if ($ticket_work['end_time']) {
                                                        $work_end_time = utcToTimezone(
                                                            $ticket_work['work_end_date'] .
                                                                ' ' .
                                                                $ticket_work['end_time'],
                                                            $ticket['timezone'],
                                                        )->format('H:i:s');
                                                    }
                                                }
                                            @endphp
                                            <p class="text-nowrap">Start : {{ $work_start_dt ?? '-' }}
                                                {{ $work_start_time ?? '-' }}</p>
                                            <p class="text-nowrap">End &nbsp; : {{ $work_end_dt ?? '-' }}
                                                {{ $work_end_time ?? '-' }}</p>
                                            <!-- <p class="">Start : {{ $ticket_work['work_start_date'] ?? '-' }} {{ $ticket_work['start_time'] ?? '-' }}</p>
                                        <p class="">End &nbsp; : {{ $ticket_work['work_end_date'] ?? '-' }} {{ $ticket_work['end_time'] ?? '-' }}</p> -->
                                        </td>
                                        <td class="capitalize text-gray-800 font-medium">
                                            <p class="leading-4"> {{ $ticket_work['total_work_time'] ?? '-' }}</p>
                                        </td>
                                        <td class="capitalize text-gray-800 font-medium">
                                            <p class="">
                                                {{ $ticket_work['total_work_time'] != '00:00:00' && !empty($ticket_work['total_work_time']) && !empty($ticket_work['total_break_time_seconds']) ? gmdate('H:i:s', $ticket_work['total_break_time_seconds']) : '-' }}
                                                <!-- <br/> -->
                                                @include('backend.ticket.work-break-tooltip', [
                                                    'ticket_breaks' => $ticket_work['breaks'],
                                                ])
                                            </p>
                                        </td>
                                        <td class="capitalize text-gray-800 font-medium">
                                            @php
                                                /*$break_time = "00:00:00";
                                    if($ticket_work['total_work_time'] != "00:00:00" && !empty($ticket_work['total_work_time']) && !empty($ticket_work['total_break_time_seconds']))
                                    {
                                        $break_time =  gmdate('H:i:s',$ticket_work['total_break_time_seconds']);
                                    }
                                    $actual_work_time = getActualWorkTime($ticket_work['total_work_time'], $break_time);*/
                                            @endphp
                                            @if ($ticket_work['total_work_time'] === '00:00:00')
                                                <p>-</p>
                                            @else
                                                <p class=""> {{ $ticket_work['total_work_time'] }}</p>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="flex gap-2">
                                                <button type="button" title="Engineer"
                                                    data-ticket-work-id="{{ $ticket_work->id }}"
                                                    data-modal-target="default-modal" data-modal-toggle="default-modal"
                                                    id="notesViewBtn_{{ $ticket_work->id }}"
                                                    class="open-detail-model note-view-btn   text-white bg-[#e4e4fc]  font-medium rounded-lg text-sm px-2 py-2 text-center  flex">
                                                    <svg class="w-5 h-5 text-primary" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-width="2"
                                                            d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                        <path stroke="currentColor" stroke-width="2"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                    <span class="sr-only">Icon description</span>
                                                </button>

                                                @php
                                                    $custPayable = checkCustomerPayable($ticket_work->id);
                                                @endphp
                                                @if ($custPayable)
                                                    <button type="button"
                                                        class="open-ticket-work-modal bg-blue-100  font-medium rounded-lg text-sm px-[.6rem] py-[.4rem] text-center  flex"
                                                        data-ticket-work-id="{{ $ticket_work->id }}" title="Customer">
                                                        <svg class="w-6 h-6 text-primary" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-width="2"
                                                                d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                            <path stroke="currentColor" stroke-width="2"
                                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                        <span class="sr-only">Icon description</span>
                                                    </button>
                                                @endif
                                                <button type="button"
                                                    class="font-medium bg-blue-100 rounded-lg text-sm px-[.6rem] py-[.4rem] text-center  flex open_Edit_adjust_model"
                                                    data-ticket_work_id="{{ $ticket_work->id }}" title="EDIT">
                                                    ADJUST
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- Main modal -->
    <div id="default-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full  max-w-[70%] max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Engineers Ticket Summary
                    </h3>
                    <button type="button" id="btn-close-model" data-modal-hide="default-modal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div id="TicketDetailPop" class="p-6 space-y-4 ">

                </div>
            </div>
        </div>
    </div>


    @include('backend.ticket.customer_ticket_summary_modal')

    @include('backend.ticket.edit_work_hour_adjust_modal')
    @include('backend.ticket.add_work_hour_adjust_modal')

@endsection

@section('scripts')


    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        var ticket = @json($ticket);


        $(document).ready(function() {

            const instanceOptions = {
                id: 'default-modal',
                override: true
            };

            const $targetEl = document.getElementById('default-modal');
            const modal = new Modal($targetEl, {}, instanceOptions);

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

            $(document).on('click', '.note-view-btn', function() {
                const workId = $(this).data('ticket-work-id');
                console.log('workId', workId);
                let storageLink = $('#storage_link').val();
            });

            document.querySelector("#work-table").addEventListener("datatable.page", function() {
                console.log("Page changed");
            });

            $(document).on("click", "#btn-close-model", function() {
                modal.toggle();
            })

            //  open pop
            $(document).on("click", ".open-detail-model", function(event) {
                console.log("open-detail-model clicked");

                let ticketId = $(this).data("ticket-work-id"); // Get Ticket ID using jQuery

                console.log("Ticket ID:", ticketId);

                if (!ticketId) return;

                $.ajax({
                    url: "{{ route('ticket.fetchPopup') }}",
                    method: "GET",
                    data: {
                        id: ticketId
                    },
                    success: function(response) {
                        $("#TicketDetailPop").html(response.html);
                        initializeTooltips();
                        modal.toggle();
                        // $("#default-modal").modal("show"); // Ensure this matches your modal library
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Error fetching ticket details. Please try again.");
                    },
                });
            });


            $(document).on('click', '.edit-btn, .save-btn', function() {

                let workId = $("#default-modal .payout-id-hidden").val(); // Get ID from modal

                console.log("Final Payout ID:", workId); // Debugging

                let target = $(this);
                let row = target.closest("tr");
                let amountText = row.find(".amount-text");
                let amountInput = row.find(".amount-input");
                let editBtn = row.find(".edit-btn");
                let saveBtn = row.find(".save-btn");

                if (target.hasClass("edit-btn")) {
                    // Enable editing mode
                    amountText.addClass("hidden");
                    amountInput.removeClass("hidden").focus();
                    editBtn.addClass("hidden");
                    saveBtn.removeClass("hidden");
                } else if (target.hasClass("save-btn")) {
                    // Save new value
                    let newValue = amountInput.val();
                    amountText.text(newValue).removeClass("hidden");
                    amountInput.addClass("hidden");
                    editBtn.removeClass("hidden");
                    saveBtn.addClass("hidden");

                    // Send AJAX request
                    let key = row.find(".amount-cell").data("key");
                    console.log('key --', key),

                        $.ajax({
                            url: "{{ route('updateAmount') }}",
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            data: {
                                workId: workId,
                                key: key,
                                amount: newValue,
                                type: "engineer"

                            },
                            success: function(response) {
                                console.log("Updated successfully:", response);
                                alert("Amount Updated Successfully");

                            },
                            error: function(xhr, status, error) {
                                console.error("Error updating:", error);
                            }
                        });
                }
            })

        });
        var STORAGE_LINK = "{{ asset('storage') }}";
        var CUSTOMER_PAYOUT_FETCH_POPUP = "{{ route('customer-payout.fetchPopup') }}";
        var UPDATE_AMOUNT_CUSTOMER = "{{ route('updateAmount') }}";

        var EDIT_TICKET_WORK_ADJUST = "{{ route('editTicketWork', '') }}/";
        var UPDATE_TICKET_WORK_ADJUST = "{{ route('updateTicketWorkAdjust', '') }}/";
    </script>
    @vite([
        'resources/js/tickets/detail.js', 
        'resources/js/tickets/customer_ticket_summary_modal.js',
        'resources/js/tickets/adjust.js'
        ])
@endsection
