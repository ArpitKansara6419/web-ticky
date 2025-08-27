@extends('layouts.app')

@section('title', 'Customer Payout')

@section('content')
<style>
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

    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
        /* Adjust the height for a slim scrollbar */
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        /* Light gray background */
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        /* Darker thumb for visibility */
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
        /* Slightly darker on hover */
    }
</style>
<div class="overflow-x-hidden">
    <div class="card  relative">
        <input type="hidden" id="storage_link" name="storage_link" value="<?php echo asset('storage/'); ?>">
        <div class="card-header mt-4 flex justify-between items-center">
            <h4 class="text-2xl font-extrabold flex items-center gap-2">
                <svg class="w-8 h-8   text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                Customer Receivable
            </h4>
            <div class="text-center">
            </div>
        </div>
        {{-- card-body  --}}
        <div class="card-body ">


            <div class="bg-white dark:bg-gray-800 dark:border-gray-600 px-4 py-10 border-gray-300  border rounded-xl shadow-xl">

                <!-- filter  -->
                <div class="gap-4 flex justify-between items-center">
                    <div class="w-1/4">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="search" class="block w-full p-[.66rem] ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search" required />
                        </div>
                    </div>
                    <div class="flex flex-row gap-2  justify-end w-full">
                        <div class="w-1/4">
                            <div class="customer">
                                {{-- <label class="text-sm dark:text-white">Select Year</label> --}}
                                <x-input-dropdown name="year" id="year" placeholder="Choose Year" class=""
                                    :options="$year" optionalLabel="name" optionalValue="value"
                                    value="{{ old('year', $currentYear) }}" />
                            </div>
                        </div>
                        <div class="w-1/4">
                            <div class="customer">
                                <x-input-dropdown name="month" id="month" placeholder="Choose Month" class=""
                                    :options="$month" optionalLabel="name" optionalValue="value"
                                    value="{{ old('month', $currentMonth) }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- table -->
                <div class="mt-2 border border-gray-200 dark:border-gray-700 overflow-x-auto rounded-xl custom-scrollbar">
                    <table id="customer-receivable-table" class="w-full border rounded-xl min-w-max">
                        <thead>
                            <tr class="">
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-4">
                                    <span class="flex items-center justify-center text-[.85rem] ">
                                        Sr.
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-4">
                                    <span class="flex items-center justify-start text-[.95rem] ">
                                        Customer Name 
                                    </span>
                                </th>

                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-4">
                                    <span class="flex items-center justify-center text-[.95rem] ">
                                        Ticket(s)
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-4">
                                    <span class="flex items-center justify-center text-[.95rem] ">
                                        Hour(s) | Day(s)
                                    </span>
                                </th>
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-4">
                                    <span class="flex items-center justify-center text-[.95rem] ">
                                        Receivables
                                    </span>
                                </th>
                                <!-- <th class="bg-gray-200 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-4">
                                    <span class="flex items-center justify-center text-[.95rem] ">
                                        Received
                                    </span>
                                </th>
                                <th class="bg-gray-200 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-4">
                                    <span class="flex items-center justify-center text-[.95rem] ">
                                        Not Received
                                    </span>
                                </th> -->
                                <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-4">
                                    <span class="flex items-center justify-center text-[.95rem] ">
                                        Action
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="px-2">

                            @if (!$monthlySummary->isEmpty())
                            @foreach ($monthlySummary as $key => $customerPayout)
                            <tr class="font-bold border-b border-gray-300 p-4">
                                <td class="dark:text-gray-200  text-gray-900 px-auto py-1 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        {{$key + 1}}
                                    </div>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-auto py-1 whitespace-nowrap">
                                    <div
                                        class="font-medium flex items-center justify-start gap-2 p-1 text-gray-900 whitespace-nowrap dark:text-white ">
                                        <img class="w-10 h-10 rounded-full" src="{{ $customerPayout?->engineer?->profile_image ? asset('storage/' . $customerPayout?->engineer?->profile_image ) : asset('user_profiles/user/user.png') }}"
                                            alt="Rounded avatar">
                                        <div class="leading-[1rem]">
                                            <p class="capitalize">
                                                <span class="">
                                                    {{ $customerPayout?->customer?->name }}
                                                </span>
                                            </p>
                                            <p class="text-gray-400">{{ $customerPayout?->customer?->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="dark:text-gray-200 text-gray-900 px-auto py-1 whitespace-nowrap">
                                    <span class="flex items-center justify-center">
                                        {{ $customerPayout?->total_tickets ?? 0  }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center">
                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300"> {{ $customerPayout?->total_work_time ?? '00:00'  }} hrs</span> <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">{{ $customerPayout?->unique_days ?? 0  }} day</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="grid grid-cols-2 gap-1">
                                        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300"> ${{ $customerPayout?->total_dollar ?? '0' }}</span> <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">£{{ $customerPayout?->total_pound ?? '0' }}</span>
                                        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">€{{ $customerPayout?->total_euro ?? '0' }}</span> <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">zł{{ $customerPayout?->total_zloty ?? '0' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="grid grid-cols-2 gap-1">
                                        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300"> ${{ $customerPayout?->paid_dollar ?? '0' }}</span> <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">£{{ $customerPayout?->paid_pound ?? '0' }}</span>
                                        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">€{{ $customerPayout?->paid_euro ?? '0' }}</span> <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">zł{{ $customerPayout?->paid_zloty ?? '0' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="grid grid-cols-2 gap-1">
                                        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300"> ${{ $customerPayout?->total_dollar -  $customerPayout?->paid_dollar  ?? '0' }}</span> <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">£{{ $customerPayout?->total_pound - $customerPayout?->paid_pound ?? '0' }}</span>
                                        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">€{{ $customerPayout?->total_euro - $customerPayout?->paid_euro  ?? '0' }}</span> <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">zł{{ $customerPayout?->total_zloty - $customerPayout?->paid_zloty ?? '0' }}</span>
                                    </div>
                                </td>

                                <td class="px-auto py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('customer-invoice.show', [ $customerPayout?->customer?->id, 'filter' => 'pending' ]) }}">
                                            <button type="button"
                                                title="Receivable details"
                                                class="editBtn bg-blue-100  font-medium rounded-lg text-sm px-[.6rem] py-[.4rem] text-center  flex"
                                                data-drawer-target="drawer-right-example"
                                                data-drawer-show="drawer-right-example"
                                                data-drawer-placement="right"
                                                aria-controls="drawer-right-example">
                                                <svg version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    class="w-5 h-5 text-blue-500" viewBox="0 0 32 32" xml:space="preserve">
                                                    <path class="puchipuchi_een" stroke="currentColor" d="M29,2H3C1.9,2,1,2.9,1,4v19c0,1.1,0.9,2,2,2h26c1.1,0,2-0.9,2-2V4C31,2.9,30.1,2,29,2z M29,20
                                                                c0,0.55-0.45,1-1,1H4c-0.55,0-1-0.45-1-1V5c0-0.55,0.45-1,1-1h24c0.55,0,1,0.45,1,1V20z M22,29c0,0.552-0.447,1-1,1H11
                                                                c-0.553,0-1-0.448-1-1s0.447-1,1-1h1v-2h8v2h1C21.553,28,22,28.448,22,29z M22,14c0,0.552-0.447,1-1,1H11c-0.553,0-1-0.448-1-1
                                                                s0.447-1,1-1h10C21.553,13,22,13.448,22,14z M22,10c0,0.552-0.447,1-1,1H11c-0.553,0-1-0.448-1-1s0.447-1,1-1h10
                                                                C21.553,9,22,9.448,22,10z" />
                                                </svg>
                                            </button>
                                        </a>
                                        <a href="{{ route('customer-invoice.index', [ $customerPayout?->customer?->id, 'filter' => 'all']) }}">
                                            <button type="button"
                                                title="invoice"
                                                class="editBtn bg-fuchsia-100  font-medium rounded-lg text-sm px-[.6rem] py-[.4rem] text-center  flex"
                                                data-drawer-target="drawer-right-example"
                                                data-drawer-show="drawer-right-example"
                                                data-drawer-placement="right"
                                                aria-controls="drawer-right-example">
                                                <svg viewBox="0 0 1024 1024" class="w-5 h-5 icon text-fuchsia-500" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke="currentColor" d="M731.15 585.97c-100.99 0-182.86 81.87-182.86 182.86s81.87 182.86 182.86 182.86 182.86-81.87 182.86-182.86-81.87-182.86-182.86-182.86z m0 292.57c-60.5 0-109.71-49.22-109.71-109.71s49.22-109.71 109.71-109.71c60.5 0 109.71 49.22 109.71 109.71s-49.21 109.71-109.71 109.71z" />
                                                    <path stroke="currentColor" d="M718.01 778.55l-42.56-38.12-36.6 40.86 84.02 75.26 102.98-118.46-41.4-36zM219.51 474.96h219.43v73.14H219.51z" />
                                                    <path stroke="currentColor" d="M182.61 365.86h585.62v179.48h73.14V145.21c0-39.96-32.5-72.48-72.46-72.48h-27.36c-29.18 0-55.04 16.73-65.88 42.59-5.71 13.64-27.82 13.66-33.57-0.02-10.86-25.86-36.71-42.57-65.88-42.57h-18.16c-29.18 0-55.04 16.73-65.88 42.59-5.71 13.64-27.82 13.66-33.57-0.02-10.86-25.86-36.71-42.57-65.88-42.57H375.3c-29.18 0-55.04 16.73-65.88 42.59-5.71 13.64-27.82 13.66-33.57-0.02-10.86-25.86-36.71-42.57-65.88-42.57H182.4c-39.96 0-72.48 32.52-72.48 72.48v805.14h401.21v-73.14H183.04l-0.43-511.35z m25.81-222.29c14.25 34.09 47.32 56.11 84.23 56.11 36.89 0 69.96-22.02 82.66-53.8l15.86-2.3c14.25 34.09 47.32 56.11 84.23 56.11 36.89 0 69.96-22.02 82.66-53.8l16.59-2.3c14.25 34.09 47.32 56.11 84.23 56.11 36.89 0 69.96-22.02 82.66-53.8l26.68-0.66v147.5H182.54l-0.13-146.84 26.01-2.33z" />
                                                </svg>
                                                <span class="sr-only">Icon description</span>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="font-bold border-b border-gray-300 p-4">
                                <td colspan="14">
                                    <p class="text-center p-3 text-md"> No record found. </p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
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

        function reloadWithQueryParam(param, value) {
            let url = new URL(window.location.href);
            url.searchParams.set(param, value); // Add or update the query parameter
            window.location.href = url.toString(); // Reload the page with the new URL
        }

        $(document).ready(function() {
            ///
            getCustomerPayable();
        });

        $('#search').on('input', function() {
            getCustomerPayable();
        });

        $('#year').on('change', function() {
            getCustomerPayable();
        });

        $('#month').on('change', function() {
            getCustomerPayable();
        });

        function getCustomerPayable() {

            var searchText = $('#search').val();
            var year = $('#year').val();
            var month = $('#month').val();


            $.ajax({
                url: `/all-customer-payout-filter-data`,
                method: 'POST',
                data: {
                    // search: searchText  ,
                    year: year,
                    month: month,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#customer-receivable-table tbody').empty();
                    $('#customer-receivable-table tbody').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                    alert('Error fetching data. Please try again.');
                }
            });

        }
    </script>

    @endsection