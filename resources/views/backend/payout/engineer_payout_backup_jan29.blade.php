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
<div class="overflow-x-hidden">
    <div class="card  relative">
        <input type="hidden" id="storage_link" name="storage_link" value="<?php echo asset('storage/'); ?>">
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Engineer Payout
            </h3>
            <div class="flex">
                <div class="engineers mr-2">
                    <select class="dropdownField bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 ">
                        @foreach ($engineers as $eng)
                        <option value="{{ $engineer['id'] }}">
                            {{ $eng['name'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="inline-flex rounded-md shadow-xs mr-2" role="group">
                    <a href="{{ route('payout.show', $engineer->id ) }}?filter=all"
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
                    <a href="{{ route('payout.show', $engineer->id ) }}?filter=unpaid"
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
        </div>
        {{-- card-body  --}}
        <div class="card-body ">

            <form method="POST" id="payoutForm" action="{{ route('payout.store') }}">

                @csrf

                <input type="hidden" name="engineer_id" value="{{ $engineer->id }}" />

                <div
                    class="mt-2 border  border-gray-200 dark:border-gray-700 overflow-scroll overflow-y-hidden rounded-xl">

                    <table id="search-table" class="w-full border rounded-xl " style="border-radius: 12px">
                        <thead>
                            <tr>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <input id="default-checkbox" type="checkbox" value=""
                                        class="w-4 h-4 check-all-box text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-200 dark:border-gray-600">
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        Sr.
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        Ticket
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        Date
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        Client
                                    </span>
                                </th>

                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        Hours
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        OT/OOH
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        WW/HW
                                    </span>
                                </th>
                                {{-- <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                        <span class="flex items-center text-lg">
                                            Travel/Tool
                                        </span>
                                    </th> --}}
                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        Other Pay
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        Gross Pay
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        Payment Status
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 px-3 py-1">
                                    <span class="flex items-center text-lg">
                                        Action
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="p-2">
                            @php
                            $total = 0;
                            $overtimePayable = 0;
                            $grossPay = 0;
                            @endphp
                            @if (!empty($daily_works))
                            @foreach ($daily_works as $key => $daily_work)
                                @php
                                    if ($daily_work['overtime_hour']) {
                                        $overtimePayable += 50;
                                    }
                                    if ($daily_work['engineer_payout_status'] != 'paid') {
                                        $grossPay +=
                                        (float) $daily_work['hourly_payable'] +
                                        (float) $daily_work['travel_cost'] +
                                        (float) $daily_work['tool_cost'] +
                                        (float) $daily_work['overtime_hour'];
                                    }
                                @endphp
                                <tr class="font-bold p-4">
                                    <td class="dark:text-black-200 text-gray-900 px-4 py-1 whitespace-nowrap">
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
                                            class="w-4 h-4 daily-work-check-box text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2  dark:border-gray-600  <?php echo $daily_work['engineer_payout_status'] == 'pending' ? ' dark:bg-gray-200 ' : ' dark:bg-gray-500 '; ?>  "
                                            <?php echo $daily_work['engineer_payout_status'] == 'paid' ? ' disabled ' : ''; ?>>
                                        @endif
                                    </td>
                                    <td class="dark:text-slate-200  text-gray-900 px-4 py-1 whitespace-nowrap">
                                        {{ $key + 1 }}
                                    </td>
                                    <td class="dark:text-slate-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                        @if (isset($daily_work['ticket']['id']))
                                        <a href="{{ route('ticket.show', $daily_work['ticket']['id']) }}"
                                            class="text-decoration hover:text-gray-400">
                                            {{ $daily_work['ticket']['ticket_code'] }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="dark:text-slate-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                        {{ $daily_work['work_start_date'] }}
                                    </td>
                                    <td class="dark:text-slate-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                        {{ $daily_work['ticket']['client_name'] ?? '-' }}
                                    </td>
                                    <td class="dark:text-slate-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                        {{ $daily_work['total_work_time'] ?? '00:00:00' }} 
                                    </td>
                                    <td class="dark:text-slate-200 text-gray-900 px-4 py-1 whitespace-nowrap">

                                        @if ($daily_work['overtime_hour'])
                                        {{ $daily_work['overtime_hour'] == '00:00:00' ?  ' --- ' : $daily_work['overtime_hour'] }} / {{ $daily_work['is_weekend'] == 1 ? 'Yes' : 'No' }}
                                            {{-- <x-badge 
                                                class="text-2xl" 
                                                type="success"
                                                label="{{ $daily_work['overtime_hour'] ? $daily_work['overtime_hour'] : ' --- ' }} / --- " 
                                            /> --}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="dark:text-slate-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                        {{ $daily_work['is_weekend'] == 1 ? 'Yes' : 'No' }} /
                                        {{ $daily_work['is_holiday'] == 1 ? 'Yes' : 'No' }}
                                    </td>


                                    <td class="flex gap-2 px-4 py-1 whitespace-nowrap">
                                        <button type="button"
                                             {{-- data-other-pay="{{ $daily_work->other_pay }}"
                                            data-ticket-work_id="{{ $daily_work->id }}" --}}
                                            class="otherPayoutBtn text-gray-800 dark:text-gray-200"
                                            {{-- data-drawer-target="drawer-other-pay"
                                            data-drawer-show="drawer-other-pay" data-drawer-placement="right"
                                            aria-controls="drawer-other-pay" --}}
                                        >
                                            {{ $engineer_currency }}
                                            {{ $daily_work['other_pay'] ?? '0' }}
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </td>

                                    <td class="dark:text-slate-200 text-gray-900 px-4 py-1 whitespace-nowrap ">
                                        @php

                                            $totalPayable = (float)$daily_work['hourly_payable'] + (float)$daily_work['other_pay'];
                                            
                                            if($daily_work['is_holiday'] == 1) {
                                                $totalPayable += (float)$engineer->enggExtraPay->public_holiday;
                                            }

                                            if($daily_work['is_out_of_office_hours'] == 1) {
                                                $totalPayable += (float)$engineer->enggExtraPay->out_of_office_hour;
                                            }

                                            if($daily_work['is_weekend'] == 1) {
                                                $totalPayable += (float)$engineer->enggExtraPay->weekend;
                                            }

                                            if($daily_work['is_overtime'] == 1) {
                                                $totalPayable += (float)$engineer->enggExtraPay->overtime;
                                            }

                                            $total += $totalPayable ;
                                            
                                        @endphp

                                        {{ $engineer_currency }}  {{(float)$totalPayable }}
                                    </td>

                                    <td class="dark:text-slate-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                                        @if ($daily_work['engineer_payout_status'] == 'pending')
                                        <p> <x-badge type="warning" label="Pending" /></p>
                                        @else
                                        <p> <x-badge type="success" label="Paid" /></p>
                                        @endif
                                    </td>
                                    
                                    <td class="flex gap-2 px-4 py-1 whitespace-nowrap">
                                        @if ($daily_work['engineer_payout_status'] != 'paid')
                                        <!-- <a href="{{ route('ticket.invoice', $daily_work->id) }}"> </a> -->
                                        <button type="button" data-ticket-work-id="{{ $daily_work->id }}"
                                            class="editBtn text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-[.9rem] py-[.4rem] text-center  flex"
                                            data-drawer-target="drawer-right-example"
                                            data-drawer-show="drawer-right-example"
                                            data-drawer-placement="right"
                                            aria-controls="drawer-right-example">
                                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="10">
                                    <div class="text-center">
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

                <div class="grid grid-cols-12 gap-4 mt-4 border dark:border-slate-600 rounded-xl p-4">

                    <div class="col-span-4">
                        <x-text-area id="note" name='note' label="Note" placeholder="Payout Note"
                            class="" value="" />
                    </div>
                    <div class="col-span-5"> </div>
                    <div class="grid col-span-3">
                        <div class="dark:text-black-200 text-gray-900 p-2 ">
                            <x-input-number id="extra_incentive" name='extra_incentive' label="Extra Incentive"
                                placeholder="Extra / Incentive" class="" value="0" />
                            <input id="total_payable" name='total_payable' hidden value="0" />
                        </div>
                        <div class="dark:text-gray-200 text-gray-900 p-2">
                            <x-input-number id="gross_pay" name='gross_pay' label="Gross Pay"
                                placeholder="Gross Pay" class="" value="{{ $total }}" />
                        </div>
                    </div>
                </div>

                <div class="mt-5 border border-gray-200 dark:border-gray-700 rounded-xl p-4 ">
                    <div class="grid md:grid-cols-4 gap-2">
                        <div class="md:col-span-1  ">
                            <button type="reset" id='cancelButton'
                                class="text-gray-700 hover:text-white border border-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-center me-2 dark:border-gray-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 justify-center flex items-center w-full focus:ring-4font-medium rounded-lg text-sm py-2.5">
                                Cancel
                            </button>
                        </div>
                        <div>
                            <button type="submit"
                                class="text-white justify-center flex items-center bg-primary-light-one hover:bg-[#16465f] w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Pay
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div id="drawer-right-example"
                class="fixed top-[10%]  right-0 z-40 h-[90vh]  overflow-scroll  p-4  transition-transform translate-x-full  rounded-lg bg-white w-[38vw]  dark:bg-gray-800"
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
                    Daily Ticket Work
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
                {{-- <div
                        class="grid grid-cols-2 gap-4  w-full dark:text-gray-400 text-slate-600 mt-4  p-2 pb-10  border-b border-slate-400 ">

                        <p> <strong class="dark:text-gray-300 text-slate-900">Date: </strong> <span
                                class="start-date"></span></p>
                        <p> <strong class="dark:text-gray-300 text-slate-900">Total Hours: </strong> <span
                                class="total_hours"></span></p>
                        <p> <strong class="dark:text-gray-300 text-slate-900">OT: </strong> <span class="overtime"></span>
                        </p>
                        <p> <strong class="dark:text-gray-300 text-slate-900">OOH: </strong> <span class="ooh"></span>
                        </p>
                        <p> <strong class="dark:text-gray-300 text-slate-900">WW:</strong> <span class="ww"></span>
                        </p>
                        <p> <strong class="dark:text-gray-300 text-slate-900">HW:</strong> <span class="hw"></span>
                        </p>
                    </div>
                    <form action="{{ route('daily-payout.update') }}" method="POST">
                @csrf <!-- CSRF token for form security -->

                <div
                    class="grid grid-cols-2 gap-4 w-full dark:text-gray-400 text-slate-600 mt-4 p-2  border-b border-slate-400">
                    <div>
                        <x-text-field id="travel_cost" name="travel_cost" label="Travel Cost"
                            placeholder="Enter Travel Cost" class="" value="" />
                    </div>

                    <div>
                        <x-text-field id="tool_cost" name="tool_cost" label="Tool Cost"
                            placeholder="Enter Tool Cost" class="" value="" />
                    </div>

                    <div>
                        <x-text-field id="daily_total_payable" name="daily_total_payable"
                            label="Daily Total Payable" placeholder="Total Payable" class=""
                            value="" />
                    </div>

                    <div>
                        <x-text-field id="overtime" name="overtime" label="Overtime" placeholder="Overtime"
                            class="" value="" />
                    </div>

                    <div>
                        <input id="ticket_work_id" type="text" name="ticket_work_id" value="" hidden />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 w-full dark:text-gray-400 text-slate-600 mt-4 p-2">
                    <button type="submit"
                        class="text-white bg-primary-light-one hover:bg-[#16465f] font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Submitsss</button>

                    <button type="button"
                        class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"
                        data-drawer-hide="drawer-right-example"
                        aria-controls="drawer-right-example">Cancel</button>
                </div>
                </form> --}}

                {{-- --}}

                <div
                    class="grid grid-cols-3 gap-4  w-full dark:text-gray-400 text-slate-600 mt-4  p-2  border-b border-slate-400 ">

                    <p> <strong class="dark:text-gray-300 text-slate-900">Date: </strong> <span
                            class="start-date"></span></p>
                    <p> <strong class="dark:text-gray-300 text-slate-900">Total Hours: </strong> <span
                            class="total_hours"></span></p>
                    <p> <strong class="dark:text-gray-300 text-slate-900">OT: </strong> 
                        <span class="overtime"></span>  
                        <label class="switch ml-2">
                            <input type="checkbox" id="ot-switch">
                            <span class="slider round"></span>
                        </label>
                    </p>
                    <p> <strong class="dark:text-gray-300 text-slate-900">OOH: </strong>
                        <label class="switch">
                            <input type="checkbox" id="ooh-switch">
                            <span class="slider round"></span>
                        </label>
                    </p>

                    <p> <strong class="dark:text-gray-300 text-slate-900">WW:</strong>
                        <label class="switch">
                            <input type="checkbox" id="ww-switch">
                            <span class="slider round"></span>
                        </label>
                    </p>
                    <p> <strong class="dark:text-gray-300 text-slate-900">HW:</strong>
                        <label class="switch">
                            <input type="checkbox" id="hw-switch">
                            <span class="slider round"></span>
                        </label>
                    </p>
                </div>

                <div class="gap-4 w-full dark:text-gray-400 text-slate-600 mt-4 p-2  border-b border-slate-400">
                    <h4 class="text-lg font-semibold mb-3">Other Expenses</h4>

                    <div id="expenses-list" class="space-y-3">
                        <!-- Expenses will be dynamically added here -->
                    </div>
                </div>

                <!-- New Section for Net Payable and Actual Amount -->
                {{-- <div class="mt-6 p-4 bg-gray-200 dark:bg-gray-600 shadow-md rounded-lg">
                    <h4 class="text-lg font-semibold mb-3">Payment Summary</h4>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h5 class="text-[1rem] font-semibold text-gray-800 dark:text-gray-300">Net Payable</h5>
                            <ul id="net-payable-list" class="space-y-2 mt-2"></ul>
                        </div>
                        <div>
                            <h5 class="text-[1rem] font-semibold text-gray-800 dark:text-gray-300">Amount</h5>
                            <ul id="actual-amount-list" class="space-y-2 mt-2"></ul>
                        </div>
                    </div>

                </div> --}}
                <div class="mt-6 p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-300 dark:border-gray-600">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 uppercase tracking-wide">Payment Summary</h4>

                    <div class="border border-gray-300 dark:border-gray-600 rounded-md overflow-hidden">
                        <div class="grid grid-cols-2 bg-gray-100 dark:bg-gray-700 px-4 py-2 font-semibold text-gray-900 dark:text-gray-200">
                            <div>Item/Service</div>
                            <div class="text-right">Amount (₹)</div>
                        </div>
                        <ul id="net-payable-list" class="divide-y divide-gray-300 dark:divide-gray-600"></ul>
                    </div>

                    <!-- Subtotal, Tax, and Total -->
                    <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                        <div class="flex justify-between text-gray-800 dark:text-gray-300 text-lg font-semibold">
                            <span>Subtotal (Hourly Payable):</span>
                            <span id="subtotal">0</span>
                        </div>
                        <div class="flex justify-between text-gray-800 dark:text-gray-300 text-lg font-semibold">
                            <span>Services Total:</span>
                            <span id="service-amount">0</span>
                        </div>
                        <hr />
                        <div class="flex justify-between text-gray-800 dark:text-gray-300 text-lg font-semibold">
                            <span>Total:</span>
                            <span id="total-amount">0</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('daily-payout.update') }}" method="POST">
                    @csrf <!-- CSRF token for form security -->

                    <div
                        class="grid grid-cols-2 gap-4 w-full dark:text-gray-400 text-slate-600 mt-4 p-2 border-b border-slate-400">
                        {{-- <div>
                            <x-text-field id="daily_total_payable" name="daily_total_payable"
                                label="Total Payable" placeholder="Total Payable" class=""
                                value="" />
                        </div> --}}

                        {{-- <div>
                            <x-text-field id="overtime" name="overtime" label="Overtime" placeholder="Overtime"
                                class="" value="" />
                        </div> --}}

                        <div>
                            <input id="ticket_work_id" type="text" name="ticket_work_id" value="" hidden />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 w-full dark:text-gray-400 text-slate-600 mt-4 p-2">
                        <button type="submit"
                            class="text-white bg-primary-light-one hover:bg-[#16465f]  font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Submit</button>

                        <button type="button"
                            class="text-gray-900 hover:text-white border border-[#16465f] hover:bg-[#16465f] focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"
                            data-drawer-hide="drawer-right-example"
                            aria-controls="drawer-right-example">Cancel</button>
                    </div>
                </form>

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
                            <input class="ticket_work_id" type="text" name="ticket_work_id" value=""
                                hidden />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 w-full dark:text-gray-400 text-slate-600 mt-4 p-2">
                        <button type="submit"
                            class="text-white bg-primary-light-one hover:bg-[#16465f] font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Submit</button>
                        <button type="button"
                            class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"
                            {{-- data-drawer-hide="drawer-other-pay" 
                            aria-controls="drawer-other-pay" --}}
                            >Cancel</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @endsection

    @section('scripts')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script>


        function getQueryParam(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        var payoutsData = @json($daily_works ?? [])

        $(document).ready(function() {
            const ticketWorkId = getQueryParam('ticket_work');
            if(ticketWorkId) {
                setTimeout(() => {
                    $('button[data-ticket-work-id="'+ticketWorkId+'"]').trigger('click');
                }, 1000);
            }
        })

        $(document).on('change', '.check-all-box', function() {
            let isChecked = $(this).is(':checked'); // Check if .check-all-box is checked
            $('.daily-work-check-box').prop('checked',
                isChecked); // Apply checked state to all .daily-work-check-box
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
        });

        $('#payoutForm').submit(function(e) {
            // Check if any checkbox with class `daily-work-check-box` is checked
            let isChecked = $('.daily-work-check-box:checked').length > 0;

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
                url: `/payout/get-ticket-data/${work_id}`,
                method: 'GET',
                success: function(response) {

                    // response.daily_work_data.eng_charge

                    console.log('response.daily_work_data == ', response.daily_work_data);

                    if (response.status == 'success') {

                        totalOtherExp = 0; // Reset totalExp before recalculating
                        let engineerCharges = response.daily_work_data.eng_charge;
                        let engineerExtraPay = response.extra_pay ;
                        let currencyIcon = '';
                        // $, €, £
                        if (engineerCharges.currency_type == 'dollar') {
                            currencyIcon = '$';
                        } else if (engineerCharges.currency_type == 'pound') {
                            currencyIcon = '£';
                        } else if (engineerCharges.currency_type == 'euro') {
                            currencyIcon = '€';
                        }

                        $('#travel_cost').val(response.daily_work_data.travel_cost);
                        $('#tool_cost').val(response.daily_work_data.tool_cost);
                        
                        $('#overtime').val(response.daily_work_data.overtime_hour);
                        $('.start-date').html(response.daily_work_data.work_start_date)
                        $('.total_hours').html(response.daily_work_data.total_work_time)
                        $('.overtime').html(response.daily_work_data.overtime_hour)

                        $('#ooh-switch').prop('checked', response.daily_work_data.is_out_of_office_hours ? true : false);
                        $('#hw-switch').prop('checked', response.daily_work_data.is_holiday ? true : false);
                        $('#ww-switch').prop('checked', response.daily_work_data.is_weekend ? true : false);
                        $('#ot-switch').prop('checked', response.daily_work_data.is_overtime ? true : false);

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
                            .work_expense; // Dynamic expense list

                        expensesList.innerHTML = ""; // Clear old data

                        workExpenses.forEach((expense, index) => {

                            const expanseValue = Number(expense.expense) || 0
                            totalOtherExp += expanseValue;
                            const documentUrl = expense.document ?
                                `${storageLink}/${expense.document}` : "";

                                console.log('documentUrl = ', documentUrl);
                                
                            const expenseItem = document.createElement("div");
                            expenseItem.className = "flex items-center justify-between bg-white dark:bg-gray-700 p-2 rounded-lg shadow-md border border-gray-300 dark:border-gray-600 space-x-3 ";
                            expenseItem.innerHTML =`
                                <div class="flex items-center space-x-3">
                                    <div> ${index+1}. </div>
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">${expense.name}</span>
                                </div>
                                <div class="flex">` +
                                    ( documentUrl != '' ? 
                                    `<a href="${documentUrl}" target="_blank">
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-2 py-1 rounded-md">View</button>
                                    </a>` : `` ) +
                                    `<div class="bg-blue-100 dark:bg-gray-500 text-blue-600 dark:text-white px-3 py-1 rounded-md text-sm font-semibold ml-2">
                                        ${currencyIcon} ${expense.expense} 
                                    </div>
                                </div>`;
                            expensesList.appendChild(expenseItem);
                        });

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

        $('.otherPayoutBtn').on('click', function() {
            var workId = $(this).data('ticket-work_id');
            var otherPay = $(this).data('other-pay');

            $('.ticket_work_id').val(workId);
            $('#other_pay').val(otherPay);

            // Make the AJAX call
            $.ajax({
                url: `/engineer/daily-work-expense/${workId}`,
                method: 'GET',
                success: function(response) {
                    console.log(response.expanse);
                    let expenseHtml = ''; // Initialize an empty string
                    // let otherPay = 0

                    response.expanse.forEach(element => {
                        expenseHtml += `
                    <li>
                        <strong class="dark:text-gray-300 text-slate-900">Name:</strong> ${element.name} <span class="name"></span>
                    </li>
                    <li>
                        <strong class="dark:text-gray-300 text-slate-900">Expanse:</strong> <span class="expanse"> ${element.expense}$ </span>
                    </li>
                `;
                        // otherPay += parseFloat(element.expense) || 0;
                    });

                    // Update the content of #other-pay-list once after the loop
                    $('#other-pay-list').html(expenseHtml);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                    alert('Error fetching data. Please try again.');
                }
            });
        });

        $('#ooh-switch').on('change', function() {
            if ($(this).is(':checked')) {} else {
                $('#status').text('Checkbox is unchecked.');
            }
        })

        function reloadWithQueryParam(param, value) {
            let url = new URL(window.location.href);
            url.searchParams.set(param, value); // Add or update the query parameter
            window.location.href = url.toString(); // Reload the page with the new URL
        }

        $('.switch input[type="checkbox"]').on('change', function() {

            let status = $(this).prop('checked') ? 1 : 0; // Get switch status (1 for checked, 0 for unchecked)
            let type = $(this).attr('id'); // Get switch ID (ooh-switch, ww-switch, hw-switch)
            let workId = $('#ticket_work_id').val();

            $.ajax({
                url: "/work-status-update", // Laravel route
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    switch_type: type, // Pass switch type (ooh-switch, ww-switch, hw-switch)
                    status: status,
                    workId
                },
                success: function(response) {
                    if (response.success) {
                        alert(type.toUpperCase() + " status updated successfully!");
                        reloadWithQueryParam('ticket_work', workId);
                    } else {
                        alert("Failed to update " + type.toUpperCase() + " status.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("An error occurred while updating the status.");
                }
            });
        });

        function updatePaymentDetails(currency, engineerExtraPay, totalHourlyPayable, totalOtherExp, dailyWorkDetail) {

            let totalSum = 0 ;
            let servicesTotalSum = 0 ;
            const paymentDetails = [
                {
                    name: "Hourly Payable",
                    amount: totalHourlyPayable
                },
                {
                    name: "HW",
                    amount: dailyWorkDetail.is_holiday == 1 ? engineerExtraPay.public_holiday : 0
                },
                {
                    name: "OOH",
                    amount: dailyWorkDetail.is_out_of_office_hours == 1 ? engineerExtraPay.out_of_office_hour : 0
                },
                {
                    name: "WW",
                    amount: dailyWorkDetail.is_weekend == 1 ? engineerExtraPay.weekend : 0
                },
                {
                    name: "OT",
                    amount: dailyWorkDetail.is_overtime == 1  ? engineerExtraPay.overtime : 0
                },
                {
                    name: "Other Expense",
                    amount: totalOtherExp
                }, 
            ];

            const netPayableList = document.getElementById("net-payable-list");
            // const actualAmountList = document.getElementById("actual-amount-list");

            // ✅ Clear previous content to prevent duplicates
            netPayableList.innerHTML = "";
            // actualAmountList.innerHTML = "";

            $('#subtotal').html(`${currency} ${totalHourlyPayable}`);

            // ✅ Append new items
            paymentDetails.forEach((item, index) => {

                totalSum += item.amount ;
                if(index > 0) {
                    servicesTotalSum += item.amount ;
                }

                const payableItem = document.createElement("li");
                payableItem.className = "text-gray-800 dark:text-gray-300 font-medium text-[.9rem]";
                payableItem.textContent = item.name;

                const amountItem = document.createElement("li");
                amountItem.className = "text-gray-900 dark:text-gray-100 font-semibold text-[.9rem]";
                amountItem.textContent = `${currency} ${item.amount}`;

                // netPayableList.appendChild(payableItem);
                // actualAmountList.appendChild(amountItem);

                const row = document.createElement("div");
                row.className = "grid grid-cols-2 px-4 py-2 text-gray-800 dark:text-gray-300";
                row.innerHTML = `<div>${item.name}</div>
                                <div class="text-right font-semibold">${currency} ${item.amount}</div>`;

                netPayableList.appendChild(row);

            });

            $('#daily_total_payable').val(totalSum);
            $('#service-amount').html(`${currency} ${servicesTotalSum}`);
            $('#total-amount').html(`${currency} ${totalSum}`);
            return paymentDetails; // If needed elsewhere
        }

    </script>
    @endsection