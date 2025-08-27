@extends('layouts.app')

@section('title', 'Customer Invoice')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 22px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
        padding: .7rem 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 22px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #4CAF50;
    }

    input:checked+.slider:before {
        transform: translateX(18px);
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
</style>

<div class="overflow-x-hidden">
    <div class="card  relative">
        <div class="mb-4 flex justify-between items-center">
            <h4 class="text-2xl font-extrabold flex text-gray-800 dark:text-gray-200 items-center gap-2" title="{{ $customer->name }}">
                <svg class="w-8 h-8   text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                {{ Str::words($customer->name, 3, '...') }}'s Receivables
            </h4>
            <div class="gap-2 flex justify-between items-center">
                <div class="flex gap-2    w-full">
                    <input type="hidden" id="filter_val" name="filter_val" value="{{$_GET['filter']}}">
                    @php
                    $currency = [
                    'all' => ['name' => 'All', 'value' => 'all'],
                    'dollar' => ['name' => '$ - Dollar', 'value' => 'dollar'],
                    'euro' => ['name' => '€ - Euro', 'value' => 'euro'],
                    'pound' => ['name' => '£ - Pound', 'value' => 'pound'],
                    'zloty' => ['name' => 'zł - Zloty', 'value' => 'zloty'],
                    ];
                    @endphp
                    <div class="">
                        <div class="customer">
                            <x-input-dropdown name="currency" id="currency" placeholder="Choose Currency" class=""
                                :options="$currency" optionalLabel="name" optionalValue="value"
                                value="<?php echo isset($_GET['currency']) ? $_GET['currency'] : 'all'; ?>" /><!-- $customer -->
                        </div>
                    </div>

                    <div class="">
                        <div class="customer">
                            <x-input-dropdown name="year" id="year" placeholder="Choose Year" class=""
                                :options="$year" optionalLabel="name" optionalValue="value"
                                value="{{ old('year', $selectedYear) }}" />
                        </div>
                    </div>
                    <div class="">
                        <div class="customer">
                            {{-- <label class="text-sm dark:text-white">Select Month</label> --}}
                            <x-input-dropdown name="month" id="month" placeholder="Choose Month" class=""
                                :options="$month" optionalLabel="name" optionalValue="value"
                                value="{{ old('month', $selectedMonth) }}" />
                        </div>
                    </div>
                    <div class="flex mt-1 items-center ">
                        <div class="inline-flex rounded-md shadow-xs " role="group">
                            <a href="{{ route('customer-invoice.show', $customer->id ) }}?filter=all"
                                class="px-4 py-2 text-sm font-medium  
                                        border border-gray-200 rounded-s-lg 
                                        focus:z-10  
                                        dark:bg-gray-800 
                                        dark:border-gray-700 dark:text-white  
                                        
                                        <?php
                                        if (isset($_GET['filter']) && $_GET['filter'] == 'all') {
                                            echo " bg-primary-light-one text-white  ";
                                        }
                                        ?>
                                    ">
                                <!-- All -->
                                All
                            </a>
                            <a href="{{ route('customer-invoice.show', $customer->id ) }}?filter=pending"
                                class="px-4 py-2 text-sm font-medium  
                                        border border-gray-200 rounded-s-lg 
                                        focus:z-10  
                                        dark:bg-gray-800 
                                        dark:border-gray-700 dark:text-white  
                                        
                                        <?php
                                        if (isset($_GET['filter']) && $_GET['filter'] == 'pending') {
                                            echo " bg-primary-light-one text-white  ";
                                        }
                                        ?>
                                    ">
                                <!-- All -->
                                Pending
                            </a>
                                <a href="{{ route('customer-invoice.show', $customer->id ) }}?filter=processing"
                                    class="px-4 py-2 text-sm font-medium text-gray-900 
                                        border-t border-b border-gray-200  
                                        focus:z-10 focus:ring-2 focus:ring-blue-700 
                                        focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 
                                            <?php
                                            if (isset($_GET['filter']) && $_GET['filter'] == 'processing') {
                                                echo " bg-primary-light-one text-white  ";
                                            }
                                            ?>      
                                        ">
                                    <!-- Paid -->
                                    Processing
                                </a>
                                <a href="{{ route('customer-invoice.show', $customer->id ) }}?filter=compeleted"
                                    class="px-4 py-2 text-sm font-medium text-gray-900 
                                    border border-gray-200 rounded-e-lg 
                                        focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 
                                        dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white
                                        dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white
                                        <?php
                                        if (isset($_GET['filter']) && $_GET['filter'] == 'compeleted') {
                                            echo " bg-primary-light-one text-white  ";
                                        }
                                        ?>  
                                        ">
                                    Completed
                                </a>
                        </div>
                    </div>
                    <!-- <div class="flex w-fit gap-2 items-center justify-end  border-x-2 border-gray-200 dark:border-gray-700 px-3">
                        <img class="w-10 h-10 rounded-full border"
                            src="{{ $customer['profile_image'] ? asset('storage/' . $customer['profile_image']) : asset('user_profiles/user/user.png') }}"
                            alt="Rounded avatar">
                        <div class="flex flex-col leading-5">
                            <span
                                class="text-[1.2rem] dark:text-gray-200 font-semibold text-gray-800">{{ $customer->name }}</span> <span
                                class="text-[.8rem] font-medium dark text-[.8rem] dark:text-gray-200 font-semibold
                                text-gray-800">#{{ $customer->customer_code }}
                            </span>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>


        {{-- card-body  --}}
        <div class="card-body ">

            <!-- filter  -->
            <form method="POST" id="payoutForm" action="{{ route('customer-invoice.store') }}">
                @csrf
                <input type="hidden" id="customer_id" name="customer_id" value="{{ $customer->id }}" />

                <div
                    class="mt-2 border  border-gray-200 dark:border-gray-700  rounded-xl scrollbar-hide overflow-scroll">

                    <table id="search-table" class="w-full border rounded-xl  scrollbar-hide overflow-scroll" style="border-radius: 12px">
                        <thead>
                            <tr>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <input id="default-checkbox" type="checkbox" value=""
                                        class="w-4 h-4 check-all-box text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-200  dark:border-3ray-600"
                                        <?php
                                        if (isset($_GET['currency']) && $_GET['currency'] == 'all') {
                                            echo "disabled='disabled'";
                                        }
                                        ?>>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        Sr.
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex  text-[.85rem]">
                                        Date
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex text-[.85rem]">
                                        Engineer
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex text-[.85rem]">
                                        Ticket
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        Hours
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        OT
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        OOH
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        WW
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        HW
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        Travel
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        Tool
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        Monthly Receivable
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        Receivable
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        Payment Status
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                                    <span class="flex items-center justify-center text-[.85rem]">
                                        Action
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="p-2">
                            @php
                            $monthlyGrouped = $customer_invoice->filter(function ($invoice) {
                            return optional($invoice->ticket)->rate_type != null;
                            })->groupBy('ticket_id');

                            $nonMonthly = $customer_invoice->filter(function ($invoice) {
                            return optional($invoice->ticket)->rate_type == null;
                            });

                            // Combine both into one iterable structure
                            $mergedInvoices = [];

                            foreach ($monthlyGrouped as $ticketId => $groupedItems) {
                            $mergedInvoices[] = $groupedItems;
                            }

                            foreach ($nonMonthly as $singleInvoice) {
                            $mergedInvoices[] = collect([$singleInvoice]); // wrap single item as a collection for uniformity
                            }

                            $sr = 1;
                            $total = 0;
                            $overtimePayable = 0;
                            $grossPay = 0;
                            $gPay = 0;
                            $currencyIcon = '';
                            @endphp

                            @foreach ($mergedInvoices as $items)
                            @php $ticketId = optional($items->first()->ticket)->id ?? ''; @endphp
                            @foreach ($items as $index => $daily_work)
                            @php
                            if ($daily_work['ticket']['currency_type'] == 'dollar') {
                            $currencyIcon = '$';
                            } elseif ($daily_work['ticket']['currency_type'] == 'pound') {
                            $currencyIcon = '£';
                            } elseif ($daily_work['ticket']['currency_type'] == 'euro') {
                            $currencyIcon = '€';
                            } elseif ($daily_work['ticket']['currency_type'] == 'zloty') {
                            $currencyIcon = 'zł';
                            }

                            $total += $daily_work['overtime_payable'];

                            if ($daily_work['client_payment_status'] != 'completed') {
                            $grossPay += (float) $daily_work['client_payable']
                            + (float) $daily_work['travel_cost']
                            + (float) $daily_work['tool_cost']
                            + (float) $daily_work['overtime_hour'];
                            }
                            @endphp
                            <tr class="{{ $index === 0 ? 'border-t border-gray-300 dark:border-gray-600' : '' }} font-medium text-[.9rem] p-4" data-ticket-id="{{ $ticketId }}">
                                <!-- Checkbox column -->
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    @if ($index === 0 && ($daily_work['payment_status'] == 'pending' || $daily_work['payment_status'] == null))
                                    <input
                                        name="customer_payable_ids[]" type="checkbox"
                                        value="{{ $daily_work->id }}"
                                        class="w-4 h-4 daily-work-check-box text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600 parent_id" data-child_id=".child_id_{{ $ticketId }}">
                                    
                                    @elseif ($daily_work['payment_status'] == 'processing' || $daily_work['payment_status'] == 'completed')
                                    <svg class="w-6 h-6 text-gray-800 dark:text-green-600" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M5 11.917 9.724 16.5 19 7.5" />
                                    </svg>
                                    @else
                                    <input
                                        name="customer_payable_ids[]" type="checkbox"
                                        value="{{ $daily_work->id }}"
                                        class="w-4 h-4 daily-work-check-box text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600 hidden child_id_{{ $ticketId }}">
                                    @endif
                                </td>
                                <!-- Serial number -->
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">
                                        @if ($index === 0)
                                        {{ $sr++ }}
                                        @endif
                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-2 py-1 whitespace-nowrap">
                                    <span class="flex ">
                                        {{ $daily_work['ticketWork']['work_start_date'] }}
                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-2 py-1 whitespace-nowrap ">
                                    <span class="flex ">
                                        {{ $daily_work['engineer']['first_name'] }} {{ $daily_work['engineer']['last_name'] }}
                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-2 py-1 whitespace-nowrap">
                                    
                                    <a href="{{ route('ticket.show',$daily_work['ticket']['id'] )}}" class="text-decoration hover:text-gray-400">
                                        {{ $daily_work['ticket']['ticket_code'] }}
                                    </a>
                                    <x-timezone :timezone="$daily_work['ticket']['timezone']"/>
                                    
                                    <!-- <span class="flex items-center justify-center">
                                        <a href="{{ route('ticket.show',$daily_work['ticket']['id'] )}}" class="text-decoration hover:text-gray-400">
                                            {{ $daily_work['ticket']['ticket_code'] }}
                                        </a>
                                        <p class="text-sm text-gray-400">
                                            {{ $daily_work['ticket']['timezone'] }}
                                            ({{ fetchTimezone($daily_work['ticket']['timezone'])['gmtOffsetName'] ?? '' }})
                                        </p>
                                    </span> -->
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">
                                        {{ $daily_work['total_work_time'] ?? '-' }}
                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">

                                    @if ($daily_work['overtime_hour'] != '00:00:00')
                                    <span class="flex items-center justify-center">
                                        {{ $daily_work['overtime_hour'] ? $daily_work['overtime_hour'] : '00:00:00' }}
                                    </span>
                                    @else
                                    <span class="flex items-center justify-center">
                                        ---
                                    </span>
                                    @endif
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">

                                    @if ($daily_work['overtime_hour'] != '00:00:00')
                                    <span class="flex items-center justify-center">
                                        {{ $daily_work['overtime_payable'] == 1 ? 'Yes' : 'No' }}
                                    </span>
                                    @else
                                    <span class="flex items-center justify-center">
                                        {{ $daily_work['is_out_of_office_hours'] == 1 ? 'Yes' : 'No' }}
                                    </span>
                                    @endif
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">
                                        {{ $daily_work['is_weekend'] == 1 ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">
                                        {{ $daily_work['is_holiday'] == 1 ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">
                                        {{$currencyIcon}} {{ $daily_work['travel_cost'] ?? '0' }}
                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">
                                        {{$currencyIcon}} {{ $daily_work['tool_cost'] ?? '0' }}
                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">
                                        @if ($daily_work?->ticket->rate_type === "monthly")
                                        {{ $index === 0 ? $currencyIcon . ' ' . (float) $daily_work['monthly_rate'] : '-' }}
                                        @else
                                        -
                                        @endif
                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">


                                        @php
                                        if ($daily_work?->ticket->rate_type == "monthly") {
                                        $totalPay = ($daily_work?->ot_payable ?? 0) + ($daily_work?->ooh_payable ?? 0) + ($daily_work?->ww_payable ?? 0) + ($daily_work?->hw_payable ?? 0) + ($daily_work?->travel_cost ?? 0) + ($daily_work?->tool_cost ?? 0);
                                        } else {
                                        $totalPay = (float) ($daily_work['client_payable'] ?? 0) +
                                        (float) ($daily_work['travel_cost'] ?? 0) +
                                        (float) ($daily_work['tool_cost'] ?? 0);
                                        }
                                        @endphp

                                        {{ $currencyIcon }} {{ $totalPay }}

                                        <input type="hidden" class="customer-total-payable" value="{{(float)$totalPay }}">
                                        <input type="hidden" class="rate-type" value="{{ $daily_work->ticket->rate_type ?? '' }}">
                                        <input type="hidden" class="monthly-rate" value="{{ $daily_work['monthly_rate'] ?? 0 }}">
                                        <input type="hidden" class="ticket-id" value="{{ $daily_work->ticket->id ?? '' }}">

                                    </span>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">
                                        @if ($daily_work['payment_status'] == 'pending')
                                        <p> <x-badge type="warning" label="Pending" /></p>
                                        @elseif ($daily_work['payment_status'] == 'processing')
                                        <p> <x-badge type="primary" label="Processing" /></p>
                                        @else
                                        <p> <x-badge type="success" label="Completed" /></p>
                                        @endif
                                    </span>
                                </td>

                                <td class="px-auto py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button"
                                            class="open-ticket-work-modal bg-blue-100  font-medium rounded-lg text-sm px-[.6rem] py-[.4rem] text-center  flex"
                                            data-ticket-work-id="{{ $daily_work->id }}"
                                            >
                                            <svg class="w-6 h-6 text-primary" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="max-w-md ms-auto mt-10 p-4">

                    {{-- <div class="flex justify-between items-center mt-4">
                        <span class="font-semibold text-primary" id="total_pay">Total Pay</span>
                        <input id="total_payable" name="total_payable" type="text" class="text-primary font-semibold text-right w-1/2 bg-transparent border border-gray-300 rounded-lg px-2" value="0.00" readonly>
                    </div> --}}

                    {{-- <div class="flex justify-between items-center mt-4">
                        <span class="font-semibold text-primary" id="total_pay">Currency</span>
                        <input id="payable_currency_icon" name="payable_currency_icon" type="text" class="text-primary font-semibold text-right w-1/2 bg-transparent border border-gray-300 rounded-lg px-2" value="<?php echo isset($_GET['currency']) && $_GET['currency'] != 'all' ? $currency[$_GET['currency']]['name'] : ''; ?>" readonly>
                        <input id="payable_currency" name="payable_currency" type="hidden" class="text-primary font-semibold text-right w-1/2 bg-transparent border border-gray-300 rounded-lg px-2" value="<?php echo isset($_GET['currency']) ? $_GET['currency'] : 'all'; ?>" readonly>
                    </div> --}}

                    <div class="flex justify-between items-center mt-4">
                        <span class="font-semibold text-primary" id="banks">Banks</span>
                        <x-input-dropdown name="bank_id" id="bank_id" placeholder="Choose Bank" :options="$banks" class="select2"
                            optionalLabel="bank_name" optionalValue="id" optionalValue2="account_holder_name" value="{{ $last_paid_bank_id }}" />
                    </div>

                    <div class="flex justify-between mt-6">
                        <input id="total_payable" name="total_payable" type="hidden" />
                        <input id="payable_currency" name="payable_currency" type="hidden" />
                        <button type="reset" class="px-7 py-3 border border-primary text-primary rounded-lg">Cancel</button>
                        <button class="px-9 py-3 bg-primary  text-white font-semibold rounded-lg bg-blue-800" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button">Create Invoice for <span id="payable_amount_text">0</span></button>
                    </div>

                </div>
                
                <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <div class="p-4 md:p-5 text-center">
                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to create the invoice?</h3>
                                <button data-modal-hide="popup-modal" type="submit" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                    Yes, I'm sure
                                </button>
                                <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Main modal -->
@include('backend.ticket.customer_ticket_summary_modal')

<!--  Toast  -->

<div id="toast-warning" class="hidden fixed top-5 right-5 flex items-center w-full max-w-xs p-4 rounded-lg shadow-lg bg-red-100 dark:bg-red-800 border border-red-300 dark:border-red-700" role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-700 bg-red-200 rounded-lg dark:bg-red-900 dark:text-red-300">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a1 1 0 012 0v4a1 1 0 01-2 0V6zm1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
        </svg>
    </div>
    <div class="ml-3 text-sm text-red-800 dark:text-red-200 font-medium">
        Please select a currency before proceeding.
    </div>
    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-transparent text-red-500 hover:text-red-800 dark:text-red-300 dark:hover:text-red-100 rounded-lg p-1.5" onclick="closeToast()">
        <span class="sr-only">Close</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('.select2').select2();


    $(document).on('change', '.check-all-box', function() {
        let isChecked = $(this).is(':checked'); // Check if .check-all-box is checked
        $('.daily-work-check-box').prop('checked', isChecked); // Apply checked state to all .daily-work-check-box
        calculateGrossPay();
    });

    // Uncheck .check-all-box if any .daily-work-check-box is unchecked
    $(document).on('change', '.daily-work-check-box', function() {

        var child_id = $(this).data('child_id');
        $(child_id).prop('checked', $(this).is(':checked'));

        if (!$(this).is(':checked')) {
            $(child_id).prop('checked', false);
            $('.check-all-box').prop('checked',
                false); // Uncheck .check-all-box if a daily-work-check-box is unchecked
        } else {
            // Check if all .daily-work-check-box checkboxes are checked
            let allChecked = $('.daily-work-check-box').length === $('.daily-work-check-box:checked').length;
            $('.check-all-box').prop('checked', allChecked); // Check .check-all-box if all are checked
        }
    });


    $('#payoutForm').submit(function(e) {
        // Check if any checkbox with class `daily-work-check-box` is checked
        let isChecked = $('.daily-work-check-box:checked').length > 0;

        if (!$("#bank_id").val()) {
            e.preventDefault(); // Prevent form submission
            alert('Please select a bank before submitting!');
            return;
        }

        if (!isChecked) {
            e.preventDefault(); // Prevent form submission
            alert('Please select at least one checkbox before submitting!');
        }
    });


    $('.editBtn').on('click', function() {

        var work_id = $(this).data('ticket-work-id');
        $('#ticket_work_id').val(work_id);

        let storageLink = $('#storage_link').val();
        // Make the AJAX call
        $.ajax({
            url: `/customer/get-ticket-data/${work_id}`,
            method: 'GET',
            success: function(response) {


                if (response.status == 'success') {

                    console.log('response == ', response);

                    $('#customer_payout_id').val(response.customer_payable.id);

                    totalOtherExp = 0; // Reset totalExp before recalculating
                    let engineerCharges = response.customer_payable.eng_charge;
                    let customerPayable = response.customer_payable;
                    let currencyIcon = '';


                    // $, €, £
                    if (engineerCharges.currency_type == 'dollar') {
                        currencyIcon = '$';
                    } else if (engineerCharges.currency_type == 'pound') {
                        currencyIcon = '£';
                    } else if (engineerCharges.currency_type == 'euro') {
                        currencyIcon = '€';
                    } else if (engineerCharges.currency_type == 'zloty') {
                        currencyIcon = 'zł';
                    }

                    $('#travel_cost').val(customerPayable.travel_cost);
                    $('#tool_cost').val(customerPayable.tool_cost);

                    $('#overtime').val(customerPayable.overtime_hour);
                    $('.start-date').html(customerPayable.work_start_date)
                    $('.total_hours').html(customerPayable.total_work_time)
                    $('.overtime').html(customerPayable.overtime_hour)

                    $('#ooh-switch').prop('checked', customerPayable.is_out_of_office_hours ? true : false);
                    $('#hw-switch').prop('checked', customerPayable.is_holiday ? true : false);
                    $('#ww-switch').prop('checked', customerPayable.is_weekend ? true : false);
                    $('#ot-switch').prop('checked', customerPayable.is_overtime ? true : false);

                    if (customerPayable.is_out_of_office_hours) {
                        $('.ooh').html(` <x-badge type="success" label="Yes" />`)
                    } else {
                        $('.ooh').html(` <x-badge type="warning" label="No" />`)
                    }
                    if (customerPayable.is_holiday) {
                        $('.hw').html(` <x-badge type="success" label="Yes" />`)
                    } else {
                        $('.hw').html(` <x-badge type="warning" label="No" />`)
                    }
                    if (customerPayable.is_weekend) {
                        $('.ww').html(` <x-badge type="success" label="Yes" />`)
                    } else {
                        $('.ww').html(` <x-badge type="warning" label="No" />`)
                    }


                    // update payouts and other expenses
                    console.log('Response ==>', response)
                    updatePaymentDetails(currencyIcon, customerPayable, customerPayable, customerPayable.hourly_payable);

                }

            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                alert('Error fetching data. Please try again.');
            }
        });
    });

    function updatePaymentDetails(currency, customerPayable, dailyWorkDetail, totalHourlyPayable) {
        let totalSum = 0;
        let servicesTotalSum = 0;
        let selectedIndex; // Declare selectedIndex to avoid ReferenceError

        const paymentDetails = [{
                name: 'Travel',
                amount: customerPayable.travel_cost,
                type: 'travel'
            },
            {
                name: 'Tool',
                amount: customerPayable.tool_cost,
                type: 'tool'

            },
            {
                name: "Overtime",
                amount: customerPayable.is_overtime == 1 ? customerPayable.ot_payable : 0,
                type: 'ot'

            },
            {
                name: "Out of Office Hour",
                amount: customerPayable.is_out_of_office_hours == 1 ? customerPayable.ooh_payable : 0,
                type: 'ooh'

            },
            {
                name: "Weekend Work",
                amount: customerPayable.is_weekend == 1 ? customerPayable.ww_payable : 0,
                type: 'ww'

            },
            {
                name: "Holiday Work",
                amount: customerPayable.is_holiday == 1 ? customerPayable.hw_payable : 0,
                type: 'hw'

            },
        ];

        const netPayableList = document.getElementById("net-payable-list");
        netPayableList.innerHTML = "";

        $('#subtotal').html(`${currency} ${parseFloat(totalHourlyPayable).toFixed(2)}`);

        paymentDetails.forEach((item, index) => {
            totalSum += parseFloat(item.amount) || 0;
            if (index > 0) {
                servicesTotalSum += parseFloat(item.amount) || 0;
            }

            const row = document.createElement("div");
            row.className = "grid grid-cols-3 px-4 py-2 text-gray-800 dark:text-gray-300 items-center";
            row.innerHTML = `
            <div>${item.name}</div>
                    <button class="edit-btn text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br 
                focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600 font-medium rounded-lg text-sm 
                px-[.9rem] py-[.4rem] mx-auto w-fit text-center flex items-center gap-1" 
                data-index="${index}" data-type="${item.type}" data-amount="${item.amount}">

                    <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                </svg>


            </button>
                    <div class="text-right font-semibold">
                        <span id="amount-${index}">${currency} ${parseFloat(item.amount).toFixed(2)}</span>
                    </div>`;

            netPayableList.appendChild(row);
        });

        // Event delegation for dynamically added buttons
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('edit-btn')) {
                selectedIndex = event.target.getAttribute('data-index');
                let currentAmount = event.target.getAttribute('data-amount');

                document.getElementById('editAmountInput').value = currentAmount;
                document.getElementById('editAmountModal').classList.remove('hidden');
                document.getElementById('editAmountModal').classList.add('flex');
            }
        });

        document.getElementById('saveEdit').addEventListener('click', function() {
            let newAmount = parseFloat(document.getElementById('editAmountInput').value) || 0;

            document.getElementById(`amount-${selectedIndex}`).textContent = `${currency} ${newAmount.toFixed(2)}`;
            paymentDetails[selectedIndex].amount = newAmount;

            totalSum = 0;
            servicesTotalSum = 0;
            paymentDetails.forEach((item, index) => {
                totalSum += parseFloat(item.amount) || 0;
                if (index > 0) {
                    servicesTotalSum += parseFloat(item.amount) || 0;
                }
            });

            $('#subtotal').html(`${currency} ${totalSum.toFixed(2)}`);
            $('#service-amount').html(`${currency} ${servicesTotalSum.toFixed(2)}`);
            $('#total-amount').html(`${currency} ${totalSum.toFixed(2)}`);

            document.getElementById('editAmountModal').classList.add('hidden');
            document.getElementById('editAmountModal').classList.remove('flex');
        });

        document.getElementById('cancelEdit').addEventListener('click', function() {
            document.getElementById('editAmountModal').classList.add('hidden');
            document.getElementById('editAmountModal').classList.remove('flex');
        });

        $('#daily_total_payable').val(parseFloat(totalSum).toFixed(2));
        $('#service-amount').html(`${currency} ${parseFloat(servicesTotalSum).toFixed(2)}`);
        $('#total-amount').html(`${currency} ${parseFloat(totalSum).toFixed(2)}`);
        $('#payable_amount_text').html(`${currency} ${parseFloat(totalSum).toFixed(2)}`);



        return paymentDetails;
    }

    $("#editAmountForm").submit(function(e) {
        e.preventDefault();

        let pay_type = $("#pay_type").val(); // Ensure pay_type is present in your form
        let payout_id = $("#customer_payout_id").val(); // Ensure pay_type is present in your form
        console.log('pay_type', pay_type)
        let updatedAmount = $("#editAmountInput").val();

        // Get CSRF token from meta tag
        let csrfToken = $("meta[name='csrf-token']").attr("content");

        if (!pay_type || !updatedAmount) {
            alert("Error: Missing required fields!");
            return;
        }

        // Add the CSRF token to the form data
        let formData = {
            _token: csrfToken, // Add CSRF token
            pay_type: pay_type, // Use the correct ID field name
            payout_id: payout_id,
            amount: updatedAmount, // Use correct field name for backend
        };

        console.log("Submitting formData:", formData);

        $.ajax({
            url: "/update-payable-amount", // Ensure this route is correct
            type: "POST",
            data: formData,
            success: function(response) {
                alert("Amount updated successfully!");
                location.reload(); // Refresh page or update UI dynamically
            },
            error: function(error) {
                console.error("Error updating amount:", error);
                alert("Failed to update amount.");
            },
        });
    });


    $(document).on("click", ".edit-btn", function() {
        let selectedAmount = $(this).data("amount"); // Get amount from button
        let selectedType = $(this).data("type"); // Get amount from button


        // Set values in modal input fields
        $("#pay_type").val(selectedType); // Assuming index is the ID
        $("#editAmountInput").val(selectedAmount);

        // Show the modal
        $("#editAmountModal").removeClass("hidden");
    });

    //  changes for goruping
    function calculateGrossPay() {
        let total = 0;
        let grossPayTotal = 0;
        let addedTickets = {};

        $("input[type='checkbox'].daily-work-check-box:checked").each(function() {
            let currentRow = $(this).closest("tr");
            let ticketId = currentRow.find("input.ticket-id").val();
            let rateType = currentRow.find("input.rate-type").val();
            let monthlyRate = parseFloat(currentRow.find("input.monthly-rate").val()) || 0.00;

            if (!addedTickets[ticketId]) {
                let groupedRows = $(`tr[data-ticket-id='${ticketId}']`);
                groupedRows.each(function() {
                    let grossPay = parseFloat($(this).find("input.customer-total-payable").val()) || 0.00;
                    // console.log("grossPay =", grossPay)
                    grossPayTotal += grossPay;
                    // console.log("grossPayTotal =", grossPayTotal)
                    total += grossPay;
                    // console.log("total =", total)
                });

                // Add monthly rate only once per ticket
                if(rateType === 'monthly')
                {
                    total += monthlyRate;
                }
                addedTickets[ticketId] = true;
            }

            // DEPRECATE
            /*if (rateType === "monthly") {
                console.log('monthlyRate', monthlyRate)
                // Only group if rateType is monthly
                if (!addedTickets[ticketId]) {
                    let groupedRows = $(`tr[data-ticket-id='${ticketId}']`);
                    groupedRows.each(function() {
                        let grossPay = parseFloat($(this).find("input.customer-total-payable").val()) || 0.00;
                        grossPayTotal += grossPay;
                        total += grossPay;
                    });

                    // Add monthly rate only once per ticket
                    total += monthlyRate;
                    addedTickets[ticketId] = true;
                }
            } else {
                console.log('test rate_type', monthlyRate)
                // If rateType is not monthly (i.e., hourly or other), just add this row's gross pay
                let grossPay = parseFloat(currentRow.find("input.customer-total-payable").val()) || 0.00;
                grossPayTotal += grossPay;
                total += grossPay;
            }*/
        });

        // Currency symbol logic
        let currency = "{{ $_GET['currency'] }}";
        let currencyIcon = '';
        if (currency === 'dollar') currencyIcon = '$';
        else if (currency === 'pound') currencyIcon = '£';
        else if (currency === 'euro') currencyIcon = '€';
        else if (currency === 'zloty') currencyIcon = 'zł';

        // Update UI
        $("#gross_pay").html(grossPayTotal.toFixed(2));
        $('#btn-amount').html(total.toFixed(2));
        $("#total_payable").val(total.toFixed(2));
        $("#payable_amount_text").html(`${currencyIcon}${total.toFixed(2)}`);
        $("#payable_currency").val(currency);
    }



    $(".daily-work-check-box").on("change", calculateGrossPay);

    $('#year').change(function() {
        filterAction();
    });

    $('#month').change(function() {
        filterAction();
    });

    $('#currency').change(function() {
        filterAction();
    });

    function filterAction() {
        var currency = $('#currency').val();
        var year = $('#year').val();
        var month = $('#month').val();
        var customerId = $('#customer_id').val();
        var filterVal = $('#filter_val').val();
        window.location.href = `/customer-invoice/${customerId}?filter=${filterVal}&currency=${currency}&year=${year}&month=${month}`;
    }

    // payment summary amount edit 
    

    document.addEventListener("DOMContentLoaded", function() {
        let checkboxes = document.querySelectorAll(".daily-work-check-box");

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener("click", function(event) {
                let urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get("currency") === "all") {
                    event.preventDefault(); // Prevent checkbox from being checked
                    showToast();
                }
            });
        });
    });

    function showToast() {
        let toast = document.getElementById("toast-warning");
        toast.classList.remove("hidden"); // Show the toast

        // Auto-hide the toast after 3 seconds
        setTimeout(() => {
            toast.classList.add("hidden");
        }, 3000);
    }

    function closeToast() {
        document.getElementById("toast-warning").classList.add("hidden");
    }

    if($(".daily-work-check-box").length === 0)
    {
        $("#default-checkbox").hide()
    }

    var STORAGE_LINK = "{{ asset('storage') }}";
    var CUSTOMER_PAYOUT_FETCH_POPUP = "{{ route('customer-payout.fetchPopup') }}";
    var UPDATE_AMOUNT_CUSTOMER = "{{ route('updateAmount') }}";
</script>
@vite([
    'resources/js/tickets/customer_ticket_summary_modal.js'
])
@endsection