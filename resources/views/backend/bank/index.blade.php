@extends('layouts.app')

@section('title', 'Banks')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.tailwindcss.css" />
@endsection

@section('content')

<div class="grid">

    <div class="card">


        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Banks
            </h3>
            <div class="text-center">
                @can($ModuleEnum::SETTING_BANK_CREATE->value)
                <button
                    id="drawerBtn"
                    class="create_new_bank_bank text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                    type="button">
                    Add New Bank
                </button>
                @endcan
                <button
                    class="hidden"
                    data-modal-target="edit-bank-model"
                    data-modal-toggle="edit-bank-model">

                </button>
                <button
                    class="hidden"
                    data-modal-target="add-bank-model"
                    data-modal-toggle="add-bank-model">

                </button>
            </div>
        </div>

        <div class="mt-4 relative">
            <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4 " style="">
                <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="bank-table">

                </table>
            </div>
        </div>
        <!-- Create Bank Modal -->
        <div id="add-bank-model" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-3xl max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create New Bank</h3>
                        <button type="button"
                            class="add_close text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="add-bank-model">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form id="form_store" action="{{ route('bank_store') }}" method="POST" class="">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                            <div>
                                <label class="text-sm dark:text-white">Bank Types *</label>
                                <x-input-dropdown name="bank_type_id" placeholder="Choose Bank Type" :options="$bankTypes"
                                    optionalLabel="name" optionalValue="id" />
                            </div>
                            <div>
                                <x-input-label for="name" :value="__('Bank Name *')" />
                                <x-text-input class="block w-full" type="text" name="bank_name" required />
                            </div>
                            <div>
                                <x-input-label for="bank_address" :value="__('Bank Address *')" />
                                <x-text-input class="block w-full" type="text" name="bank_address" required />
                            </div>
                            <div>
                                <x-input-label for="account_holder_name" :value="__('Account Holder Name *')" />
                                <x-text-input class="block w-full" type="text" name="account_holder_name" required />
                            </div>
                            <div>
                                <x-input-label for="account_number" :value="__('Account Number *')" />
                                <x-text-input class="block w-full" type="text" name="account_number" required />
                            </div>
                            <div>
                                <x-input-label for="iban" :value="__('IBAN')" />
                                <x-text-input class="block w-full" type="text" name="iban" required />
                            </div>
                            <div>
                                <x-input-label for="swift_code" :value="__('Swift Code')" />
                                <x-text-input class="block w-full" type="text" name="swift_code" required />
                            </div>
                            <div>
                                <x-input-label for="sort_code" :value="__('Sort Code')" />
                                <x-text-input class="block w-full" type="text" name="sort_code" required />
                            </div>
                            <div>
                                <x-input-label for="country" :value="__('Country *')" />
                                <x-text-input class="block w-full" type="text" name="country" required />
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <hr class="my-2 dark:opacity-20" />
                                <button type="submit"
                                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Bank Modal -->
        <div id="edit-bank-model" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-3xl max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Update Bank</h3>
                        <button type="button"
                            class="edit_close text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="edit-bank-model">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form id="form_update" action="#" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="bank_id" id="bank_id">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                            <div>
                                <label class="text-sm dark:text-white">Bank Types *</label>
                                <x-input-dropdown name="bank_type_id" id="edit_bank_type_id" placeholder="Choose Bank Type"
                                    :options="$bankTypes" optionalLabel="name" optionalValue="id" />
                            </div>
                            <div>
                                <x-input-label for="bank_name" :value="__('Bank Name *')" />
                                <x-text-input id="bank_name" class="block w-full" type="text" name="bank_name" required />
                            </div>
                            <div>
                                <x-input-label for="bank_address" :value="__('Bank Address *')" />
                                <x-text-input class="block w-full" type="text" id="bank_address" name="bank_address" required />
                            </div>
                            <div>
                                <x-input-label for="account_holder_name" :value="__('Account Holder Name *')" />
                                <x-text-input class="block w-full" type="text" id="account_holder_name" name="account_holder_name" required />
                            </div>
                            <div>
                                <x-input-label for="account_number" :value="__('Account Number *')" />
                                <x-text-input class="block w-full" type="text" id="account_number" name="account_number" required />
                            </div>
                            <div>
                                <x-input-label for="iban" :value="__('IBAN')" />
                                <x-text-input class="block w-full" type="text" id="iban" name="iban" required />
                            </div>
                            <div>
                                <x-input-label for="swift_code" :value="__('Swift Code')" />
                                <x-text-input class="block w-full" type="text" id="swift_code" name="swift_code" required />
                            </div>
                            <div>
                                <x-input-label for="sort_code" :value="__('Sort Code')" />
                                <x-text-input class="block w-full" type="text" id="sort_code" name="sort_code" required />
                            </div>
                            <div>
                                <x-input-label for="country" :value="__('Country *')" />
                                <x-text-input class="block w-full" type="text" id="country" name="country" required />
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <hr class="my-2 dark:opacity-20" />
                                <button type="submit"
                                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Save
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
{!! JsValidator::formRequest(App\Http\Requests\BankStoreRequest::class, "#form_store") !!}
{!! JsValidator::formRequest(App\Http\Requests\BankUpdateRequest::class, "#form_update") !!}
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
<script>
    var datatable_id = "bank-table";
    $(document).ready(function() {

        var list_datatable = $("#" + datatable_id).DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pagingType: "full_numbers",
            ajax: {
                url: "{{ route('bank_ajax_data_table') }}",
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: function(d) {
                    var searchValue = $('#input_search').val();
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
                    title: "Bank Name",
                    data: "bank_and_country",
                    name: "bank_and_country",
                    width: "40%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                {
                    title: "Bank Address",
                    data: "bank_address",
                    name: "bank_address",
                    width: "25%",
                    orderable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass("px-6 py-3 ");
                    },
                },
                // {
                //     title: "Country",
                //     data: "country",
                //     name: "country",
                //     width: "20%",
                //     orderable: false,
                //     createdCell: function(td, cellData, rowData, row, col) {
                //         $(td).addClass("px-6 py-3 ");
                //     },
                // },
                {
                    title: "Status",
                    data: "is_active",
                    name: "is_active",
                    width: "25%",
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
                    width: "10%",
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

                    $(thead).addClass('text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400')
                }, 10);
            },
            createdRow: function(row, data, dataIndex) {
                $('.dt-layout-table').next().addClass('pagination-show-entries');
                $(row).addClass("odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200");
            },
            drawCallback: function(settings) {
                if (typeof initializeDropdowns === 'function') {
                    initializeDropdowns();
                } else {
                    console.warn('initializeDropdowns is not defined');
                }
            }

        });

        const $targetEledit = document.getElementById('edit-bank-model');
        const modalEditRef = new Modal($targetEledit, {}, {
            id: 'edit-bank-model',
            override: true
        });

        const $targetEl = document.getElementById('add-bank-model');
        const modelAddBank = new Modal($targetEl, {}, {
            id: 'add-bank-model',
            override: true
        });


        $(document).on('click', '#drawerBtn', function() {
            modelAddBank.toggle();
            $("#form_store").trigger("reset");
            $("#form_store").find(".invalid-feedback").remove();

        });

        $(document).on('click', '.create_new_bank', function() {
            modelAddBank.toggle();
            $("#form_store").trigger("reset");
            $("#form_store").find(".invalid-feedback").remove();
        });

        $(document).on('click', '.add_close', function() {
            modelAddBank.toggle();
        });
        $(document).on('click', '.edit_close', function() {
            modalEditRef.toggle();
        });

        $(document).on('click', '.editBtn', function() {
            modalEditRef.toggle();
            var bank_id = $(this).data('bank_id');
            $.ajax({
                url: '{{ route("bank_edit", "") }}/' + bank_id,
                type: 'GET',
                dataType: 'json',
                data: typeof post_parameter !== "undefined" ? post_parameter : {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    var response = response.data;
                    $("#bank_name").val(response.bank_name);
                    $("#bank_address").val(response.bank_address);
                    $("#edit_bank_type_id").val(String(response.bank_type_id)).trigger('change');
                    $("#account_holder_name").val(response.account_holder_name);
                    $("#account_number").val(response.account_number);
                    $("#iban").val(response.iban);
                    $("#swift_code").val(response.swift_code);
                    $("#sort_code").val(response.sort_code);
                    $("#country").val(response.country);

                    $("#bank_id").val(response.id);

                    $("#form_update").attr("action", "{{ route('bank_update', '') }}/" + bank_id);
                }
            });
        });

        $(document).on('click', '.del-button', function() {
            if (confirm("Are you sure?")) {
                var bank_id = $(this).data('bank_id');
                $.ajax({
                    url: '{{ route("bank_remove", "") }}/' + bank_id,
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
                    console.error("Failed to fetch data");
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
                    modelAddBank.toggle();


                },
                error: function() {
                    console.error("Failed to fetch data");
                }
            });

        });

        $(document).on('click', '.bank_active_inactive', function() {
            var bank_id = $(this).data('bank_id');
            if (confirm($(this).data('message'))) {
                $.ajax({
                    url: '{{ route("bank_active_inactive", "") }}/' + bank_id,
                    method: "POST",
                    dataType: "json",
                    context: this,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function(response) {
                        showSuccessToast(response.message);
                        var currentPage = $("#" + datatable_id).DataTable().page.info().page;
                        list_datatable.page(currentPage).draw(false);
                    },
                    error: function() {
                        console.error("Failed to fetch data");
                    }
                });
            }
        });
    });
</script>
@endsection