@extends('layouts.app')

@section('title', 'Customers')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.tailwindcss.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    #customer-table_processing {
        display: none !important;
    }
</style>
@endsection

@section('content')

<div class="grid">

    <div class="card">

        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Customers
            </h3>
            <div class=" gap-2 flex justify-between items-center">
                <div class="  ">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="search" name="search" label=""
                            placeholder="Search by CODE & NAME..." class="" />
                        
                    </div>
                </div>
                @can($ModuleEnum::CUSTOMER_CREATE->value)
                <div>
                    <a class="mt-2 mb-3" href="{{ route('customer.create') }}">
                        <button id="drawerBtn"
                            class="text-white bg-primary bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                            type="button">
                            Add Customer
                        </button>
                    </a>
                </div>
                @endcan
            </div>
            
        </div>

        <div class="mt-4 relative">
            <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4  overflow-x-auto"
                style="">
                <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                    id="customer-table">


                </table>
            </div>

            @if (session('toast'))
            <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}" />
            @endif

        </div>
    </div>


    <!-- Main modal -->
    @include('backend.customer.popup_customer_detail')

</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        var datatable_id = "customer-table";
        var list_datatable = $("#" + datatable_id).DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pagingType: "full_numbers",
            ajax: {
                url: "{{ route('customer.dataTable') }}",
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
                    title: "NAME",
                    data: "customer_div",
                    name: "customer_div",
                    width: "45%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                {
                    title: "CUSTOMER TYPE",
                    data: "customer_type_div",
                    name: "customer_type_div",
                    width: "20%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                {
                    title: "Authorized Person",
                    data: "authorized_person_count",
                    name: "authorized_person_count",
                    width: "10%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                {
                    title: "Documents",
                    data: "document_count",
                    name: "document_count",
                    width: "10%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                {
                    title: "",
                    data: "lead_create",
                    name: "lead_create",
                    width: "29%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    }
                },
                {
                    title: "",
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    width: "6%",
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

            drawCallback: async function(settings) {
                if (typeof initializeDropdowns === 'function') {
                    await initializeDropdowns();
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
                var customer_id = $(this).data('customer_id');
                $.ajax({
                    url: '{{ route("customer.destroy", "") }}/' + customer_id,
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
</script>
@vite([
    'resources/js/customer/detail.js',
])
@endsection