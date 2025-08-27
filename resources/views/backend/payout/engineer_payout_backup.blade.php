@extends('layouts.app')

@section('title', 'Engineer Payout')

@section('content')

    <div class="">
        <div class="card">

            <div class="card-header flex justify-between items-center">
                <h3 class="font-extrabold">
                    Engineer Payout
                </h3>
                <div class="text-center">
                    <!-- <a href="{{ route('lead.create') }}">
                        <button
                            id="drawerBtn"
                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                            type="button"
                        >
                            Add
                        </button>
                    </a> -->
                </div>
            </div>
            {{--  card-body  --}}
            <div class="card-body">

                <div class="grid md:grid-cols-6 gap-4">
                    <div class="md:col-span-4 p-6 flex flex-row justify-between rounded-lg bg-gray-50 dark:bg-gray-800 dark:border dark:border-gray-700">

                        <!-- Profile Info -->
                        <div class="flex items-center gap-4 p-1">
                            <img class="w-24 h-24 border rounded-full" src="/user_profiles/user/user.png"
                                alt="User profile picture">
                            <div class="text-base">
                                <p class="capitalize text-2xl font-medium text-gray-900 dark:text-white leading-7">
                                    John Doe</p>
                                <p class="text-gray-500">john.doe@gmail.com</p>
                            </div>
                        </div>

                        <!-- Profile Details -->
                        <div class="grid md:grid-cols-2 text-sm mt-4 text-gray-600 dark:text-gray-400">
                            <p>Contact:7201002217</p>
                            <p>Contact:7201002217</p>
                            <p>Gender: Male</p>
                            <p>Engineer Status:
                                <x-badge type="success" label="Active" />
                            </p>
                        </div>
                    </div>

                    <!-- Payout -->
                    <div class="md:col-span-2 border dark:border-gray-800 dark:bg-gray-700 rounded-lg p-4">

                        <div class="flex justify-between">
                                <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 dark:text-white">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z" />
                                    </svg>
        
                                    Payout
                                </p>
                                <p class="capitalize  gap-2 text-lg font-bold mt-2 text-gray-600 dark:text-white">
                                    <x-badge type="danger" label="Pending" />
                                </p>
                        </div>



                        {{-- @if (isset($engineer['enggLang'])) --}}
                        <div class="mt-6">
                            {{-- @foreach ($engineer['enggLang'] as $lang) --}}
                            <div
                                class="text-sm mt-2 text-gray-600 dark:text-gray-300 border-gray-600 border-opacity-20 dark:border-opacity-80 border border-1 rounded-lg p-4">

                                <div class="flex justify-between items-center uppercase mb-2 font-bold">
                                    <p class="font-bold">Total Payout</p>
                                    <p class="text-green-400">2500$</p>
                                </div>
                            </div>
                            {{-- @endforeach --}}
                        </div>
                        {{-- @else
                        <p class="text-center text-sm  dark:text-gray-400">No Language Found</p>
                    @endif --}}
                    </div>
                </div>

                <div class="mt-5 border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4 ">
                    <table id="search-table" class="w-full border border-gray-200" style="border-radius: 12px">
                        <thead>
                            <tr>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2"></th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    <span class="flex items-center">
                                        Sr.
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    <span class="flex items-center">
                                        Ticket 
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    <span class="flex items-center">
                                        Job Type
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    <span class="flex items-center">
                                        Payouts 
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    <span class="flex items-center">
                                        Hours 
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    <span class="flex items-center">
                                        Days 
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    <span class="flex items-center">
                                        Payout Status
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    <span class="flex items-center">
                                        Action
                                    </span>
                                </th>
                            </tr>
                        </thead>
                       <tbody class="p-2">
                        <tr class="bg-green-400 font-bold">
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                1
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                TCK00292
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                Full Time
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                2500$
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                190 HR
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                4
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                <p> <x-badge type="warning" label="Pending" /></p>
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td ></td>
                            <td colspan="8"> 
                                <table class="w-full border  border-gray-600">
                                    <thead>
                                        <tr> 
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Sr. 
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Date
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Hours
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Break
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Overtime
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Weekend Work
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Out Of Office Hours
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Holiday Work
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Total Pay
                                                </span>
                                            </th>

                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Action
                                                </span>
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                12-11-2024
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                50 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                145$
                                            </td>
                                            <td>
                                            <a href="">
                                                <button type="button" title="Lead" data-customer-id=""
                                                    class="editBtn  text-white bg-gradient-to-r from-blue-400 via-blue-400 to-blue-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-blue-600 shadow dark:shadow dark:shadow-blue-700/80 font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                                    </svg>

                                                    <span class="sr-only">Icon description</span>
                                                    {{--  Edit  --}}
                                                </button>
                                            </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                2
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                12-11-2024
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                50 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                145$
                                            </td>
                                            <td>
                                            <a href="">
                                                <button type="button" title="Lead" data-customer-id=""
                                                    class="editBtn  text-white bg-gradient-to-r from-blue-400 via-blue-400 to-blue-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-blue-600 shadow dark:shadow dark:shadow-blue-700/80 font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                                    </svg>

                                                    <span class="sr-only">Icon description</span>
                                                    {{--  Edit  --}}
                                                </button>
                                            </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="bg-green-400 font-bold">
                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                2
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                TCK00295
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                Full Time
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                2500$
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                190 HR
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                4
                            </td>
                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                <p> <x-badge type="warning" label="Pending" /></p>
                            </td>
                            <td class="dark:text-black-200 text-gray-900 p-2">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="8"> 
                                <table class="w-full border  border-gray-600">
                                    <thead>
                                        <tr> 
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Sr. 
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Date
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Hours
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Break
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Overtime
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Weekend Work
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Out Of Office Hours
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Holiday Work
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Total Pay
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 p-2  dark:text-gray-200 text-gray-900 dark:bg-gray-900" >
                                                <span class="flex items-center">
                                                    Action
                                                </span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                12-11-2024
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                50 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                145$
                                            </td>

                                            <td>
                                            <a href="">
                                                <button type="button" title="Lead" data-customer-id=""
                                                    class="editBtn  text-white bg-gradient-to-r from-blue-400 via-blue-400 to-blue-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-blue-600 shadow dark:shadow dark:shadow-blue-700/80 font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                                    </svg>

                                                    <span class="sr-only">Icon description</span>
                                                    {{--  Edit  --}}
                                                </button>
                                            </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                2
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                12-11-2024
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                50 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                1 HR
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                No
                                            </td>
                                            <td class="dark:text-gray-200 text-gray-900 p-2">
                                                145$
                                            </td>
                                            <td>
                                            <a href="">
                                                <button type="button" title="Lead" data-customer-id=""
                                                    class="editBtn  text-white bg-gradient-to-r from-blue-400 via-blue-400 to-blue-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-blue-600 shadow dark:shadow dark:shadow-blue-700/80 font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                                    </svg>

                                                    <span class="sr-only">Icon description</span>
                                                    {{--  Edit  --}}
                                                </button>
                                            </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                       </tbody>
                       <tfoot>
                            <tr>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    
                                </th>
                            </tr>
                       </tfoot>
                    </table>
                </div>

                <div class="mt-5 border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4 ">
                    <table class="w-full border border-gray-200">
                        <thead>
                            <tr>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    Work Pay
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    OverTime
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    Holiday
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    Out Of Office Hours
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    Weekend
                                </th>
                                <th class="bg-blue-100  dark:text-gray-200 text-gray-900 dark:bg-gray-900 p-2">
                                    Gross Pay
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="dark:text-black-200 text-gray-900 p-2">
                                    <x-input-number id="hourly_rate" name='hourly_rate' label=""
                                        placeholder="Work Pay" class=""
                                        value=""
                                    />
                                </td>
                                <td class="dark:text-black-200 text-gray-900 p-2">
                                    <x-input-number id="hourly_rate" name='hourly_rate' label=""
                                        placeholder="Overtime" class=""
                                        value=""
                                    />
                                </td>
                                <td class="dark:text-black-200 text-gray-900 p-2">
                                    <x-input-number id="hourly_rate" name='hourly_rate' label=""
                                        placeholder="Holiday" class=""
                                        value=""
                                    />
                                </td>
                                <td class="dark:text-black-200 text-gray-900 p-2">
                                    <x-input-number id="hourly_rate" name='hourly_rate' label=""
                                        placeholder="Out Of Office Hours" class=""
                                        value=""
                                    />
                                </td>
                                <td class="dark:text-black-200 text-gray-900 p-2">
                                    <x-input-number id="hourly_rate" name='hourly_rate' label=""
                                        placeholder="Weekend" class=""
                                        value=""
                                    />
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 p-2 text-center">
                                    <span class="flex items-center text-center">
                                        2500$
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="p-3">
                                    <x-text-area id="scope_of_work" name='scope_of_work' label="Important Note"
                                    placeholder="Important Note" class=""
                                    value=""  />
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                            <button type="submit" class="text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Pay
                            </button>
                        </div>
                    </div>    
                </div>

            </div>
        </div>


    @endsection
