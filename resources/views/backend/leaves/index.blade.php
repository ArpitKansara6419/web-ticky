@extends('layouts.app')

@section('title', 'Engineers Leaves')

@section('styles')
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
@endsection

@section('content')

<div class="grid">
    <div class="card">
        <div class=" grid grid-cols-4 gap-4">
            <div class="flex p-3   items-center rounded-xl gap-4 bg-white border border-gray-300 shadow-sm">
                <div class="bg-[#FEF7D4] text-[#BA951E] p-2 border-2 border-[#BA951E] rounded-lg">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                    </svg>

                </div>
                <div class="flex flex-col ">
                    <p class="text-lg font-medium">Pending Leaves</p>
                    <span class="text-sm font-semibold ">
                        <strong>Paid : </strong>{{(int)$dashCounts['pending_leaves']}} &nbsp; &nbsp; &nbsp;
                        <strong>Unpaid : </strong>{{(int)$dashCounts['unpaid_pending_leaves']}} 
                    </span>
                </div>
                <!-- <div class="flex flex-col items-end ">
                    <span>
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 12h.01m6 0h.01m5.99 0h.01" />
                        </svg>
                    </span>

                    <span class="bg-[#FEF7D4] text-[#BA951E] px-2  py-1 text-xs rounded-lg ">
                        ~ 75%
                    </span>
                </div> -->
            </div>
            <div class="flex p-3 items-center rounded-xl gap-4 bg-white border border-gray-300 shadow-sm">
                <div class="bg-[#E5FEE6] text-[#00A854] border-2 border-[#00A854] p-2 rounded-lg">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                    </svg>
                </div>
                <div class="flex flex-col ">
                    <p class="text-lg font-medium">Approved Leaves</p>
                    <span class="text-sm font-semibold ">
                        <strong>Paid :</strong>  {{(int)$dashCounts['approved_leaves']}} &nbsp; &nbsp; &nbsp;
                        <strong>Unpaid :</strong> {{(int)$dashCounts['unpaid_approved_leaves']}}
                    </span>
                </div>
                <!-- <div class="flex flex-col items-end ">
                    <span>
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 12h.01m6 0h.01m5.99 0h.01" />
                        </svg>
                    </span>

                    <span class="bg-[#E5FEE6] text-[#00A854] px-2  py-1 text-xs rounded-lg ">
                        ~ 75%
                    </span>
                </div> -->
            </div>


            <div class="flex p-3   items-center rounded-xl gap-4 bg-white border border-gray-300 shadow-sm">
                <div class="bg-[#FFE9E9] text-[#EA0C15] border-2 border-[#EA0C15] p-2 rounded-lg">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                    </svg>
                </div>
                <div class="flex flex-col ">
                    <p class="text-lg font-medium">Rejected Leaves</p>
                    <span class="text-sm font-semibold ">
                        <strong>Paid : </strong>{{(int)$dashCounts['declined_leaves']}} &nbsp; &nbsp; &nbsp;
                        <strong>Unpaid : </strong>{{(int)$dashCounts['unpaid_declined_leaves']}} 
                    </span>
                </div>
                <!-- <div class="flex flex-col items-end ">
                    <span>
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 12h.01m6 0h.01m5.99 0h.01" />
                        </svg>
                    </span>

                    <span class="bg-[#FFE9E9] text-[#EA0C15] px-2  py-1 text-xs rounded-lg ">
                        ~ 75%
                    </span>
                </div> -->
            </div>


            <div class="flex p-3   items-center rounded-xl gap-4 bg-white border border-gray-300 shadow-sm">
                <div class="bg-[#E8EFEE] text-[#003AA6] border-2 border-[#003AA6] p-2 rounded-lg">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                    </svg>
                </div>
                <div class="flex flex-col ">
                    <p class="text-lg text-nowrap font-medium">Total Leaves Applied</p>
                    <span class="text-sm font-semibold ">
                        <strong>Paid : </strong>{{(int)$dashCounts['total_leaves']}} &nbsp; &nbsp; &nbsp;
                        <strong>Unpaid : </strong>{{(int)$dashCounts['unpaid_total_leaves']}}
                    </span>
                </div>
                <!-- <div class="flex flex-col items-end ">
                    <span>
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 12h.01m6 0h.01m5.99 0h.01" />
                        </svg>
                    </span>

                    <span class="bg-[#E8EFEE] text-[#003AA6] px-2  py-1 text-xs rounded-lg ">
                        ~ 75%
                    </span>
                </div> -->
            </div>
        </div>

        <div class="grid grid-cols-12 mt-5 gap-4">
            <div class="col-span-6">
                <div class="p-3 bg-white h-full  shadow-sm rounded-xl">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold mb-4">Engineer Leaves</h2>

                        <div class="flex gap-4 items-center mb-4">
                            <!-- <input type="text" placeholder="Search Engineer" class="border rounded-lg px-4 py-2 w-48 shadow-sm"> -->
                            <!-- <button class="border rounded-lg p-2 shadow-sm">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                                </svg>

                            </button> -->
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-1.5 py-1 text-[.73rem] text-left font-medium">Sr. no</th>
                                    <th class="px-1.5 py-1 text-[.73rem] text-left font-medium">Engineer</th>
                                    <th class="px-1.5 py-1 text-[.73rem] font-medium">
                                        <!-- Yearly Allocated -->
                                        Yearly Allocated Leaves
                                    </th>
                                    <th class="px-1.5 py-1 text-[.73rem] font-medium">
                                        <!-- Opening Balance -->
                                        Carry Forward From Last Year
                                    </th>
                                    <th class="px-1.5 py-1 text-[.73rem] font-medium">
                                        <!-- Opening Balance -->
                                        Previous Month Balance
                                    </th>
                                    <th class="px-1.5 py-1 text-[.73rem] font-medium">
                                        <!-- Credited Month -->
                                        Credited This Month
                                    </th>
                                    <th class="px-1.5 py-1 text-[.73rem] font-medium">Paid Leaves</th>
                                    <th class="px-1.5 py-1 text-[.73rem] font-medium">Unpaid Leaves</th>
                                    <th class="px-1.5 py-1 text-[.73rem] font-medium">Balance</th>
                                    {{-- <th class="px-1.5 py-1 text-[.73rem] font-medium">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$leaveBalance->isEmpty())
                                @foreach ($leaveBalance as $key => $leave )
                                <tr class="text-center border-b last:rounded-b-xl">
                                    <td class="px-2 py-1 text-xs">{{ $key + 1 }} </td>
                                    <td class="px-2 py-1 text-xs">
                                        <div class="flex-col">
                                            @if($leave?->engineer)
                                            <p class="text-sm text-left">
                                                {{ $leave->engineer->first_name }} {{ $leave->engineer->last_name }}
                                            </p>
                                            <p class="text-gray-500 text-left  hover:text-primary">
                                                <a href="{{ route('engg.show', $leave->engineer->id) }}">
                                                    #{{ $leave->engineer->engineer_code }}
                                                </a>
                                            </p>
                                            @else
                                            <p class="text-sm text-gray-500">N/A</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-2 py-1 text-xs">{{ $leave?->total_yearly_alloted }} </td>
                                    <td class="px-2 py-1 text-xs">{{ $leave?->opening_balance_from_past_year }} </td>
                                    <td class="px-2 py-1 text-xs">
                                        <!-- {{ $leave?->leave_credited_this_month }} -->
                                        @php
                                        $previouse_month_leave = getPreviousMonthLeave($leave?->balance,$leave?->leave_credited_this_month);
                                        $previouse_month_leave = $previouse_month_leave + $leave?->opening_balance_from_past_year;
                                        @endphp
                                        {{ number_format($previouse_month_leave, 2) }}
                                    </td>
                                    <td class="px-2 py-1 text-xs">{{ $leave?->leave_credited_this_month }}</td>
                                    <td class="px-2 py-1 text-xs">{{ $leave?->total_paid_leave_used }}</td>
                                    <td class="px-2 py-1 text-xs">{{ $leave?->total_unpaid_leave_used }}</td>
                                    <td class="px-2 py-1 text-xs">
                                        {{ $previouse_month_leave + $leave?->leave_credited_this_month -$leave?->total_paid_leave_used }}</td>
                                    {{-- <td class="px-2 py-1 text-xs">
                                        <button type="button" title="View"
                                            class="bg-[#e4e4fc]  font-medium rounded-lg text-sm px-2 py-2 text-center  flex">
                                            <svg class="w-6 h-6 text-primary" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-width="2"
                                                    d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                <path stroke="currentColor" stroke-width="2"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </td> --}}
                                </tr>
                                @endforeach
                                @else
                                <tr class="text-center border-b last:rounded-b-xl">
                                    <td colspan="9">
                                        <p class="p-2">
                                            No record found.
                                        </p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-span-6 space-y-5">
                <div class="p-3 bg-white  shadow-sm rounded-xl">
                    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-blue-800 hover:text-blue-800 dark:text-blue-800 dark:hover:text-blue-800 border-blue-600 dark:border-blue-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg pending-tab" id="profile-styled-tab" data-tabs-target="#styled-pending" type="button" role="tab" aria-controls="pending" aria-selected="false">Pending</button>
                            </li>
                            <li role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 approved-tab" id="approved-styled-tab" data-tabs-target="#styled-approved" type="button" role="tab" aria-controls="approved" aria-selected="false">Approved</button>
                            </li>
                            <li role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 rejected-tab" id="rejected-styled-tab" data-tabs-target="#styled-rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">Rejected</button>
                            </li>

                        </ul>
                    </div>
                    <div id="default-styled-tab-content">
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-pending" role="tabpanel" aria-labelledby="pending-tab">
                            <div class="table-responsive">
                                <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                                    id="pending-table">

                                </table>
                            </div>
                            
                        </div>
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-approved" role="tabpanel" aria-labelledby="approved-tab">
                            <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                                id="approved-table">

                            </table>
                           
                        </div>
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-rejected" role="tabpanel" aria-labelledby="rejected-tab">
                            <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                                id="rejected-table">

                            </table>
                           
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
    
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
<script>
    var ENGINEER_LEAVE_DATATABLE = "{{ route('engineerLeave.dataTable') }}";
</script>
@vite([
'resources/js/engineer_leave/pending.js',
'resources/js/engineer_leave/accepted.js',
'resources/js/engineer_leave/rejected.js',
])
@endsection