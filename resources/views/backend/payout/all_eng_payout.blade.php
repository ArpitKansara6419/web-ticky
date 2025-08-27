@extends('layouts.app')

@section('title', 'Engineer Payout')

@section('content')
<div class="overflow-x-hidden">
    <div class="card  relative">
        <input type="hidden" id="storage_link" name="storage_link" value="<?php echo asset('storage/'); ?>">
        <div class="card-header mt-4 flex justify-between items-center">
            <h4 class="text-2xl font-extrabold flex items-center gap-2">
                <svg class="w-8 h-8   text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                Engineer Payout
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
                            {{-- <label class="text-sm dark:text-white">Select Month</label> --}}
                            <x-input-dropdown name="month" id="month" placeholder="Choose Month" class=""
                                :options="$month" optionalLabel="name" optionalValue="value"
                                value="{{ old('month', $currentMonth) }}" />
                        </div>
                    </div>
                  </div>


                </div>

                <!-- table -->
                <div id="tableContainer" class="mt-2 border  border-gray-200 dark:border-gray-700 overflow-scroll overflow-y-hidden rounded-xl">

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
            fetchTableData();
        });

        function fetchTableData() {

            // var engineer_id = $('#engineer_id').val(); // This will be empty if not selected
            // if (engineer_id === "Select") {
            //     engineer_id = null; // Reset to null if default placeholder value is selected
            // }
            // Ensure necessary data is present
            var year = $('#year').val();
            var month = $('#month').val();

            if (year && month) {
                $.ajax({
                    url: '{{ route("eng-monthly.payout") }}', // Update with the correct route
                    method: 'GET',
                    data: {
                        year: $('#year').val(),
                        month: $('#month').val(),
                        engineer: $('#search').val() || null // Send null if no engineer selected
                    },
                    success: function(response) {
                        // Assuming the response contains the HTML table
                        $('#tableContainer').empty();
                        $('#tableContainer').html(response.html);
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching data. Please try again.');
                    }
                });
            }

        }

        $(document).on('change', '#month', function() {
            fetchTableData();
        });
    </script>
    @endsection