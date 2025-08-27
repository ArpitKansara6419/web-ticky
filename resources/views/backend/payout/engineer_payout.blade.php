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
</style>

@extends('layouts.app')

@section('title', 'Engineer Payout')

@section('content')
<div id="engineer" data-id="{{ $engineer->id }}" class="hidden"></div>
<div class="overflow-x-hidden">

    <div class="card  relative">
        <div class="mb-4 flex justify-between items-center">
            <h4 class="text-2xl font-extrabold flex items-center gap-2">
                <svg class="w-8 h-8   text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                {{ ucfirst($engineer['first_name']).' '.$engineer['last_name'] }}'s Payout
            </h4>
            <div class="flex gap-4">
                <x-input-dropdown name="month" id="month" placeholder="Choose Month" class="w-[15rem]"
                    :options="$month" optionalLabel="name" optionalValue="value"
                    value="{{ old('month', $currentMonth) }}" />
                <div class="inline-flex rounded-md shadow-xs mr-2" role="group">
                    <a href="{{ route('payout.show', $engineer->id ) }}?filter=all&month={{ request()->get('month') }}"
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
                        All
                        </>
                        <a href="{{ route('payout.show', $engineer->id ) }}?filter=paid&month={{ request()->get('month') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-900 
                                border-t border-b border-gray-200  
                                focus:z-10 focus:ring-2 focus:ring-blue-700 
                                focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 
                                    <?php
                                    if (isset($_GET['filter']) && $_GET['filter'] == 'paid') {
                                        echo " bg-primary-light-one text-white  ";
                                    }
                                    ?>      
                                ">
                            Paid
                        </a>
                        <a href="{{ route('payout.show', $engineer->id ) }}?filter=unpaid&month={{ request()->get('month') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-900 
                            border border-gray-200 rounded-e-lg 
                                focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 
                                dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white
                                dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white
                                <?php
                                if (isset($_GET['filter']) && $_GET['filter'] == 'unpaid') {
                                    echo " bg-primary-light-one text-white  ";
                                }
                                ?>  
                                ">
                            Unpaid
                        </a>
                </div>
                <!-- <div class="flex gap-2 items-center border-x-2 border-gray-200 dark:border-gray-700 px-3">
                    <img class="w-10 h-10 rounded-full border"
                        src="{{ $engineer['profile_image'] ? asset('storage/' . $engineer['profile_image']) : asset('user_profiles/user/user.png') }}"
                        alt="Rounded avatar">
                    <div class="flex flex-col leading-5">
                        <span
                            class="text-[1.2rem] dark:text-gray-200 font-semibold text-gray-800">{{ $engineer->first_name }}
                            {{ $engineer->last_name }}</span> <span
                            class="text-[.8rem] font-medium dark text-[.8rem] dark:text-gray-200 font-semibold
                                text-gray-800">{{ $engineer->engineer_code }}
                        </span>
                    </div>
                </div> -->
            </div>
        </div>


        <input type="hidden" id="storage_link" name="storage_link" value="<?php echo asset('storage/'); ?>">
        <!-- <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Engineer Payout
            </h3>
            <div class="flex">
                <div class="engineers mr-2">
                    <select id="engineer_filter_options" class="engineer_filter_options dropdownField bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 ">
                        @foreach ($engineers as $eng)
                        <option value="{{$eng['id']}}" <?php echo ($selectedEngineerId == $eng['id'] ? ' selected ' : '');  ?>>
                            {{ $eng['name'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="inline-flex rounded-md shadow-xs mr-2" role="group">
                    <a href="{{ route('payout.show', $engineer->id ) }}?filter=all"
                        class="px-4 py-2 text-sm font-medium  
                                border 
                                border-gray-200 rounded-s-lg 
                                focus:z-10  
                                text-gray-900 
                                dark:bg-gray-800 
                                dark:border-gray-700 
                                dark:bg-gray-800 
                                dark:border-gray-700
                                <?php
                                if (isset($_GET['filter']) && $_GET['filter'] == 'all') {
                                    echo " bg-primary-light-one text-white  ";
                                }
                                ?>
                            ">
                        All
                    </a>
                    <a href="{{ route('payout.show', $engineer->id ) }}?filter=paid"
                        class="px-4 py-2 text-sm font-medium text-gray-900 
                                border-t border-b border-gray-200  
                                focus:z-10 focus:ring-2 focus:ring-blue-700 
                                focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 
                                    <?php
                                    if (isset($_GET['filter']) && $_GET['filter'] == 'paid') {
                                        echo " bg-primary-light-one text-white  ";
                                    }
                                    ?>      
                                ">
                        Paid
                    </a>
                    <a href="{{ route('payout.show', $engineer->id ) }}?filter=pending"
                        class="px-4 py-2 text-sm font-medium text-gray-900 
                            border border-gray-200 rounded-e-lg 
                             focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 
                             dark:bg-gray-800 dark:border-gray-700  dark:hover:text-white
                              dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white
                              <?php
                                if (isset($_GET['filter']) && $_GET['filter'] == 'pending') {
                                    echo " bg-primary-light-one text-white  ";
                                }
                                ?>  
                              ">
                        Unpaid
                    </a>
                </div>
                <div class="flex gap-2 items-center border-x-2 border-gray-200 dark:border-gray-700 px-3 ">
                    <img class="w-10 h-10 rounded-full border"
                        src="{{ $engineer['profile_image'] ? asset('storage/' . $engineer['profile_image']) : asset('user_profiles/user/user.png') }}"
                        alt="Rounded avatar">
                    <div class="flex flex-col leading-5">
                        <span
                            class="text-[1.2rem] dark:text-gray-200 font-semibold text-gray-800">{{ $engineer->first_name }}
                            {{ $engineer->last_name }}</span> <span
                            class="text-[.8rem] font-medium dark text-[.8rem] dark:text-gray-200 font-semibold
                                text-gray-800">{{ $engineer->engineer_code }}
                        </span>
                    </div>
                </div>
            </div>
        </div> -->

        {{-- card-body  --}}
        <div class="card-body">

            <form method="POST" id="payoutForm" action="{{ route('payout.store') }}">
                @csrf
                <input type="hidden" name="engineer_id" value="{{ $engineer->id }}" />
                <input type="hidden" id="total_payable" name="total_payable" value="0" />
                <input type="hidden" id="total_zus" name="total_zus" value="0" />
                <input type="hidden" id="total_pit" name="total_pit" value="0" />
                <input type="hidden" id="selected_month" name="selected_month" value="{{ $currentMonth }}" />

                <div
                    class="mt-2 border  border-gray-200 dark:border-gray-700  rounded-xl overflow-scroll">
                    <table id="search-table" class="w-full border rounded-xl  overflow-scroll" style="border-radius: 12px">
                        <thead>
                            <tr>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <input id="default-checkbox" type="checkbox" value=""
                                        class=" w-4 h-4 check-all-box text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-200 dark:border-gray-600">
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        Sr.
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        Date
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        Ticket
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        Customer
                                    </span>
                                </th>

                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        Hours
                                    </span>
                                </th>

                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        OT
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        OOH
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        WW
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        HW
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        Expenses
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        Gross Pay
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        Payment Status
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                    <span class="flex items-center text-[.85rem]">
                                        Action
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="p-2">
                            @php
                            $gPayTotal = 0;
                            $total = 0;
                            $overtimePayable = 0;
                            $grossPay = 0;
                            @endphp

                            @if (!empty($daily_works) && count($daily_works) > 0)
                            @foreach ($daily_works as $key => $daily_work)
                            @php
                            if ($daily_work['overtime_hour']) {
                            $overtimePayable += 50;
                            }

                            if ($daily_work['engineer_payout_status'] != 'paid') {
                            $grossPay += (float) $daily_work['hourly_payable'] + (float) $daily_work['overtime_payable'];
                            }
                            @endphp
                            <tr class="font-medium text-[.9rem] p-4">
                                <td class="dark:text-black-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    @if ($daily_work['engineer_payout_status'] == 'paid')
                                    <svg class="w-6 h-6 text-gray-800 dark:text-green-600"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M5 11.917 9.724 16.5 19 7.5" />
                                    </svg>
                                    @else
                                    <input name="daily_work_ids[]" type="checkbox"
                                        value="{{ $daily_work->id }}"
                                        data-amount="{{}}"
                                        class="w-4 h-4 daily-work-check-box text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2  dark:border-gray-600  <?php echo $daily_work['engineer_payout_status'] == 'pending' ? ' dark:bg-gray-200 ' : ' dark:bg-gray-500 '; ?>  "
                                        <?php echo $daily_work['engineer_payout_status'] == 'paid' ? ' disabled ' : ''; ?>>
                                    @endif
                                </td>
                                <td class="dark:text-slate-200  text-gray-900 px-4 py-2 whitespace-nowrap">
                                    {{ $key + 1 }}
                                </td>
                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    {{ $daily_work['work_start_date'] }}
                                </td>
                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    @if (isset($daily_work['ticket']['id']))
                                    <a href="{{ route('ticket.show', $daily_work['ticket']['id']) }}"
                                        class="text-decoration hover:text-gray-400">
                                        {{ $daily_work['ticket']['ticket_code'] }}
                                    </a>
                                    <p class="text-sm text-gray-400">
                                        {{ $daily_work['ticket']['task_name'] }}
                                    </p>
                                    <p class="text-sm text-gray-400">
                                        {{ $daily_work['ticket']['timezone'] }}
                                        ({{ fetchTimezone($daily_work['ticket']['timezone'])['gmtOffsetName'] ?? '' }})
                                    </p>
                                    @else
                                    -
                                    @endif
                                </td>

                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    {{ $daily_work['ticket']['customer']['id'] }}
                                    <a href="{{ route('ticket.show', $daily_work['ticket']['customer']['id']) }}"
                                        class="text-decoration hover:text-gray-400">
                                        {{ $daily_work['ticket']['customer']['customer_code'] ?? '-' }}
                                    </a>
                                    <p class="text-sm text-gray-400">
                                        {{ $daily_work['ticket']['customer']['name'] ?? '-' }}
                                    </p>
                                </td>
                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    {{ $daily_work['total_work_time'] ?? '00:00:00' }}
                                </td>
                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    @if ($daily_work['overtime_hour'])
                                    {{ $daily_work['overtime_hour'] == '00:00:00' ?  ' --- ' : $daily_work['overtime_hour'] }}
                                    @else
                                    ---
                                    @endif
                                </td>
                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    @if ($daily_work['out_of_office_duration'])
                                    {{ $daily_work['out_of_office_duration'] == '00:00:00' ?  ' --- ' : $daily_work['out_of_office_duration'] }}
                                    @else
                                    ---
                                    @endif
                                </td>
                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    {{ $daily_work['is_weekend'] == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    {{ $daily_work['is_holiday'] == 1 ? 'Yes' : 'No' }}
                                </td>

                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    <button type="button"
                                        {{-- data-other-pay="{{ $daily_work->other_pay }}"
                                        data-ticket-work_id="{{ $daily_work->id }}" --}}
                                        class="otherPayoutBtn text-gray-800 dark:text-gray-200"
                                        {{-- data-drawer-target="drawer-other-pay"
                                                data-drawer-show="drawer-other-pay" data-drawer-placement="right"
                                                aria-controls="drawer-other-pay" --}}>
                                        {{ $engineer_currency }}
                                        {{ $daily_work['other_pay'] ?? '0' }}
                                        <span class="sr-only">Icon description</span>
                                    </button>
                                </td>

                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap ">
                                    @php
                                    $totalPayable = (float)$daily_work['daily_gross_pay'] + (float)$daily_work['other_pay'];
                                    @endphp

                                    {{ $engineer_currency }} {{(float)$totalPayable }}
                                    <input type="hidden" class="engineer-total-payable" value="{{(float)$totalPayable }}">
                                </td>

                                <td class="dark:text-slate-200 text-gray-900 px-4 py-2 whitespace-nowrap">
                                    @if ($daily_work['engineer_payout_status'] == 'pending')
                                    <p> <x-badge type="warning" label="Pending" /></p>
                                    @else
                                    <p> <x-badge type="success" label="Paid" /></p>
                                    @endif
                                </td>

                                <td class="flex gap-2 px-4 py-2 whitespace-nowrap">
                                    <!-- <a href="{{ route('ticket.invoice', $daily_work->id) }}"> </a> -->
                                    <button
                                        {{-- type="button" data-ticket-work-id="{{ $daily_work->id }}"
                                        class="editBtn bg-blue-100  font-medium rounded-lg text-sm px-[.6rem] py-[.4rem] text-center  flex"
                                        data-drawer-target="drawer-right-example"
                                        data-drawer-show="drawer-right-example"
                                        data-drawer-placement="right"
                                        aria-controls="drawer-right-example" --}}
                                        type="button"
                                        class="open-ticket-work-modal bg-blue-100  font-medium rounded-lg text-sm px-[.6rem] py-[.4rem] text-center  flex"
                                        data-ticket-work-id="{{ $daily_work->id }}"
                                        data-modal-target="default-modal" data-modal-toggle="default-modal">
                                        <svg class="w-6 h-6 text-primary" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                            <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        <span class="sr-only">Icon description</span>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="14">
                                    <div class="text-center text-lg p-5  dark:text-gray-200 text-gray-900 font-extrabold mx-auto">
                                        <span>
                                            No record found.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="max-w-sm ms-auto mt-10 p-4">
                    <div class="mb-3 {{ $engineer->job_type == 'full_time' ? '' : 'hidden' }} ">
                        <input type="checkbox"
                            name="monthly-pay"
                            id="monthly-pay"
                            class=" w-4 h-4 monthly-check-box text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2  dark:border-gray-600 "
                            onchange="handleMonthlyPayCheck(this)">

                        <input type="hidden" name="monthly-pay" id="monthly-pay-value" value="false">
                        <label class="cursor-pointer mx-1" for="monthly-pay">Monthly Pay</label>
                        <span class="text-gray-900" id="monthlyPay">
                            {{ $engineer_currency }} 0.00
                        </span>

                        @if(!empty($engineer->monthly_payout[$currentMonth]))
                        <div class="text-red-600 text-sm mt-2" id="error-message" style="display: none;">Monthly payout already done for this engineer.</div>
                        @endif
                    </div>

                    <div class="border rounded-lg mb-4 hidden" id="div_total">
                        <div class="flex justify-between p-2">
                            <span class="text-gray-800">Total</span>
                            <span class="text-gray-900" id="total_finale">
                                {{ $engineer_currency }} 0.00
                            </span>
                        </div>
                    </div>

                    <div class=" rounded-lg mb-4 hidden" id="div_zus">
                        <div class="flex justify-between p-2">
                            <span class="text-gray-800">Zus</span>
                            <span class="text-gray-900" >
                                {{ $engineer_currency }} 
                                <input
                                    type="number"
                                    id="zus_input"
                                    name="zus_input"
                                    data-type="zus"
                                    value="0.00"
                                    min="0"
                                    class="w-20 px-3 py-1.5 rounded-md border border-[#38515e] focus:outline-none focus:ring-2 focus:ring-[#496979] transition duration-200 bg-white shadow-sm no-print"
                                    placeholder="Enter amount" />
                            </span>
                        </div>
                    </div>
                    <div class=" rounded-lg mb-4 hidden" id="div_pit">
                        <div class="flex justify-between p-2">
                            <span class="text-gray-800">Pit</span>
                            <span class="text-gray-900" >
                                {{ $engineer_currency }}
                                <input
                                    type="number"
                                    id="pit_input"
                                    name="pit_input"
                                    data-type="pit"
                                    value="0.00"
                                    min="0.00"
                                    class="w-20 px-3 py-1.5 rounded-md border border-[#38515e] focus:outline-none focus:ring-2 focus:ring-[#496979] transition duration-200 bg-white shadow-sm no-print"
                                    placeholder="Enter amount" />
                            </span>
                        </div>
                    </div>

                    <div class="border rounded-lg ">
                        <div class="flex justify-between p-2">
                            <span class="text-gray-800">Gross Pay</span>
                            <span class="text-gray-900" id="gross_pay">
                                {{ $engineer_currency }} 0.00
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-4">
                        <button class="px-7 py-3 border border-primary text-primary rounded-lg" type="reset">Cancel</button>
                        <button class="px-9 py-3 bg-primary bg-blue-800 text-white font-semibold rounded-lg" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button"> Pay Now</button>
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
                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to pay?</h3>
                                <button data-modal-hide="popup-modal" type="submit" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                    Yes, I'm sure
                                </button>
                                <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div id="drawer-right-example"
                class="fixed top-[7%]  right-0 z-40 h-[95vh]  overflow-scroll  p-4  transition-transform translate-x-full  rounded-lg bg-white w-[38vw]  dark:bg-gray-800"
                tabindex="-1" aria-labelledby="drawer-right-label">
                <h5 id="drawer-right-label"
                    class="inline-flex items-center mb-4  text-gray-900 dark:text-gray-400">
                    Daily Payout
                </h5>
                <button type="button" data-drawer-hide="drawer-right-example" aria-controls="drawer-right-example"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close menu</span>
                </button>

                <div class="grid grid-cols-1 gap-2 w-full dark:text-gray-400 text-slate-600  p-2">
                    <p> <strong class="dark:text-gray-300 text-slate-900">Ticket: </strong>
                        <span class="ticket-code"></span>
                    </p>
                    <p>
                        <strong class="dark:text-gray-300 text-slate-900">Task: </strong>
                        <span class="task-name"></span>
                    </p>
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-2  w-full dark:text-gray-400 text-slate-600 p-2 ">

                    {{-- <p> <strong class="dark:text-gray-300 text-slate-900">Ticket: </strong> 
                        <span class="ticket-code"></span></p> --}}

                    <p> <strong class="dark:text-gray-300 text-slate-900">Date: </strong> <span
                            class="start-date"></span></p>
                    <p> <strong class="dark:text-gray-300 text-slate-900">Total Hours: </strong> <span
                            class="total_hours"></span></p>
                    <p> <strong class="dark:text-gray-300 text-slate-900">OT: </strong>
                        {{-- <span class="overtime"></span>
                        <label class="switch ml-2">
                            <input type="checkbox" id="ot-switch">              
                            <span class="slider round"></span>
                        </label> --}}
                        <span class="ot-switch"></span>
                    </p>

                    <p> <strong class="dark:text-gray-300 text-slate-900">OOH: </strong>
                        <span class="ooh-switch"></span>

                        {{-- <label class="switch">
                            <input type="checkbox" id="ooh-switch">
                            <span class="slider round"></span>
                        </label> --}}
                    </p>

                    <p> <strong class="dark:text-gray-300 text-slate-900">WW:</strong>
                        <span class="ww-switch"></span>
                        {{-- <label class="switch">
                            <input type="checkbox" id="ww-switch">
                            <span class="slider round"></span>
                        </label> --}}
                    </p>
                    <p> <strong class="dark:text-gray-300 text-slate-900">HW:</strong>
                        <span class="hw-switch"></span>
                    </p>
                    {{-- <label class="switch">
                            <input type="checkbox" id="hw-switch">
                            <span class="slider round"></span>
                        </label> --}}
                    </p>
                </div>

                <div class="grid grid-cols-1 w-full dark:text-gray-400 text-slate-600 p-2 ">
                    <strong class="dark:text-gray-300 text-slate-900">Engineer Rates </strong>
                </div>

                <div class="grid grid-cols-3 gap-4 w-full dark:text-gray-400 text-slate-600 p-2 ">
                    <p>
                        <strong class="dark:text-gray-300 text-slate-900">Hourly: </strong>
                        <span class="hourly-rate"></span>
                    </p>
                    <p>
                        <strong class="dark:text-gray-300 text-slate-900">FullDay: </strong>
                        <span class="fullday-rate"></span>
                    </p>
                    <p>
                        <strong class="dark:text-gray-300 text-slate-900">HalfDay: </strong>
                        <span class="halfday-rate"></span>
                    </p>
                </div>

                <div class="gap-4 w-full dark:text-gray-400 text-slate-600 mt-4 p-3 shadow-lg rounded-lg border border-gray-300 dark:border-gray-600">
                    <h5 class=" text-gray-900 dark:text-gray-100 mb-2 tracking-wide">Other Expenses</h5>

                    <div id="expenses-list" class="space-y-3">
                        <!-- Expenses will be dynamically added here -->
                    </div>
                </div>

                <div class="mt-3 p-3 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-300 dark:border-gray-600">
                    <h5 class="  text-gray-900 dark:text-gray-100 mb-4  tracking-wide">Payment Summary</h5>

                    <div class="border border-gray-300 dark:border-gray-600 rounded-md overflow-hidden">
                        <div class="grid grid-cols-3 bg-gray-100 dark:bg-gray-700 px-4 py-2 font-semibold text-gray-900 dark:text-gray-200">
                            <div>Item/Service</div>
                            <div class="text-center">Action</div>
                            <div class="text-right">Amount</div>
                        </div>
                        <ul id="net-payable-list" class="divide-y divide-gray-300 dark:divide-gray-600"></ul>
                    </div>

                    <!-- Subtotal, Tax, and Total -->
                    <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                        {{-- <div class="flex justify-between text-gray-800 dark:text-gray-300 text-lg font-semibold">
                            <span>Subtotal (Hourly Payable):</span>
                            <span id="subtotal">0</span>
                        </div>
                        <div class="flex justify-between text-gray-800 dark:text-gray-300 text-lg font-semibold">
                            <span>Services Total:</span>
                            <span id="service-amount">0</span>
                        </div>
                        <hr /> --}}
                        <div class="flex justify-between text-gray-800 dark:text-gray-300 text-lg font-semibold">
                            <span>Total:</span>
                            <span id="total-amount">0</span>
                        </div>
                    </div>
                </div>

            </div>

            <div id="drawer-other-pay"
                class="fixed top-[10%]  right-0 z-40 h-auto    p-4  transition-transform translate-x-full  rounded-lg bg-white w-[25vw]  dark:bg-gray-800"
                tabindex="-1" aria-labelledby="drawer-right-label">
                <h5 id="drawer-right-label"
                    class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
                    <svg class="w-5 h-5 mr-2 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                            clip-rule="evenodd" />
                    </svg>
                    Other Pay
                </h5>
                <button type="button"
                    {{-- data-drawer-hide="drawer-other-pay" 
                    aria-controls="drawer-other-pay" --}}
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close menu</span>
                </button>
                <ul class="grid grid-cols-2 gap-4  w-full dark:text-gray-400 text-slate-600 mt-4  p-2  border-b border-slate-400"
                    id="other-pay-list">

                </ul>
                <form action="{{ route('other-pay.update') }}" method="POST">
                    @csrf <!-- CSRF token for form security -->

                    <div
                        class="grid grid-cols-2 gap-4 w-full dark:text-gray-400 text-slate-600 mt-4 p-2 border-b border-slate-400">
                        <div>
                            <x-text-field id="other_pay" name="other_pay" label="Other Pay"
                                placeholder="Enter Other Pay" class="" value="" />
                        </div>

                        <div>
                            <input id="ticket_work_id" class="ticket_work_id" type="text" name="ticket_work_id" value=""
                                hidden />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 w-full dark:text-gray-400 text-slate-600 mt-4 p-2">
                        <button type="submit"
                            class="text-white bg-primary-light-one hover:bg-[#16465f] font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Submit</button>
                        <button type="button"
                            class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"
                            {{-- data-drawer-hide="drawer-other-pay" 
                            aria-controls="drawer-other-pay" --}}>Cancel</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Main modal -->
    <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full  max-w-[80%] max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Daily Payout
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
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

    <!--  Toast  -->

    <div id="toast-warning" class="hidden fixed top-5 right-5 flex items-center w-full max-w-xs p-4 rounded-lg shadow-lg bg-red-100 dark:bg-red-800 border border-red-300 dark:border-red-700" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-700 bg-red-200 rounded-lg dark:bg-red-900 dark:text-red-300">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a1 1 0 012 0v4a1 1 0 01-2 0V6zm1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm text-red-800 dark:text-red-200 font-medium">
            Monthly payout has already been processed for this engineer.
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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        var engineerData = @json($engineer);
        var selectedMonth = @json($currentMonth);
    </script>

    <script>
        function getQueryParam(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }


        console.log('engineerData---', engineerData);
        console.log('selectedMonth---', selectedMonth);
        const currentYear = new Date().getFullYear();
        const formattedMonth = `${currentYear}-${selectedMonth.toString().padStart(2, '0')}`;



        $(document).ready(function() {
            const ticketWorkId = getQueryParam('ticket_work');
            if (ticketWorkId) {
                setTimeout(() => {
                    $('button[data-ticket-work-id="' + ticketWorkId + '"]').trigger('click');
                }, 1000);
            }
        })

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

        $('#monthly-pay').change(function() {
            // const currentMonth = new Date().toISOString().slice(0, 7); // 'YYYY-MM'

            // Parse the JSON string to object (ONLY if it's a string)
            let monthlyPayoutData = engineerData.monthly_payout;
            if (typeof monthlyPayoutData === 'string') {
                try {
                    monthlyPayoutData = JSON.parse(monthlyPayoutData);
                } catch (error) {
                    console.error('Invalid JSON:', error);
                    monthlyPayoutData = {}; // fallback
                }
            }

            // console.log('Parsed monthlyPayoutData:', monthlyPayoutData);
            // console.log('currentMonth:', currentMonth);

            if (monthlyPayoutData && monthlyPayoutData[formattedMonth]) {
                if ($(this).is(':checked')) {
                    $(this).prop('checked', false); // Uncheck the checkbox
                    showToast(); // Show the toast
                }
            } else {
                $('#error-message').hide(); // Hide error message if monthly payout not found
            }
            toggleTotalDiv()
        });



        document.getElementById('monthly-pay').addEventListener('change', function() {
            document.getElementById('monthly-pay-value').value = this.checked ? "true" : "false";
        });


        $(document).on('change', '.check-all-box', function() {
            let isChecked = $(this).is(':checked'); // Check if .check-all-box is checked
            $('.daily-work-check-box').prop('checked',
                isChecked); // Apply checked state to all .daily-work-check-box
            calculateGrossPay();
            toggleTotalDiv()
        });

        $(document).on('change', '.monthly-check-box', function() {
            let isChecked = $(this).is(':checked'); // Check if .check-all-box is checked
            calculateGrossPay();
        });

        // Uncheck .check-all-box if any .daily-work-check-box is unchecked
        $(document).on('change', '.daily-work-check-box', function() {
            if (!$(this).is(':checked')) {
                $('.check-all-box').prop('checked',
                    false); // Uncheck .check-all-box if a daily-work-check-box is unchecked
            } else {
                // Check if all .daily-work-check-box checkboxes are checked
                let allChecked = $('.daily-work-check-box').length === $('.daily-work-check-box:checked').length;
                $('.check-all-box').prop('checked', allChecked); // Check .check-all-box if all are checked
            }

            toggleTotalDiv()
        });

        function toggleTotalDiv()
        {
            if($('.daily-work-check-box:checked').length > 0 || $("#monthly-pay").is(':checked')){
                $("#div_total").show();
                $("#div_zus").show();
                $("#div_pit").show();                
            }else{
                $("#div_total").hide();
                $("#div_zus").hide();
                $("#div_pit").hide();
            }
        }

        $("#zus_input").keyup(function(){
            let value_of_input = $(this).val();
            if(value_of_input < 0){
                $(this).val(0.00)
                $("#total_zus").val(0.00)
            }else{
                $("#total_zus").val(value_of_input);
            }
            calculateGrossPay()
        });

        $("#pit_input").keyup(function(){
            let value_of_input = $(this).val();
            if(value_of_input < 0){
                $(this).val(0.00)
                $("#total_pit").val(0.00)
            }else{
                $("#total_pit").val(value_of_input);
            }
            calculateGrossPay()
            
        });

        $('#payoutForm').submit(function(e) {
            // Check if any checkbox with class `daily-work-check-box` is checked
            let isDailyChecked = $('.daily-work-check-box:checked').length > 0;
            let isMonthlyChecked = $('.monthly-check-box:checked').length > 0;


            if (!isDailyChecked && !isMonthlyChecked) {
                e.preventDefault(); // Prevent form submission
                alert('Please select at least one checkbox (daily or monthly) before submitting!');
            }
        });

        $('.editBtn').on('click', function() {

            var work_id = $(this).data('ticket-work-id');
            $('#ticket_work_id').val(work_id);
            let storageLink = $('#storage_link').val();

            // Make the AJAX call
            $.ajax({
                url: `/payout/get-ticket-data/${work_id}`,
                method: 'GET',
                success: function(response) {

                    console.log('ticket-code', response);


                    if (response.status == 'success') {


                        $('#daily_work_data_id').val(response.daily_work_data.id);

                        totalOtherExp = 0; // Reset totalExp before recalculating
                        let engineerCharges = response.daily_work_data.eng_charge;
                        let engineerExtraPay = response.extra_pay;
                        let currencyIcon = '';
                        // $, €, £
                        if (engineerCharges.currency_type == 'dollar') {
                            currencyIcon = '$';
                        } else if (engineerCharges.currency_type == 'pound') {
                            currencyIcon = '£';
                        } else if (engineerCharges.currency_type == 'euro') {
                            currencyIcon = '€';
                        }

                        $('.ticket-code').html('#' + response.daily_work_data.ticket.ticket_code);
                        $('.task-name').html(response.daily_work_data.ticket.task_name);
                        $('.hourly-rate').html(currencyIcon + response.daily_work_data.eng_charge.hourly_charge);
                        $('.fullday-rate').html(currencyIcon + response.daily_work_data.eng_charge.full_day_charge);
                        $('.halfday-rate').html(currencyIcon + response.daily_work_data.eng_charge.half_day_charge);

                        // hourly-rate
                        // fullday-rate
                        // halfday-rate

                        $('#travel_cost').val(response.daily_work_data.travel_cost);
                        $('#tool_cost').val(response.daily_work_data.tool_cost);

                        $('#overtime').val(response.daily_work_data.overtime_hour);

                        $('.start-date').html(response.daily_work_data.work_start_date)
                        $('.total_hours').html(response.daily_work_data.total_work_time)
                        $('.overtime').html(response.daily_work_data.overtime_hour)

                        // $('#ooh-switch').prop('checked', response.daily_work_data.is_out_of_office_hours ? true : false);
                        $('.ooh-switch').html(response.daily_work_data.is_out_of_office_hours ? " Yes " : "No");
                        // $('#hw-switch').prop('checked', response.daily_work_data.is_holiday ? true : false);
                        $('.hw-switch').html(response.daily_work_data.is_holiday ? " Yes " : " No ");
                        // $('#ww-switch').prop('checked', response.daily_work_data.is_weekend ? true : false);
                        $('.ww-switch').html(response.daily_work_data.is_weekend ? " Yes " : " No ");
                        // $('#ot-switch').prop('checked', response.daily_work_data.is_overtime ? true : false);
                        $('.ot-switch').html(response.daily_work_data.is_overtime ? " Yes " : " No ");

                        if (response.daily_work_data.is_out_of_office_hours) {
                            $('.ooh').html(` <x-badge type="success" label="Yes" />`)
                        } else {
                            $('.ooh').html(` <x-badge type="warning" label="No" />`)
                        }
                        if (response.daily_work_data.is_holiday) {
                            $('.hw').html(` <x-badge type="success" label="Yes" />`)
                        } else {
                            $('.hw').html(` <x-badge type="warning" label="No" />`)
                        }
                        if (response.daily_work_data.is_weekend) {
                            $('.ww').html(` <x-badge type="success" label="Yes" />`)
                        } else {
                            $('.ww').html(` <x-badge type="warning" label="No" />`)
                        }

                        const expensesList = document.getElementById("expenses-list");

                        const workExpenses = response.daily_work_data
                            .work_expense;

                        expensesList.innerHTML = ""; // Clear old data

                        if (workExpenses.length > 0) {
                            workExpenses.forEach((expense, index) => {

                                const expanseValue = Number(expense.expense) || 0
                                totalOtherExp += expanseValue;
                                const documentUrl = expense.document ?
                                    `${storageLink}/${expense.document}` : "";

                                const expenseItem = document.createElement("div");
                                expenseItem.className = "flex items-center justify-between bg-white dark:bg-gray-700 p-2 rounded-lg shadow-md border border-gray-300 dark:border-gray-600 space-x-3 ";
                                expenseItem.innerHTML = `
                                    <div class="flex items-center space-x-3">
                                        <div> ${index+1}. </div>
                                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">${expense.name}</span>
                                    </div>
                                    <div class="flex">` +
                                    (documentUrl != '' ?
                                        `<a href="${documentUrl}" target="_blank">
                                            <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-2 py-1 rounded-md">View</button>
                                        </a>` : ``) +
                                    `<div class="bg-blue-100 dark:bg-gray-500 text-blue-600 dark:text-white px-3 py-1 rounded-md text-sm font-semibold ml-2">
                                            ${currencyIcon} ${expense.expense} 
                                        </div>
                                    </div>`;
                                expensesList.appendChild(expenseItem);
                            });
                        } else {
                            const expenseItem = document.createElement("div");
                            expenseItem.className = "flex items-center justify-between bg-white dark:bg-gray-700 p-2 rounded-lg shadow-md border border-gray-300 dark:border-gray-600 space-x-3 ";
                            expenseItem.innerHTML = ` <div> No other expense. </div> `;
                            expensesList.appendChild(expenseItem);
                        }


                        // update payouts and other expenses
                        updatePaymentDetails(currencyIcon, engineerExtraPay, response.daily_work_data.hourly_payable, totalOtherExp, response.daily_work_data);

                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                    alert('Error fetching data. Please try again.');
                }
            });
        });

        $('.open-ticket-work-modal').on('click', function() {

            let storageLink = "{{ asset('storage') }}";
            let ticketWorkId = $(this).data('ticket-work-id'); // Get the Ticket ID

            $.ajax({
                url: '{{ route("engTicket.fetchPopup") }}',
                method: 'GET',
                data: {
                    id: ticketWorkId
                },
                success: function(response) {
                    $('#TicketDetailPop').html(response.html); // Update popup content
                    initializeTooltips()
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Error fetching ticket details. Please try again.');
                }
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

        function reloadWithQueryParam(param, value) {
            let url = new URL(window.location.href);
            url.searchParams.set(param, value); // Add or update the query parameter
            window.location.href = url.toString(); // Reload the page with the new URL
        }

        function updatePaymentDetails(currency, engineerExtraPay, totalHourlyPayable, totalOtherExp, dailyWorkDetail) {

            let totalSum = 0;
            let servicesTotalSum = 0;

            const paymentDetails = [{
                    name: "Hourly Payable",
                    amount: totalHourlyPayable,
                    type: 'hourly_payable',
                    isEdit: true,


                },
                {
                    name: "Overtime",
                    amount: dailyWorkDetail.overtime_payable || 0,
                    type: 'ot',
                    isEdit: true,


                },
                {
                    name: "Out of Office Hour",
                    amount: dailyWorkDetail.out_of_office_payable || 0,
                    type: 'ooh',
                    isEdit: true,
                },
                {
                    name: "Weekend Work",
                    amount: dailyWorkDetail.weekend_payable || 0,
                    type: 'ww',
                    isEdit: true,
                },

                {
                    name: "Holiday Work",
                    amount: dailyWorkDetail.holiday_payable || 0,
                    type: 'hw',
                    isEdit: true,
                },
                {
                    name: "Other Expense",
                    amount: totalOtherExp,
                    type: 'other_expense',
                    isEdit: false,
                }
            ];

            const netPayableList = document.getElementById("net-payable-list");
            // const actualAmountList = document.getElementById("actual-amount-list");

            // ✅ Clear previous content to prevent duplicates
            netPayableList.innerHTML = "";

            // actualAmountList.innerHTML = "";

            $('#subtotal').html(`${currency} ${totalHourlyPayable}`);

            // ✅ Append new items
            paymentDetails.forEach((item, index) => {

                totalSum += item.amount;

                if (index >= 0) {
                    servicesTotalSum += item.amount;
                }

                const payableItem = document.createElement("li");
                payableItem.className = "text-gray-800 dark:text-gray-300 font-medium text-[.9rem]";
                payableItem.textContent = item.name;

                const amountItem = document.createElement("li");
                amountItem.className = "text-gray-900 dark:text-gray-100 font-semibold text-[.9rem]";
                amountItem.textContent = `${currency} ${item.amount}`;

                // netPayableList.appendChild(payableItem);
                // actualAmountList.appendChild(amountItem);

                if (index >= 0) {

                    const row = document.createElement("div");
                    row.className = "grid grid-cols-3 px-4 py-2 text-gray-800 dark:text-gray-300 items-center";
                    row.innerHTML = `<div>${item.name}</div>`

                    row.innerHTML += item.isEdit ?
                        `<button class="edit-btn text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br 
                            focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600 font-medium rounded-lg text-sm 
                            px-[.9rem] py-[.4rem] mx-auto w-fit text-center flex items-center gap-1" 
                            data-index="${index}" data-type="${item.type}" data-amount="${item.amount}">

                                <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                            </svg>


                        </button>
                        ` : `<span> </span>`
                    row.innerHTML += ` <div class="text-right font-semibold">${currency} ${item.amount}</div>`;
                    netPayableList.appendChild(row);

                }

            });

            // console.log('totalSum == ', totalSum);
            // console.log('servicesTotalSum == ', servicesTotalSum);


            // $('#daily_total_payable').val(totalSum);
            // $('#service-amount').html(`${currency} ${servicesTotalSum}`);
            // $('#total-amount').html(`${currency} ${totalSum.toFixed(2)}`);

            // past from customer 
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

            return paymentDetails; // If needed elsewhere
        }

        $("#editAmountForm").submit(function(e) {
            e.preventDefault();

            let pay_type = $("#pay_type").val(); // Ensure pay_type is present in your form
            let dialy_workId = $("#daily_work_data_id").val(); // Ensure pay_type is present in your form
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
                dialy_workId: dialy_workId,
                amount: updatedAmount, // Use correct field name for backend
            };

            console.log("Submitting formData:", formData);

            $.ajax({
                url: "/update-eng-payable-amount", // Ensure this route is correct
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


        console.log(engineerData.engg_charge.monthly_charge);

        var monthlyCharge = engineerData.engg_charge.monthly_charge || 0;
        let jobType = engineerData?.job_type || "";
        let currencySymbol = "{{ $engineer_currency }}";

        // Function to calculate gross pay
        function calculateGrossPay() {
            let total = 0;
            let monthlyTotal = 0;


            if (jobType === "full_time") {
                monthlyTotal += parseFloat(monthlyCharge) || 0;
            }

            // Update Gross Pay while keeping the currency symbol
            $("#monthlyPay").html(currencySymbol + " " + monthlyTotal.toFixed(2));

            // Loop through checked checkboxes
            $("input[type='checkbox'].daily-work-check-box:checked").each(function() {
                let row = $(this).closest("tr"); // Get the row of the checkbox
                let grossPay = parseFloat(row.find("input.engineer-total-payable").val()) || 0; // Extract hidden input value
                total += grossPay;
            });

            let display_total = 0;
            let display_grosspay = 0;

            // Update Gross Pay while keeping the currency symbol
            let zus_amount = parseFloat($("#zus_input").val());
            let pit_amount = parseFloat($("#pit_input").val());

            let zus_pit_total = zus_amount + pit_amount;

            if ($("#monthly-pay").is(":checked")) {
                $("#gross_pay").html(currencySymbol + " " + (monthlyTotal + total - zus_pit_total).toFixed(2));
                $("#total_finale").html(currencySymbol + " " + (monthlyTotal + total).toFixed(2));
                total += monthlyTotal;
            } else {
                $("#total_finale").html(currencySymbol + " " + (monthlyTotal + total).toFixed(2));
                $("#gross_pay").html(currencySymbol + " " + (total.toFixed(2) - zus_pit_total).toFixed(2));
            }

            // If job type is full-time, add monthly charge

            $("#total_payable").val(total.toFixed(2));
        }

        // Initialize Gross Pay on page load
        $(document).ready(function() {
            calculateGrossPay();
        });

        // Listen for checkbox changes
        $(".daily-work-check-box").on("change", calculateGrossPay);

        $('#engineer_filter_options').change(function() {
            console.log('$(this).val()', $(this).val());

            if ($(this).val() != '') {
                window.location.href = "/payout/" + $(this).val() + "?filter=all";
            }
        });

        let engineerId = document.getElementById('engineer').getAttribute('data-id');
        $('#month').change(function() {
            console.log('$(this).val()', $(this).val());

            if ($(this).val() != '') {
                window.location.href = "/payout/" + engineerId + "?filter=all&month=" + $(this).val();
            }

        })
        if($(".daily-work-check-box").length === 0)
        {
            $("#default-checkbox").hide();
        }
    </script>
    @endsection