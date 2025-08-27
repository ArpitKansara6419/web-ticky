@extends('layouts.app')

@section('title', 'Leads Index')

@section('content')

<div class="">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Leads Of {{$customer['name']}}
            </h3>
            <div class=" gap-2 flex justify-between items-center">
                <div class="  ">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="lead_search" name="search" label=""
                            placeholder="Search by CODE & NAME..." class="" />
                        
                    </div>
                </div>
                
                <div>
                    
                    @can($ModuleEnum::LEAD_CREATE->value)
                    <a href="{{route('lead.create',['customer_id' => $customer['id'] ])}}">
                        <button
                            id="drawerBtn"
                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                            type="button">
                            Add
                        </button>
                    </a>
                    @endcan
                </div>
                
            </div>
            
        </div>

        {{-- card-body  --}}
        <div class="card-body relative ">

            {{-- data-table  --}}
            <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4">
                
                <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                    id="lead-table">

                </table>
            </div>
            @include('backend.customer.popup_customer_detail')
            @include('backend.lead.popup_lead_detail')

            {{-- toast-message component --}}
            @if(session('toast'))
            <x-toast-message
                type="{{ session('toast')['type'] }}"
                message="{{ session('toast')['message'] }}"
                {{--  error="{{ session('toast')['error'] }}" --}} />
            @elseif($errors->any())
            <x-toast-message
                type="danger"
                message="Oops, Something went wrong, Check the form again!" />
            @endif


        </div>

        
        

        

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
                    var searchValue = $('#lead_search').val();
                    d['search[value]'] = searchValue;
                    d['customer_id'] = "{{ $customer->id }}";
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
        $("#lead_search").on('input', function() {
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
</script>
@vite([
    'resources/js/customer/detail.js',
    'resources/js/lead/detail.js',
])
@endsection