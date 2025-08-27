@extends('layouts.app')

@section('title', 'System Users')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.tailwindcss.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
    <style>
        #user-table_processing {
            display: none !important;
        }
    </style>
@endsection

@section('content')

    <div class="grid">

        <div class="card">

            <div class="card-header flex justify-between items-center">
                <h3 class="font-extrabold">
                    System Users
                </h3>
                <div class=" gap-2 flex justify-between items-center">
                    <div class="  ">
                        <div class="mt-2 mb-3 w-100">
                            <x-text-field id="search" name="search" label="" placeholder="Search..."
                                class="" />

                        </div>
                    </div>
                    @can($ModuleEnum::SETTING_SYSTEM_USERS_CREATE->value)
                        <div>
                            <button id="drawerBtn"
                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 add_user_modal"
                                type="button">
                                Add System User
                            </button>

                            <button class="hidden" data-modal-target="edit-user-model" data-modal-toggle="edit-user-model">

                            </button>
                            <button class="hidden" data-modal-target="add-user-model" data-modal-toggle="add-user-model">

                            </button>
                        </div>
                    @endcan
                </div>

            </div>
            

            <div class="mt-4 relative">
                <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4 " style="">
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="user-table">

                    </table>
                </div>
            </div>
            <div id="add-user-model" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Create New User
                            </h3>
                            <button type="button"
                                class="add_close text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="add-user-model">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form id="form_store" action="{{ route('user_store') }}" method="POST" class="">
                            @csrf
                            <div class="gap-3 p-4">
                                <div class="mt-4">
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        required autocomplete="new-password" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input class="block mt-1 w-full" type="text" name="email" required
                                        autocomplete="odl-password" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="Password" :value="__('Password')" />
                                    <x-text-input class="block mt-1 w-full" type="password" name="password" required
                                        autocomplete="old-password" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="confirm_password" :value="__('Confirm Password')" />
                                    <x-text-input class="block mt-1 w-full" type="password" name="confirm_password" required
                                        autocomplete="ol1-password" />
                                </div>
                                <div class="mt-4">
                                    <x-RoleDropdown name="role_id" id="add_role_id" />
                                </div>
                                <div class="mt-4 mb-2">
                                    <x-timezone-drop-down name="timezone" id="add_timezone_id" />
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

            <div id="edit-user-model" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Update User
                            </h3>
                            <button type="button"
                                class="edit_close text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="edit-user-model">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form id="form_update" action="#" method="PUT" class="">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id">
                            <div class="gap-3 p-4">
                                <div class="mt-4">
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="edit_name" class="block mt-1 w-full" type="text" name="name"
                                        required autocomplete="new-password" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="edit_email" class="block mt-1 w-full" type="text"
                                        name="email" required autocomplete="new1-password" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="Password" :value="__('Password')" />
                                    <x-text-input class="block mt-1 w-full" type="password" name="password"
                                        id="edit-password" required autocomplete="new2-password" />
                                </div>
                                <div class="mt-4">
                                    <x-role-dropdown name="role_id" id="update_role_id" />
                                </div>
                                <div class="mt-4 mb-2">
                                    <x-timezone-drop-down name="timezone" id="edit_timezone" />
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
    {!! JsValidator::formRequest(App\Http\Requests\UserStoreRequest::class, '#form_store') !!}
    {!! JsValidator::formRequest(App\Http\Requests\UserUpdateRequest::class, '#form_update') !!}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(".select2").select2({
            width: '100%',
            // theme: 'bootstrap-5',
        });
        $(".select2-container").removeClass("select2-container--default");
        $(document).ready(function() {
            var datatable_id = "user-table";
            var list_datatable = $("#" + datatable_id).DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('user_ajax_data_table') }}",
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
                        width: "20%",
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass("px-6 py-3 ");
                        },
                    },
                    {
                        title: "Email",
                        data: "email",
                        name: "email",
                        width: "20%",
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass("px-6 py-3 ");
                        },
                    },
                    {
                        title: "Timezone",
                        data: "timezone",
                        name: "timezone",
                        width: "20%",
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            $(td).addClass("px-6 py-3 ");
                        },
                    },
                    {
                        title: "Role",
                        data: "roles",
                        name: "roles",
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
                        width: "8%",
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

            const $targetEledit = document.getElementById('edit-user-model');
            const modalEditRef = new Modal($targetEledit, {}, {
                id: 'edit-user-model',
                override: true
            });

            const $targetEl = document.getElementById('add-user-model');
            const modelAddUser = new Modal($targetEl, {}, {
                id: 'add-user-model',
                override: true
            });


            

            $(document).on('click', '.add_close', function() {
                modelAddUser.toggle();
            });
            $(document).on('click', '.edit_close', function() {
                modalEditRef.toggle();
            });
            $(document).on('click', '.add_user_modal', function() {
                modelAddUser.toggle();
                $("#form_store").trigger("reset");
                $("#form_store").find(".invalid-feedback").remove();
            });

            $(document).on('click', '.editBtn', function() {
                modalEditRef.toggle();
                var user_id = $(this).data('user_id');
                $(this).trigger("reset");
                $(this).find(".invalid-feedback").remove();
                $.ajax({
                    url: '{{ route('user_edit', '') }}/' + user_id,
                    type: 'GET',
                    dataType: 'json',
                    data: typeof post_parameter !== "undefined" ? post_parameter : {},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        var data = response.data;
                        var user_Role = response.user_role;
                        $("#edit_name").val(data.name);
                        $("#edit_email").val(data.email);
                        $("#user_id").val(data.id);
                        if(user_Role)
                        {
                            $("#update_role_id").val(user_Role.name)
                        }
                        $("#edit_timezone").val(data.timezone).trigger('change');

                        $("#form_update").attr("action", "{{ route('user_update', '') }}/" +
                            user_id);
                    }
                });
            });

            $(document).on('click', '.del-button', function() {
                if (confirm("Are you sure?")) {
                    var user_id = $(this).data('user_id');
                    $.ajax({
                        url: '{{ route('user_remove', '') }}/' + user_id,
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
                        $("#edit-password").val('')
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
                        modelAddUser.toggle();


                    },
                    error: function() {
                        console.error("Failed to fetch attendance data");
                    }
                });

            });
        });
    </script>
@endsection
