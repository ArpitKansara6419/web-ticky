@extends('layouts.app')

@section('title', 'Admins Dashboard')

@section('content')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
    .scrollbar-thin {
        scrollbar-width: thin;
    }

    .scrollbar-thumb-dark-500 {
        scrollbar-color: #a2d7f3;
        /* Teal thumb with light gray track */
    }

    /* For WebKit Browsers */
    .scrollbar-thin::-webkit-scrollbar {
        width: 8px;
        /* Set scrollbar width */
    }

    .scrollbar-thin::-webkit-scrollbar-track {
        background: #e5e7eb;
        /* Light gray track */
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #14b8a6;
        /* Teal thumb */
        border-radius: 10px;
        /* Rounded edges */
        border: 2px solid #e5e7eb;
        /* Adds padding effect with track color */
    }

    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #0f766e;
        /* Darker teal on hover */
    }

    .fc .fc-daygrid-body-unbalanced .fc-daygrid-day-events {
        min-height: 6em !important;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for Firefox */
    .scrollbar-hide {
        scrollbar-width: none;
    }

    /* Optional: Ensure smooth scrolling */
    .scrollbar-hide {
        -webkit-overflow-scrolling: touch;
    }

    /* .event-dot {
        width: 6px;
        height: 6px;
        background-color: red;
        border-radius: 50%;
        display: block;
        margin: auto;
    } */

    .fc-daygrid-event {
        display: flex;
        justify-content: center;
        align-items: center;
        line-height: 0;
    }
    /* .event-dot {
      width: 6px;
      height: 6px;
      background-color: #02307b;
      border-radius: 50%;
      margin: 2px auto 0;
    } */
    .fc-daygrid-day-frame {
        position: relative; /* Needed for absolute positioning of the dot */
    }

    .event-dot {
        position: absolute;
        left: 49%; /* Center horizontally */
        transform: translateX(-50%); /* Fine-tune centering */
        top: 38px; /* Adjust based on your date's height; place below the date */
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #02307b; /* Match FullCalendar's event color or customize */
        pointer-events: none; /* Prevent hover interference */
        z-index: 9;
    }
    .fc-day-today .event-dot{
        background-color: white;
    }

    /* Ensure no hover effects disrupt the dot */
    .fc-daygrid-day:hover .event-dot {
        transform: translateX(-50%); /* Lock position on hover */
    }
</style>
@php
    $currentMonth = now()->format('Y-m'); // e.g., "2025-05"
@endphp
<div class="grid md:grid-cols-5 mb-4">
    <div class="relative md:col-start-5">
        <x-input-label for="filter_date" :value="__('Filter')" />
        <input type="month" id="filter_date" name="filter_date"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
            placeholder="Filter Month-Year Wise"
            value="{{ $currentMonth }}"
        />
    </div>
</div>
<div class="grid md:grid-cols-12 gap-3">
    @php
    $currencySymbols = [
    'dollar' => '$',
    'rupee' => '₹',
    'euro' => '€',
    'pound' => '£',
    'zloty' => 'zł'
    ];
    @endphp

    <div class="col-span-12">
        <div class=" grid grid-cols-12 gap-3">
            <div class="col-span-3 p-3 flex  rounded-xl gap-0 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-700 shadow-lg">
                <div class="bg-[#FEF7D4]  text-[#BA951E]  h-fit my-auto  p-2 border-2 border-[#BA951E] rounded-lg">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                    </svg>

                </div>
                <div class="flex flex-col gap-2 justify-center ps-4 w-full">
                    <a href="{{route('all-customer.payout')}}">
                        <p class="text-[.9rem] font-semibold dark:text-gray-200">Customer Receivables </p>
                        <div class="flex justify-between" id="states_customer_receivable">
                            @foreach ($finalMonthlyCustomerPayable as $engineerPaySum)
                            <span class="text-[1rem] text-nowrap dark:text-gray-300">{{$engineerPaySum['symbol']}} {{$engineerPaySum['total_payable'] ?? 0 }}</span>
                            @endforeach
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-span-3 p-3 flex rounded-xl gap-0 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-700 shadow-lg">
                <div class="bg-[#E5FEE6] text-[#00A854] border-2 border-[#00A854] p-2 rounded-lg h-fit my-auto">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                    </svg>
                </div>
                <div class="flex flex-col gap-2 justify-center ps-4 w-full">
                    <a href="{{route('alleng.payout')}}">
                        <p class="text-[.9] font-semibold dark:text-gray-200">Engineer Payout</p>
                        <div class="flex justify-between" id="states_engineer_payouts">
                            @foreach ($finalEngineerGrossPay as $currencyTotal)
                            <span class="text-[1rem] text-nowrap dark:text-gray-300">{{$currencyTotal['symbol']}} {{$currencyTotal['total_amount']}}</span>
                            @endforeach
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-span-2 p-3 flex rounded-xl gap-0 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-700 shadow-lg">
                <div class="bg-[#fae5ff] text-[#be0cea] border-2 border-[#be0cea] p-2 rounded-lg h-fit my-auto">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
                <div class="flex flex-col gap-2 justify-center ps-4 w-full">
                    <a href="{{route('lead.index')}}">
                        <p class=" dark:text-gray-200 text-[.9rem] font-semibold">Leads</p>
                        <span class="text-lg font-semibold dark:text-gray-300 " id="states_leads">{{ $insights['leads'] }}</span>
                    </a>
                </div>
                <span class="w-full  flex justify-end items-end flex-col">
                    <a href="/lead/create" class="bg-green-100 text-green-400 font-semibold text-xs w-fit text-nowrap   px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Add Leads</a>
                </span>
            </div>

            <div class="col-span-2 p-3  flex   rounded-xl gap-0 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-700 shadow-lg">
                <div class="bg-[#E8EFEE] text-[#003AA6] border-2 border-[#003AA6] p-2 rounded-lg w-fit  my-auto">
                    <svg class="w-5 h-5"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M18.5 12A2.5 2.5 0 0 1 21 9.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 1 0 5V17a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                    </svg>
                </div>
                <div class="flex flex-col gap-2 justify-center ps-4">
                    <a href="{{route('ticket.index')}}">
                        <p class=" dark:text-gray-200 text-[.9rem] text-nowrap font-semibold">Ticket</p>
                        <span class="text-lg font-semibold dark:text-gray-300 " id="states_tickets">{{ $insights['tickets'] }}</span>
                    </a>
                </div>
                <span class="w-full  flex  justify-end items-end flex-col">
                    <a href="/ticket/create" class="bg-green-100 text-green-400 w-fit  font-semibold text-xs text-nowrap  px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Add Tickets</a>
                </span>
            </div>

            <div class="col-span-2 p-3   flex  rounded-xl gap-0 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-700 shadow-lg">
                <div class="bg-red-100 text-red-500 border-2 border-red-500 p-2 rounded-lg w-fit  my-auto">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12.512 8.72a2.46 2.46 0 0 1 3.479 0 2.461 2.461 0 0 1 0 3.479l-.004.005-1.094 1.08a.998.998 0 0 0-.194-.272l-3-3a1 1 0 0 0-.272-.193l1.085-1.1Zm-2.415 2.445L7.28 14.017a1 1 0 0 0-.289.702v2a1 1 0 0 0 1 1h2a1 1 0 0 0 .703-.288l2.851-2.816a.995.995 0 0 1-.26-.189l-3-3a.998.998 0 0 1-.19-.26Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M7 3a1 1 0 0 1 1 1v1h3V4a1 1 0 1 1 2 0v1h3V4a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h1V4a1 1 0 0 1 1-1Zm10.67 8H19v8H5v-8h3.855l.53-.537a1 1 0 0 1 .87-.285c.097.015.233.13.277.087.045-.043-.073-.18-.09-.276a1 1 0 0 1 .274-.873l1.09-1.104a3.46 3.46 0 0 1 4.892 0l.001.002A3.461 3.461 0 0 1 17.67 11Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex flex-col gap-2 justify-center ps-4">
                    <a href="{{route('leave.dashboard')}}">
                        <p class=" dark:text-gray-200 text-[.9rem] text-nowrap font-semibold">Approved Leaves</p>
                        <span class="text-lg font-semibold dark:text-gray-300 " id="states_approved_leaves">{{ (int)$leaveApproved }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid md:col-span-6 h-full items-stretch gap-0">
        <div class="grid h-fit p-3 bg-white dark:bg-gray-700 rounded-lg shadow-lg">
            <div id='calendar' class="text-gray-900 dark:text-gray-300"></div>
            <div id="events">
                <!--  -->
                <ul class="flex flex-col gap-3 p-2 h-[53vh] overflow-auto scrollbar-hide" role="tabpanel" id="events-ul-all"
                    aria-labelledby="all-tab">
                    <!-- <li class="flex p-2 justify-between border border-gray-300 items-center rounded-lg">
                            <div class="flex justify-center items-center gap-2">
                                <img src="/assets/ticky-logo-new.png" class="w-10 h-10 rounded-full" alt="">
                                <div>
                                    <div>Jenny Wilson completed new task</div>
                                    <div class="text-[.7rem] font-medium text-gray-500">Lorem ipsum dolor sit amet.</div>
                                </div>
                            </div>
                            <span class="text-[.7rem] font-medium text-gray-500">5 min ago</span>
                        </li> -->
                </ul>
                <!--  -->
            </div>
        </div>
    </div>

    <div class="grid md:col-span-6 h-full items-stretch gap-3">
        <div class="bg-white dark:bg-gray-700 dark:border-gray-800 rounded-lg border-gray-100 shadow-lg p-4">
            <div class="flex justify-between p-2 dark:text-gray-200">
                <p> All Request
                    <span class="bg-indigo-100 w-fit p-[.3rem] text-indigo-800 text-[.69rem] font-bold  rounded-full dark:bg-indigo-900 dark:text-indigo-300"> {{ str_pad($requestList->count(), 2, '0', STR_PAD_LEFT) }}
                    </span>
                </p>
                <span class="text-sm font-semibold text-gray-600 dark:text-gray-300">See All</span>
            </div>
            <ul class="flex flex-col gap-3 p-2 h-[36vh] overflow-auto">
                @if(!$requestList->isEmpty())
                @foreach($requestList as $req)
                <li class="flex items-center justify-between p-4 border border-gray-300 rounded-md bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition duration-200">
                    {{-- Engineer Profile --}}
                    <div class="flex items-center gap-4">
                        <img class="w-12 h-12 rounded-full object-cover border border-gray-300 dark:border-gray-700 shadow"
                            src="{{ $req->engineer->profile_image ? asset('storage/' . $req->engineer->profile_image) : asset('user_profiles/user/user.png') }}"
                            alt="Avatar">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $req->engineer->first_name }} {{ $req->engineer->last_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $req->engineer->designation ?? 'Engineer' }}</p>
                        </div>
                    </div>

                    {{-- Leave Details --}}
                    <div class="flex flex-col text-xs text-gray-700 dark:text-gray-300 text-center border-l border-gray-200 px-4">
                        @if($req->paid_leave_days)
                        <p class="text-green-600 font-semibold">Paid Leave</p>
                        <p class="text-gray-700 dark:text-gray-400">{{ \Carbon\Carbon::parse($req->paid_from_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($req->paid_to_date)->format('d M, Y') }}</p>
                        @endif
                        @if($req->unpaid_leave_days)
                        <p class="text-red-600 font-semibold mt-2">Unpaid Leave</p>
                        <p class="text-gray-700 dark:text-gray-400">{{ \Carbon\Carbon::parse($req->unpaid_from_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($req->unpaid_to_date)->format('d M, Y') }}</p>
                        @endif
                    </div>

                    <div class="flex">
                        @if (!empty($req->signed_paid_document) && !empty($req->unsigned_paid_document))
                        {{-- If both Paid and Unpaid leaves are applied, show only Signed Paid Document --}}
                        <a title="Unsigned Paid Document" href="{{ asset('storage') }}/{{ $req->unsigned_paid_document }}" target="_blank">
                            <div class="flex items-center gap-3">
                                <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="Signed Paid Document">
                            </div>
                        </a>
                        @elseif($req->unsigned_unpaid_document)
                        {{-- If only Unpaid leave is applied, show Unsigned Unpaid Document --}}
                        <a title="Unsigned Unpaid Document" href="{{ asset('storage') }}/{{ $req->unsigned_unpaid_document }}" target="_blank">
                            <div class="flex items-center gap-3">
                                <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="Unsigned Unpaid Document">
                            </div>
                        </a>
                        @else
                        <p>-</p>
                        @endif
                    </div>


                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-3">
                        <form method="POST" action="{{ route('leave.approve', $req->id) }}" onsubmit="return confirmApprove()">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 shadow-sm transition">
                                Accept
                            </button>
                        </form>

                        <form method="POST" action="{{ route('leave.reject', $req->id) }}" onsubmit="return confirmReject()">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 shadow-sm transition">
                                Reject
                            </button>
                        </form>
                    </div>
                </li>
                @endforeach



                @else
                <li class="text-center text-gray-500 py-4">
                    No leave requests found.
                </li>
                @endif


                <!-- <li class="flex p-2 justify-between border border-gray-300 items-center rounded-lg ">
                    <div class="text-center w-full dark:text-gray-300">
                        Comming Soon...
                    </div>
                </li> -->
            </ul>
        </div>

        <div class="bg-white dark:bg-gray-700 dark:border-gray-800  rounded-lg border-gray-100 shadow-lg p-4">
            <div class="flex justify-between p-2 dark:text-gray-200">
                <p>Notifications</p>
                <a data-href="{{ route('notifications.markAllAsRead') }}" class="text-sm font-semibold dark:text-gray-300 text-primary cursor-pointer mark-all-as-read">Mark all as read</a>
            </div>

            <!-- Tabs -->
            <div class="mb-4 mt-4 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                    data-tabs-toggle="#default-tab-content" role="tablist">

                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg active" id="all-tab"
                            data-tabs-target="#all" type="button" role="tab" aria-controls="all"
                            aria-selected="true">
                            All
                            <span class="ms-[.2rem] bg-indigo-100 w-fit p-[.3rem] text-indigo-800 text-[.66rem] font-bold rounded-full all_count">
                                {{ sprintf('%02d', $allCount) }}
                            </span>
                        </button>
                    </li>

                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="unread-tab"
                            data-tabs-target="#unread" type="button" role="tab" aria-controls="unread"
                            aria-selected="false">
                            Unread
                            <span class="ms-[.2rem] bg-indigo-100 w-fit p-[.3rem] text-indigo-800 text-[.66rem] font-bold rounded-full unread_count">
                                {{ sprintf('%02d', $unreadCount) }}
                            </span>
                        </button>
                    </li>

                    <li role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="read-tab"
                            data-tabs-target="#read" type="button" role="tab" aria-controls="read"
                            aria-selected="false">
                            Read
                            <span class="ms-[.2rem] bg-indigo-100 w-fit p-[.3rem] text-indigo-800 text-[.66rem] font-bold rounded-full read_count">
                                {{ sprintf('%02d', $readCount) }}
                            </span>
                        </button>
                    </li>
                </ul>

            </div>

            <!-- Tab Content -->
            <div id="default-tab-content">
                {{-- All Notifications --}}
                <ul class="flex flex-col gap-2 p-4 h-[40vh] overflow-auto bg-gray-50 rounded-lg shadow-lg" role="tabpanel" id="all" aria-labelledby="all-tab">
                    
                </ul>

                {{-- Unread Notifications --}}
                <ul class="flex flex-col gap-2 p-4 h-[40vh] overflow-auto bg-gray-50 rounded-lg shadow-lg hidden" role="tabpanel" id="unread" aria-labelledby="unread-tab">
                    
                </ul>

                {{-- Read Notifications --}}
                <ul class="flex flex-col gap-2 p-4 h-[40vh] overflow-auto bg-gray-50 rounded-lg shadow-lg hidden" role="tabpanel" id="read" aria-labelledby="read-tab">
                    
                </ul>
            </div>



        </div>
    </div>

</div>
@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- Include jQuery (Optional for other integrations) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        const today = new Date().toISOString().split('T')[0];
        fetchEventsForDate(today);
    });

    $("#filter_date").on('change', function(){
        $.ajax({
            url: "{{ route('filter-dashboard-stastics') }}", 
            type: "POST",
            data: {filter_date : $("#filter_date").val()},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                
                $("#states_approved_leaves").html(response.leaveApproved)
                $("#states_tickets").html(response.tickets)
                $("#states_leads").html(response.leads);
                $("#states_engineer_payouts").html(response.engineer_payouts_html);
                $("#states_customer_receivable").html(response.customer_receivable_html);
                
            },
            error: function(error) {
                console.error("Error updating amount:", error);
                alert("Failed to update amount.");
            },
        });
    });

    $("#filter_date").trigger('change');

    document.addEventListener("DOMContentLoaded", function() {

        var calendarEl = document.getElementById("calendar");

        let eventDots = new Set();

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "dayGridMonth",
            height: 400,
            contentHeight: 400,
            weekends: true,
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch(`/events/get?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`)
                    .then(response => response.json())
                    .then(events => {
                        eventDots.clear();
                        events.forEach(event => {
                            if (Array.isArray(event.uniqueDates)) {
                                event.uniqueDates.forEach(date => eventDots.add(date));
                            }
                        });
                        console.log("Fetched Events:", events); // Debugging
                        successCallback(events);
                    })
                    .catch(error => {
                        console.error("Error fetching events:", error);
                        failureCallback(error);
                    });
            },
            eventContent: function(arg) {
                return {
                    html: '<div class="hidden"></div>'
                };
            },
            headerToolbar: {
                left: "prev",
                center: "title",
                right: "next",
            },
            selectable: true, // Allow date selection
            dateClick: function(info) {
                fetchEventsForDate(info.dateStr);
            },
            editable: true,
            datesSet: function(info) {
                console.log("New month:", info.start.toISOString().slice(0, 7)); // Logs YYYY-MM
                fetchEventsForMonth(info.start);
            }
        });

        function updateEvents(newEvents) {
                // calendar.removeAllEvents(); // Remove old events
                calendar.addEventSource(newEvents); // Add new events
                // Ensure calendar has rendered before attempting to access the view
                setTimeout(() => {
                    $('.fc-day').each(function() {
                    const dayCell = $(this);
                    const dateStr = dayCell.data('date'); // e.g., "2025-05-05"

                    console.log("dayCell => ", dayCell)
                    
                    if (!dateStr) return;

                    const date = new Date(dateStr);
                    const dayOfWeek = date.getDay(); // 0 = Sunday, 6 = Saturday

                    // Skip if Saturday (6) or Sunday (0)
                    if (dayOfWeek === 0 || dayOfWeek === 6) return;

                    // Check if the event exists for this date and add a dot if not already there
                    if (eventDots.has(dateStr) && dayCell.find('.event-dot').length === 0) {
                        const dot = $('<div class="event-dot"></div>');
                        const dayNumber = dayCell.find('.fc-daygrid-day-number');

                        // dayCell.find(".fc-daygrid-day-number")
                        const event = dayCell.find('.fc-daygrid-day-bg');

                        event.html(dot);
                    }
                });



                // Refresh the calendar events to make sure everything is updated
                calendar.refetchEvents();
            }, 300); 
        }

        // Function to fetch events for a specific month
        function fetchEventsForMonth(startDate) {
            let year = startDate.getFullYear();
            let month = (startDate.getMonth() + 1).toString().padStart(2, "0"); // Format as 2-digit MM

            console.log(`Fetching events for ${year}-${month}...`);

            // Simulate fetching events from an API (replace this with actual API call)
            setTimeout(() => {
                let newEvents = [{
                        id: "1",
                        start: `${year}-${month}-05`
                    },
                    {
                        id: "2",
                        start: `${year}-${month}-15`
                    },
                    {
                        id: "3",
                        start: `${year}-${month}-25`
                    }
                ];
                updateEvents(newEvents);
            }, 1000);
        }

        // Load initial events for the current month
        fetchEventsForMonth(new Date());

        calendar.render();

        setTimeout(() => {
            calendar.render();
        }, 300);

    });


    // Function to fetch events for a specific date
    function fetchEventsForDate(date) {

        fetch(`/events/get?date=${date}`)
            .then((response) => response.json())
            .then((events) => {
                updateEventsList(events);
            })
            .catch((error) => console.error("Error fetching events:", error));
    }


    function updateEvents(calendar, newEvents) {
        calendar.removeAllEvents(); // Remove old events
        calendar.addEventSource(newEvents); // Add new events
        calendar.refetchEvents(); // Refresh calendar
    }

    // Function to update the event list in the sidebar
    function updateEventsList(events) {
        console.log('events :', events);


        let eventsContainer = document.getElementById("events").querySelector("ul");
        eventsContainer.innerHTML = ""; // Clear previous events

        if (events.length === 0) {
            eventsContainer.innerHTML = `<li class="text-gray-500 p-2">No events for this date.</li>`;
            return;
        }

        eventsContainer.innerHTML = `<li class="text-primary p-2  my-2 text-lg font-semibold">Total Ticket : ${events.length}</li>`;


        events.forEach((event) => {
            try {
                let li = document.createElement("li");
                li.className =
                    "p-2  border border-gray-300 flex flex-col   rounded-lg";

                let wrapper = document.createElement("div"); // use a div instead of <a>


                // Define status color mapping
                const statusColors = {
                    inprogress: {
                        bg: "bg-green-100",
                        text: "text-green-600",
                        label: "In Progress"
                    },
                    hold: {
                        bg: "bg-blue-100",
                        text: "text-blue-600",
                        label: "On Hold"
                    },
                    break: {
                        bg: "bg-yellow-100",
                        text: "text-yellow-600",
                        label: "On Break"
                    },
                    close: {
                        bg: "bg-green-100",
                        text: "text-green-600",
                        label: "Close"
                    },
                    offered: {
                        bg: "bg-purple-100",
                        text: "text-purple-600",
                        label: "Offered"
                    },
                    expired: {
                        bg: "bg-red-100",
                        text: "text-red-600",
                        label: "Expired"
                    },
                    accepted: {
                        bg: "bg-purple-100",
                        text: "text-purple-600",
                        label: "Accepted"
                    },
                    not_started: {
                        bg: "bg-purple-100",
                        text: "text-purple-600",
                        label: "Not Started"
                    },
                    null: {
                        bg: "bg-purple-100",
                        text: "text-purple-600",
                        label: "Offered"
                    },

                };

                // Determine status label and color
                let statusKey = event.status;
                if (event.status === "accepted" && new Date().toISOString().split('T')[0] === event.date) {
                    statusKey = "not_started"; // Check if today's date matches task start date
                }

                const status = (() => {
                    let resolvedStatusKey = event.status;

                    if (event.status === "accepted" && new Date().toISOString().split('T')[0] === event.date) {
                        resolvedStatusKey = "not_started";
                    }

                    // Fallback for when status is null and eng_status is not defined or invalid
                    const keyToUse = resolvedStatusKey === null ? event.eng_status : resolvedStatusKey;
                    const fallback = {
                        bg: "bg-gray-100",
                        text: "text-gray-600",
                        label: keyToUse || "Unknown"
                    };

                    return statusColors[keyToUse] || fallback;
                })();

                var noti = "";

                if(event.notifications_count){
                    noti = `<div class="text-gray-500">  
                            <p>
                                🔔 ${event.prepare_for_display_notification}
                            </p>
                        </div>`;
                }

                wrapper.innerHTML = `
                                <div class="flex justify-between items-center gap-2 w-full">
                                    <a target="_self" href="/ticket/${event.id}"  class="text-[1rem] font-bold text-gray-800">${event.ticketId}</a>
                                    <div class="flex items-center gap-2">
                                     ${event.isLate ?  `  <div class="text-[.9rem] bg-red-100 text-red-700 font-semibold capitalize px-[1rem] py-[.3rem] rounded-lg "> Late   </div> ` : `<div> </div>` } 
                                     <div class="text-[.9rem] ${status.bg} font-semibold capitalize px-[1rem] py-[.3rem] rounded-lg ${status.text}">
                                         ${status.label}
                                     </div>
                                     </div>
                                </div>
                                <div class="flex gap-2 flex-col">  
                                   <div class="flex gap-4 items-center justify-between my-2">
                                     <div class="text-[1rem] text-primary  font-bold">${event.task}  </div>
                                   
                                    </div>


                                    <div class="flex justify-between">
                                        <div class="flex gap-4">
                                            <div class="text-gray-500"> 📅 ${event.date} </div>
                                            ${event.date !== event.end_date ? `<div class="text-gray-500">  📅 ${event.end_date} </div>` : ''}
                                        </div>
                                        <div class="text-gray-500"> ⏰ ${event.time} </div>
                                    </div>
                                    <div class="text-gray-500"> 📍 ${event.address} </div>
                                    ${noti}
                                </div>
                            `;


                li.appendChild(wrapper);
                eventsContainer.appendChild(li);
            } catch (error) {
                console.error(`Error rendering event at index ${index}`, event, error);
            }
        });

    }


    // Leave request submit

    function confirmApprove() {
        return confirm("Are you sure you want to approve this leave request?");
    }

    function confirmReject() {
        return confirm("Are you sure you want to reject this leave request?");
    }
</script>
<script>
    var NOTI_LAZY_LOAD = "{{ route('notificationLazyLoad') }}";
</script>
@vite([
    'resources/js/dashboard/notification.js',
])
@endsection