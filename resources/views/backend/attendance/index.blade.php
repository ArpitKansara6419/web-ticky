@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="grid">
    <div class="card">

        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold  flex gap-2 items-center">
                <svg class="w-8 h-8 text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                Attendance
            </h3>
        </div>

        <div class="card-body relative pt-2">
            <!-- Table container where the results will be rendered -->
            <div class="bg-white dark:bg-gray-800 dark:border-gray-600 px-4 py-10 border-gray-300  border rounded-xl shadow-xl">

                <div class="gap-4 flex items-center">
                    <div class="">
                        <span>Timesheet</span>
                    </div>
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
                    <!-- <div>
                        <svg class="w-7 h-7 text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6h-2m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4" />
                        </svg>

                    </div> -->
                </div>

                {{-- <button 
                        data-modal-target="default-modal" 
                        data-modal-toggle="default-modal" 
                        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" 
                        type="button">
                    Toggle modal
                </button> --}}

                <div id="tableContainer" class="mt-4">
                    <!-- The table will be injected here after AJAX call -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main modal -->
<div id="default-modal"  tabindex="-1" aria-hidden="true" class="hidden  overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <!-- Modal content -->
    <div id="popupContainer" class="">
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        const instanceOptions = {
            id: 'default-modal',
            override: true
        };

        var field_name_direction = "";
        var filter_direction = "";

        const $targetEl = document.getElementById('default-modal');
        const modal = new Modal($targetEl, {}, instanceOptions);

        // Default AJAX call to render the table on page load
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
                    url: '{{ route("attendance.fetchTable") }}', // Update with the correct route
                    method: 'GET',
                    data: {
                        year: $('#year').val(),
                        month: $('#month').val(),
                        engineer: $('#search').val() || null, // Send null if no engineer selected
                        field_name_direction : field_name_direction,
                        filter_direction : filter_direction
                    },
                    success: function(response) {
                        // Assuming the response contains the HTML table
                        $('#tableContainer').html(response);
                        changeFunction();
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching data. Please try again.');
                    }
                });
            }
        }
        // Call the function to fetch and render the table when the page loads
        fetchTableData();
        // Attach the function to the Search button for manual triggering
        $('#searchBtn').on('click', function() {
            fetchTableData();
        });

        function changeFunction()
        {
            $(".change_direction").on('click', function(){
                $(this).addClass('text-blue-600')
                field_name_direction = $(this).data('dir_field');
                filter_direction = $(this).data('dir');
                fetchTableData();
            });
        }

        $('#year').change(function() {
            fetchTableData();
        });

        $('#month').change(function() {
            fetchTableData();
        });

        $('#search').keyup(function() {
            console.log('====================================');
            console.log('search-input', $(this).val());
            console.log('====================================');
            fetchTableData();
        });

        $(document).on('click', '.open-detail-model', function() {
            var engineerId = $(this).data('engineer-id');
            $.ajax({
                url: '{{ route("attendance.fetchPopup") }}',
                method: 'GET',
                data: {
                    year: $('#year').val(),
                    month: $('#month').val(),
                    engineer_id: engineerId
                }, 
                success: function(response) {
                    // Assuming the response contains the HTML table
                    $('#popupContainer').html(response);
                },
                error: function(xhr, status, error) {
                    alert('Error fetching popup. Please try again.');
                }
            })
            modal.toggle();
        });
    });
</script>


@endsection