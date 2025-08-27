@extends('layouts.app')

@section('title', 'Roles')

@section('styles')
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" /> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.tailwindcss.css" />

@endsection

@section('content')

    <div class="grid">

        <div class="card">

            <div class="card-header flex justify-between items-center">
                <h3 class="font-extrabold">
                    Roles
                </h3>
                <div class=" gap-2 flex justify-between items-center">
                    <div class="  ">
                        <div class="mt-2 mb-3 w-100">
                            <x-text-field id="search" name="search" label="" placeholder="Search..."
                                class="" />

                        </div>
                    </div>
                    @can($ModuleEnum::SETTING_ROLE_CREATE->value)
                        <div>
                            @can($ModuleEnum::SETTING_ROLE_CREATE->value)
                                <button data-modal-target="add-role-model" data-modal-toggle="add-role-model" id="drawerBtn"
                                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                                    type="button">
                                    Add Roles
                                </button>
                            @endcan
                            <button class="hidden" data-modal-target="edit-role-model" data-modal-toggle="edit-role-model">

                            </button>
                        </div>
                    @endcan
                </div>

            </div>


            {{-- card-body  --}}
            <div class="mt-4 relative">

                {{-- data-table  --}}
                <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4 " style="">
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="search-table">
                        <!--  w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 --->


                    </table>
                </div>



            </div>




            <div id="add-role-model" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Create New Role
                            </h3>
                            <button type="button"
                                class="add_close text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="add-role-model">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form id="form_store" action="{{ route('role_store') }}" method="POST" class="">
                            @csrf
                            <div class="grid gap-3 p-4 grid-cols-1">
                                <div class="mt-4">
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        required autocomplete="new-password" />
                                </div>
                                <hr class="md:col-span-2 dark:opacity-20" />
                                <div class="md:col-span-2 mt-2">
                                    <button type="submit" id=""
                                        class="text-white w-full inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Save
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="edit-role-model" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Update Role
                            </h3>
                            <button type="button"
                                class="edit_close text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="edit-role-model">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form id="form_update" action="#" method="PUT" class="">
                            @csrf
                            <input type="hidden" name="role_id" id="role_id">
                            <div class="grid gap-3 p-4 grid-cols-1">
                                <div class="mt-4">
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="edit_name" class="block mt-1 w-full" type="text" name="name"
                                        required autocomplete="new-password" />
                                </div>
                                <hr class="md:col-span-2 dark:opacity-20" />
                                <div class="md:col-span-2 mt-2">
                                    <button type="submit" id="btn-update-role"
                                        class="text-white w-full inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Update
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('scripts')


    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest(App\Http\Requests\RoleStoreRequest::class, '#form_store') !!}
    {!! JsValidator::formRequest(App\Http\Requests\RoleUpdateRequest::class, '#form_update') !!}
    <!-- <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            var datatable_id = "search-table";
            var list_datatable = $("#" + datatable_id).DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('role_ajax_data_table') }}",
                    type: 'GET',
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
                        title: "#",
                        data: null,
                        width: "2%",
                        orderable: false,
                        render: function(data, type, row, meta) {
                            var pageInfo = list_datatable.page.info();
                            return pageInfo.start + meta.row + 1; // Start index + row index
                        },
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass("px-6 py-3 ");
                        },
                    },
                    {
                        title: "Name",
                        data: "name",
                        name: "name",
                        width: "80%",
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
                        width: "18%",
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

            const $targetEledit = document.getElementById('edit-role-model');
            const modalEditRef = new Modal($targetEledit, {}, {
                id: 'edit-role-model',
                override: true
            });

            const $targetEl = document.getElementById('add-role-model');
            const modelAddRole = new Modal($targetEl, {}, {
                id: 'add-role-model',
                override: true
            });


            $(document).on('click', '#drawerBtn', function() {
                modelAddRole.toggle();
                $("#form_store").trigger("reset");
                $("#form_store").find(".invalid-feedback").remove();

            });

            $(document).on('click', '.add_close', function() {
                modelAddRole.toggle();
            });
            $(document).on('click', '.edit_close', function() {
                modalEditRef.toggle();
            });

            $(document).on('click', '.editBtn', function() {
                modalEditRef.toggle();
                var role_id = $(this).data('role_id');
                $.ajax({
                    url: '{{ route('role_edit', '') }}/' + role_id,
                    type: 'GET',
                    dataType: 'json',
                    data: typeof post_parameter !== "undefined" ? post_parameter : {},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        var response = response.data;
                        $("#edit_name").val(response.name);
                        $("#role_id").val(response.id);

                        $("#form_update").attr("action", "{{ route('role_update', '') }}/" +
                            role_id);
                    }
                });
            });

            $(document).on('click', '.del-button', function() {
                if (confirm("Are you sure?")) {
                    var role_id = $(this).data('role_id');
                    $.ajax({
                        url: '{{ route('role_remove', '') }}/' + role_id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: typeof post_parameter !== "undefined" ? post_parameter : {},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {

                            showSuccessToast(response.message);
                            var currentPage = $("#" + datatable_id).DataTable().page.info()
                            .page;
                            list_datatable.page(currentPage).draw(false);
                        }
                    });
                }
            });



            $("#form_update").on("submit", function(e) {
                e.preventDefault();

                var formData = {};
                $(this)
                    .serializeArray()
                    .map(function(field) {
                        formData[field.name] = field.value;
                    });

                $.ajax({
                    url: $(this).attr("action"), // Update with your actual endpoint
                    method: "PUT",
                    dataType: "json",
                    // context: this,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: formData,
                    success: function(response) {
                        list_datatable.ajax.reload();

                        modalEditRef.hide();
                        showSuccessToast(response.message);
                    },
                    error: function() {
                        console.error("Failed to fetch attendance data");
                    }
                });

            });

            $("#form_store").on("submit", function(e) {
                e.preventDefault();

                var formData = {};
                $(this)
                    .serializeArray()
                    .map(function(field) {
                        formData[field.name] = field.value;
                    });
                // return
                $.ajax({
                    url: $(this).attr("action"), // Update with your actual endpoint
                    method: "POST",
                    dataType: "json",
                    context: this,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: formData,
                    success: function(response) {

                        list_datatable.ajax.reload();
                        showSuccessToast(response.message);
                        $(this).trigger("reset");
                        modelAddRole.toggle();


                    },
                    error: function() {
                        console.error("Failed to fetch attendance data");
                    }
                });

            });
        });
    </script>
@endsection
