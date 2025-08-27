@extends('layouts.app')

@section('title', 'Admins Dashboard')

@section('content')
    <style>
        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thumb-dark-500 {
            scrollbar-color: #1F2937 gray;
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
    </style>
    <div class="grid md:grid-cols-8 gap-2">

        <div class="grid md:col-span-6">

            <div
                class="p-8 mb-4 text-white bg-gradient-to-r rounded-xl from-[#3c6e87] via-[#3077ad] to-[#239dde] hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-[#206387] dark:focus:ring-teal-800">
                <p class="pl-0 text-3xl font-bold text-white dark:text-white">
                    Hi, {{ auth()->user()->name }}
                </p>
                <p class="">
                    Welcome back & have a great day at work!
                </p>

            </div>

            <div class="grid md:grid-cols-4 gap-4">

                <a href="{{ route('lead.index') }}" class="">
                    <div class="flex  border border-gray-200 dark:border-gray-700 rounded-lg gap-3 p-4">
                        <div class="p-4 rounded-lg bg-cyan-400 dark:bg-gray-700">
                            <svg class="w-8 h-8 text-white dark:text-cyan-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm4.996 2a1 1 0 0 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 8a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 11a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 14a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-900 dark:text-gray-400 text-base font-extrabold leading-5">Leads</p>
                            <p class="f-montserrat font-bold text-2xl text-gray-500 dark:text-gray-200">
                                {{ $insights['leads'] }}</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('engg.index') }}">
                    <div class="flex  border border-gray-200 dark:border-gray-700 rounded-lg gap-3 p-4">
                        <div class="p-4 rounded-lg bg-teal-400 dark:bg-gray-700">
                            <svg class="w-8 h-8 text-white dark:text-teal-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                    d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                            </svg>

                        </div>
                        <div>
                            <p class="text-gray-900 dark:text-gray-400 text-base font-extrabold">Engineer</p>
                            <p class="f-montserrat font-bold text-2xl text-gray-500 dark:text-gray-200">
                                {{ $insights['engineers'] }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('customer.index') }}">
                    <div class="flex  border border-gray-200 dark:border-gray-700 rounded-lg gap-3 p-4">
                        <div class="p-4 rounded-lg bg-green-400 dark:bg-gray-700">
                            <svg class="w-8 h-8 text-white dark:text-green-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-900 dark:text-gray-400 text-base font-extrabold">Customers</p>
                            <p class="f-montserrat font-bold text-2xl text-gray-500 dark:text-gray-200">
                                {{ $insights['customers'] }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('ticket.index') }}">
                    <div class="flex  border border-gray-200 dark:border-gray-700 rounded-lg gap-3 p-4">
                        <div class="p-4 rounded-lg bg-yellow-400 dark:bg-gray-700">
                            <svg class="w-8 h-8 text-white dark:text-yellow-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12 2a10 10 0 1 0 10 10A10.009 10.009 0 0 0 12 2Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.093 20.093 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM10 3.707a8.82 8.82 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.755 45.755 0 0 0 10 3.707Zm-6.358 6.555a8.57 8.57 0 0 1 4.867-5.523 44.119 44.119 0 0 1 3.748 4.575 8.906 8.906 0 0 1-2.7 1.214 18.798 18.798 0 0 0-5.915-.266Zm-.83.914a8.536 8.536 0 0 0 1.922 5.305 18.55 18.55 0 0 1-.309-5.305 18.1 18.1 0 0 1 6.334.17 17.366 17.366 0 0 1 .39 1.038c.06.15.118.299.178.445a45.596 45.596 0 0 1-6.16 5.582 8.485 8.485 0 0 0-2.355-7.482ZM12 21.5a8.483 8.483 0 0 1-4.673-1.354 44.502 44.502 0 0 0 4.627-4.53 45.515 45.515 0 0 0 4.487 4.457A8.47 8.47 0 0 1 12 21.5Zm6.374-3.02a45.84 45.84 0 0 1-6.031-5.266c.03-.072.057-.146.085-.221.08-.203.16-.412.241-.622a17.166 17.166 0 0 1 6.296-.18 8.531 8.531 0 0 1-.59 6.289Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-900 dark:text-gray-400 text-base font-extrabold">Tickets</p>
                            <p class="f-montserrat font-bold text-2xl text-gray-500 dark:text-gray-200">
                                {{ $insights['tickets'] }}</p>
                        </div>
                    </div>
                </a>

            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div class="mt-4">
                    <div class="w-full border dark:border-gray-700 bg-white rounded-lg dark:bg-gray-800 p-4 md:p-6">
                        <div class="flex justify-between border-gray-200 border-b dark:border-gray-700 pb-3">
                            <dl>
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400 pb-1">Profit</dt>
                                <dd class="leading-none text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $totalIncome - $totalExpense }}</dd>
                            </dl>
                            <div>
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                                    <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4" />
                                    </svg>
                                    Profit rate 23.5%
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 py-3">
                            <dl>
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400 pb-1">Income</dt>
                                <dd class="leading-none text-xl font-bold text-green-500 dark:text-green-400">
                                    {{ $totalIncome }}</dd>
                            </dl>
                            <dl>
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400 pb-1">Expense</dt>
                                <dd class="leading-none text-xl font-bold text-red-600 dark:text-red-500">
                                    {{ $totalExpense }}</dd>
                            </dl>
                        </div>

                        <div id="bar-chart"></div>
                        <div
                            class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                            <div class="flex justify-between items-center pt-5">
                                <!-- Button -->
                                <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdownIncomExp"
                                    data-dropdown-placement="bottom"
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                                    type="button">
                                    Last Year
                                    <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="lastDaysdropdownIncomExp"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                Yesterday</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                7 days </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                30 days</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                90 days</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                6 months</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                year</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="#"
                                    class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                                    Revenue Report
                                    <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full border dark:border-gray-700 bg-white rounded-lg dark:bg-gray-800 p-4 md:p-6">
                        <div class="flex justify-between">
                            <div>
                                <h6 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-2"> Total
                                    Ticket - 32.4k </h6>
                            </div>
                            <div
                                class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
                                12%
                                <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4" />
                                </svg>
                            </div>
                        </div>
                        <div id="area-chart"></div>
                        <div
                            class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                            <div class="flex justify-between items-center pt-5">
                                <!-- Button -->
                                <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                                    data-dropdown-placement="bottom"
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                                    type="button">
                                    Last 7 days
                                    <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>

                                <!-- Dropdown menu -->
                                <div id="lastDaysdropdown"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <a href="#" data-value="yesterday"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white dropdown-item">Yesterday</a>
                                        </li>
                                        <li>
                                            <a href="#" data-value="today"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white dropdown-item">Today</a>
                                        </li>
                                        <li>
                                            <a href="#" data-value="last7days"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white dropdown-item">Last
                                                7 days</a>
                                        </li>
                                        <li>
                                            <a href="#" data-value="last30days"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white dropdown-item">Last
                                                30 days</a>
                                        </li>
                                        <li>
                                            <a href="#" data-value="last90days"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white dropdown-item">Last
                                                90 days</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="#"
                                    class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                                    Users Report
                                    <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid  md:col-span-2 rounded-lg p-2 border dark:border-gray-900 dark:bg-gray-900">

            <div>
                <div class="donutChart w-full bg-white rounded-lg shadow dark:bg-gray-800 p-2 md:p-">
                    <div class="mb-3">
                        <div class="flex p-3">
                            <h4 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Today's Ticket
                            </h4>
                        </div>
                        <!-- <hr class="border-b border-slate-400 w-full my-5"> -->
                        <div
                            class="flex flex-wrap gap-2  justify-center scrollbar-thumb-dark-500 scrollbar-thin overflow-scroll h-[90vh] ">
                            <!-- Card 1 -->
                            @if (!empty($ticketData))
                                @foreach ($ticketData as $ticket)
                                    <div
                                        class="w-[95%] border  border-gray-200 dark:border-gray-700 rounded-xl shadow-md p-4 bg-white dark:bg-gray-900 transform transition-transform duration-300 hover:scale-105">
                                        <a href="{{ route('ticket.show', $ticket->id) }}">
                                            <div class="flex justify-between">
                                                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex gap-2">
                                                    <img class="w-8 h-8 rounded-full"
                                                        src="{{ $ticket->engineer?->profile_image ? asset('storage/profiles/' . $ticket->engineer?->profile_image) : asset('user_profiles/user/user.png') }}"
                                                        alt="user photo">
                                                    @if ($ticket->engineer != null)
                                                        <div class="flex flex-col leading-5"><span
                                                                class="text-[1rem]">{{ $ticket->engineer?->first_name }}
                                                                {{ $ticket->engineer?->last_name }}</span> <span
                                                                class="text-[.7rem]">{{ $ticket->engineer?->engineer_code }}
                                                            </span> </div>
                                                    @else
                                                        <span class="text-[1.1rem]">Not Assign</span>
                                                    @endif
                                                </h3>

                                                <p class="text-lg text-gray-500 dark:text-gray-400 flex justify-between">
                                                    <!-- <span class="font-medium text-[1rem] ">Status:</span>  -->
                                                    @if ($ticket['status'] === 'inprogress')
                                                        <span
                                                            class="bg-green-100 text-green-800 text-xs font-medium  px-2.5 py-0.5 rounded-xl dark:bg-green-900 dark:text-green-300 w-fit h-fit mt-[.5rem] ">In
                                                            Progress</span>
                                                    @elseif($ticket['status'] === 'hold')
                                                        <span
                                                            class="bg-blue-100 text-blue-800 text-xs font-medium  px-2.5 py-0.5 rounded-xl dark:bg-blue-900 dark:text-blue-300 w-fit h-fit mt-[.5rem] ">On
                                                            Hold</span>
                                                    @elseif($ticket['status'] === 'break')
                                                        <span
                                                            class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-xl dark:bg-yellow-900 dark:text-yellow-300 w-fit h-fit mt-[.5rem] ">On
                                                            Break</span>
                                                    @elseif($ticket['status'] === 'close')
                                                        <span
                                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-xl dark:bg-green-900 dark:text-green-300 w-fit h-fit mt-[.5rem] ">Close</span>
                                                    @else
                                                        <span
                                                            class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-xl dark:bg-purple-900 dark:text-purple-300 w-fit h-fit mt-[.5rem] ">Not
                                                            Stated</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <hr class="mb-2" />
                                            <p class="text-lg text-gray-500  dark:text-gray-400 flex justify-between">
                                                <span class="font-medium text-[1rem] ">Ticket:</span> <span
                                                    class="text-[.9rem] font-medium">{{ $ticket['ticket_code'] }}</span>
                                            </p>
                                            <p class="text-lg text-gray-500 dark:text-gray-400 flex justify-between">
                                                <span class="font-medium text-[1rem] truncate max-w-[45%]">Customer:</span>
                                                <span class="text-[.9rem] font-medium truncate max-w-[50%]">
                                                    {{ $ticket['customer']['name'] ?? '-' }}    
                                                </span>
                                            </p>    
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div
                                    class="w-[95%] h-[20vh] border mb-4 border-gray-200 dark:border-gray-700 rounded-xl shadow-md p-4 bg-white dark:bg-gray-900 transform transition-transform duration-300 hover:scale-105 dark:text-white text-gray-800 font-bold flex items-center justify-center">
                                        No ticket found.
                                </div>   
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- ticket table  --}}

    

@endsection

@section('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth'
            });
            calendar.render();
        });

        const revenueChartVal = @json($chartData);

        const options = {
            series: [{
                    name: "Income",
                    color: "#31C48D",
                    data: revenueChartVal.income, // Dynamic income data
                },
                {
                    name: "Expense",
                    data: revenueChartVal.expense, // Dynamic expense data
                    color: "#F05252",
                }
            ],
            chart: {
                type: "bar",
                height: 400,
                toolbar: {
                    show: false,
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: "50%",
                    borderRadius: 5,
                }
            },
            dataLabels: {
                enabled: false,
            },
            xaxis: {
                categories: revenueChartVal.categories, // Months (Jan, Feb, etc.)
                labels: {
                    style: {
                        fontSize: "12px",
                        fontFamily: "Inter, sans-serif",
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return `$${value}`;
                    },
                    style: {
                        fontSize: "12px",
                        fontFamily: "Inter, sans-serif",
                    }
                }
            },
            legend: {
                position: "top",
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(value) {
                        return `$${value}`;
                    }
                }
            }
        };

        if (document.getElementById("bar-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("bar-chart"), options);
            chart.render();
        }
    </script>


    {{-- line chart  --}}
    <script>
        $(document).ready(function() {
            // Initially load the chart with 'today' data
            loadChartData('today');

            // Event delegation for dropdown items
            $(document).on('click', '.dropdown-item', function(e) {
                e.preventDefault(); // Prevent default anchor behavior

                const selectedValue = $(this).data('value'); // Get selected time period value
                const selectedText = $(this).text(); // Get the text of the selected item

                // Update the dropdown button text
                $('#dropdownDefaultButton').html(
                    `${selectedText} <svg class="w-2.5 m-2.5 ms-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" /></svg>`
                );

                // Load the chart with the selected time period data
                loadChartData(selectedValue);
            });

            function loadChartData(timePeriod) {
                $.ajax({
                    url: '{{ route('getTicketChartData') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        time_period: timePeriod,
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            const ticketChartVal = response.data;

                            const ticketChartOptions = {
                                chart: {
                                    height: "100%",
                                    maxWidth: "100%",
                                    type: "area",
                                    fontFamily: "Inter, sans-serif",
                                    dropShadow: {
                                        enabled: false,
                                    },
                                    toolbar: {
                                        show: false,
                                    },
                                },
                                tooltip: {
                                    enabled: true,
                                    x: {
                                        show: false,
                                    },
                                },
                                fill: {
                                    type: "gradient",
                                    gradient: {
                                        opacityFrom: 0.55,
                                        opacityTo: 0,
                                        shade: "#1C64F2",
                                        gradientToColors: ["#1C64F2"],
                                    },
                                },
                                dataLabels: {
                                    enabled: false,
                                },
                                stroke: {
                                    width: 6,
                                },
                                grid: {
                                    show: false,
                                    strokeDashArray: 4,
                                    padding: {
                                        left: 2,
                                        right: 2,
                                        top: 0
                                    },
                                },
                                series: [{
                                    name: "Tickets",
                                    data: ticketChartVal.yTickets,
                                    color: "#1A56DB",
                                }],
                                xaxis: {
                                    categories: ticketChartVal.xVal,
                                    labels: {
                                        show: false,
                                    },
                                    axisBorder: {
                                        show: false,
                                    },
                                    axisTicks: {
                                        show: false,
                                    },
                                },
                                yaxis: {
                                    show: false,
                                },
                            };

                            // Render the updated chart
                            const chart = new ApexCharts(document.getElementById("area-chart"),
                                ticketChartOptions);
                            chart.render();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    }
                });
            }
        });
    </script>




@endsection
