@extends('layouts.app')

@section('title', 'Engineers')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.tailwindcss.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        #engineer-table_processing{
            display: none !important;
        }
    </style>
@endsection

@section('content')

    <div class="grid">

        <div class="card">

            <div class="card-header flex justify-between items-center">
                <h3 class="font-extrabold">
                    Engineers
                </h3>
                <div class="w-1/3">
                    <div class="gap-2 ">
                        <div class="mt-2 mb-3">
                            <x-text-field id="search" name="search" label=""
                                placeholder="Search by CODE & NAME..." class=""/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 relative">
                <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4  overflow-x-auto"
                    style="">
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="engineer-table">


                    </table>
                </div>

                @if (session('toast'))
                    <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}" />
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
            var datatable_id = "engineer-table";
            var list_datatable = $("#" + datatable_id).DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('engineer.dataTable') }}",
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
                columns: [
                    {
                        title: "NAME",
                        data: "engineer_div",
                        name: "engineer_div",
                        width: "37%",
                        // width: "30%",
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass("px-6 py-3 ");
                        },
                    },
                    {
                        title: "Contact",
                        data: "contact",
                        name: "contact",
                        width: "10%",
                        // width: "17%",
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass("px-6 py-3 ");
                        },
                    },
                    {
                        title: "Location",
                        data: "location_div",
                        name: "location_div",
                        width: "10%",
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass("px-6 py-3 ");
                        },
                    },
                    {
                        title: "Timezone",
                        data: "timezone_div",
                        name: "timezone_div",
                        width: "13%",
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass("px-6 py-3 ");
                        },
                    },
                    {
                        title: "Job Type",
                        data: "job_type",
                        name: "job_type",
                        width: "15%",
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass("px-6 py-3 ");
                        },
                    },
                    {
                        title: "Job Title",
                        data: "job_title",
                        name: "job_title",
                        width: "12%",
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
            $("#search").on('input', function(){
                list_datatable.ajax.reload();
            });      
            $(document).on('click', '.del-button', function() {
                if (confirm("Are you sure?")) {
                    var engineer_id = $(this).data('engineer_id');
                    $.ajax({
                        url: '{{ route("engg.destroy", "") }}/' + engineer_id,
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
@endsection
