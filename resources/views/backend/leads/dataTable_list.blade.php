@extends('layouts.app')

@section('title', 'Leads')

@section('styles')
<!-- <style>
    #customer-table_processing {
        display: none !important;
    }
</style> -->
@endsection

@section('content')
<div class="">
    <div class="card w-full overflow-hidden">
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Leads
            </h3>
            <div class=" gap-2 flex justify-between items-center">
                <div class="  ">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="search" name="search" label=""
                            placeholder="Search by CODE & NAME..." class="" />
                        
                    </div>
                </div>
                
                <div>
                    
                    @can($ModuleEnum::LEAD_CREATE->value)
                    <a href="{{ route('lead.create') }}">
                        <button id="drawerBtn"
                            class="text-white bg-primary-light-one bg-blue-800 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                            type="button">
                            Generate Lead
                        </button>
                    </a>
                    @endcan
                    <button
                        class="hidden"
                        data-modal-target="static-modal"
                        data-modal-toggle="static-modal">

                    </button>
                    <button
                        class="hidden"
                        data-modal-target="change-status-modal"
                        data-modal-toggle="change-status-modal">

                    </button>
                </div>
                
            </div>
            
        </div>
        
        {{-- card-body  --}}
        <div class="card-body relative ">

            {{-- data-table  --}}
            <div class="border  border-1 overflow-x-auto border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                    id="lead-table">

                </table>
            </div>



            <div id="change-status-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Change Status
                            </h3>
                            <button type="button"
                                class="close-status text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="change-status-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form id="lead-status-form" action="{{ route('lead-status.update') }}" method="POST"
                            class="">
                            @csrf
                            <div class="grid gap-3 p-4 grid-cols-2">
                                <input type="hidden" name="lead_id" id="lead_id" value=""
                                    required="" />
                                @php
                                $status = [
                                ['name' => 'Bid', 'value' => 'bid'],
                                ['name' => 'Confirm', 'value' => 'confirm'],
                                ['name' => 'Reschedule', 'value' => 'reschedule'],
                                ['name' => 'Cancelled', 'value' => 'cancelled'],
                                ];
                                @endphp

                                <div class="customer">
                                    <label class="text-sm dark:text-white">Status</label>
                                    <x-input-dropdown name="lead_status" id="lead_status" placeholder="Lead Status"
                                        class="" :options="$status" optionalLabel="name" optionalValue="value"
                                        value="" />
                                </div>

                                <div class="reschedule_date_container hidden">
                                    <label class="text-sm dark:text-white">Reschedule Date</label>
                                    <input name="reschedule_date" id="reschedule_date" placeholder="Reschedule Date"
                                        class="border dark:border-[#5A6270] border-gray-300 rounded-lg mt-2 dark:text-white dark:bg-[#374151]"
                                        value="" type="date" min="<?php echo date('Y-m-d'); ?>" />
                                </div>

                                <hr class="md:col-span-2 dark:opacity-20" />

                                <div class="md:col-span-2 mt-2">
                                    <button type="submit" id="btn-update-status"
                                        class="text-white w-full inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Update
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main modal -->
            
            @include('backend.lead.popup_lead_detail')
        </div>

        @include('backend.customer.popup_customer_detail')



    </div>


</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        var datatable_id = "lead-table";
        var list_datatable = $("#" + datatable_id).DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pagingType: "full_numbers",
            ajax: {
                url: "{{ route('lead.dataTable') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: function(d) {
                    var searchValue = $('#search').val();
                    d['search[value]'] = searchValue;
                }
            },
            columns: [{
                    title: "LEAD",
                    data: "lead_div",
                    name: "lead_div",
                    width: "20%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                {
                    title: "CUSTOMER",
                    data: "customer_div",
                    name: "customer_div",
                    width: "20%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                {
                    title: "DATE & TIME",
                    data: "date_time_div",
                    name: "date_time_div",
                    width: "20%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
               
                
                
                
                {
                    title: "Status",
                    data: "lead_status",
                    name: "lead_status",
                    width: "20%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                {
                    title: "Ticket",
                    data: "ticket_div",
                    name: "ticket_div",
                    width: "20%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                {
                    title: "",
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    width: "3%",
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("action-cell text-end d-flex justify-content-end m-3");
                    },
                },
            ],
            headerCallback: function(thead, data, start, end, display) {
                setTimeout(() => {
                    $(thead).find("th").first().removeClass("dt-ordering-asc");
                    $(thead).find("th").last().addClass("d-flex justify-content-end");

                    $(thead).find("th").addClass('bg-blue-100  dark:bg-gray-900 px-6 py-3');

                    $(thead).addClass(
                        'text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400'
                    )
                }, 10);
            },
            createdRow: function(row, data, dataIndex) {
                $('.dt-layout-table').next().addClass('pagination-show-entries');
                $(row).addClass(
                    "odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200"
                );
            },

            drawCallback: function(settings) {
                if (typeof initializeDropdowns === 'function') {
                    initializeDropdowns();
                } else {
                    console.warn('initializeDropdowns is not defined');
                }
            }
        });
        $("#search").on('input', function() {
            list_datatable.ajax.reload();
        });
        $(document).on('click', '.del-button', function() {
            if (confirm("Are you sure?")) {
                var lead_id = $(this).data('lead_id');
                $.ajax({
                    url: '{{ route("lead.destroy", "") }}/' + lead_id,
                    type: 'DELETE',
                    dataType: 'json',
                    data: typeof post_parameter !== "undefined" ? post_parameter : {},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        showSuccessToast(response.message);
                        var currentPage = $("#" + datatable_id).DataTable().page.info().page;
                        list_datatable.page(currentPage).draw(false);
                    }
                });
            }
        });
    });
    setTimeout(function() {
        const $targetElChangeStatus = document.getElementById('change-status-modal');
        const $changeStatusModal = new Modal($targetElChangeStatus, {}, {
            id: 'change-status-modal',
            override: true
        });
        $(document).on('click', '.lead-status-change-btn', function() {
            $changeStatusModal.toggle();
            let lead_id = $(this).data('lead-id');
            let lead_status = $(this).data('lead-status');
            let reschedule_date = $(this).data('reschedule-date'); // Add this if you are passing the reschedule date.

            $('#lead_id').val(lead_id);
            $('#lead_status').val(lead_status);

            // Check if the lead status is "reschedule"
            if (lead_status === 'reschedule') {
                $('.reschedule_date_container').removeClass('hidden');
                $('#reschedule_date').val(reschedule_date || ''); // Pre-fill the date if available.
                $('#reschedule_date').prop('required', true);
            } else {
                $('.reschedule_date_container').addClass('hidden');
                $('#reschedule_date').val('');
                $('#reschedule_date').prop('required', false);
            }
        });

        
        $(document).on('click',".close-status", function(){
            $changeStatusModal.toggle();
        });
    }, 500)
</script>
@vite([
    'resources/js/customer/detail.js',
    'resources/js/lead/detail.js',
])
@endsection