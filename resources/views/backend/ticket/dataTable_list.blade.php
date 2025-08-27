@extends('layouts.app')

@section('title', 'Tickets')

@section('styles')
<!-- <style>
    #customer-table_processing {
        display: none !important;
    }
</style> -->
@endsection

@section('content')
<div class="">
    <div x-data="{'activeTab' : 'all'}" class="card w-full overflow-hidden">
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Tickets
            </h3>
            <div class=" gap-2 flex justify-between items-center">
                <div x-show="activeTab == 'offered'" class="">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="offered_search" name="search" label=""
                            placeholder="Search..." class="" />

                    </div>
                </div>
                <div x-show="activeTab == 'inprogress'" class="">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="inprogress_search" name="search" label=""
                            placeholder="Search..." class="" />

                    </div>
                </div>
                <div x-show="activeTab == 'onhold'" class="">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="onhold_search" name="search" label=""
                            placeholder="Search..." class="" />

                    </div>
                </div>
                <div x-show="activeTab == 'close'" class="">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="close_search" name="search" label=""
                            placeholder="Search..." class="" />

                    </div>
                </div>
                <div x-show="activeTab == 'expired'" class="">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="expired_search" name="search" label=""
                            placeholder="Search..." class="" />

                    </div>
                </div>
                <div x-show="activeTab == 'accepted'" class="">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="accepted_search" name="search" label=""
                            placeholder="Search..." class="" />

                    </div>
                </div>
                <div x-show="activeTab == 'all'" class="">
                    <div class="mt-2 mb-3 w-100">
                        <x-text-field id="all_search" name="search" label=""
                            placeholder="Search..." class="" />

                    </div>
                </div>

                <div>

                    @can($ModuleEnum::TICKET_CREATE->value)
                    <a href="{{ route('ticket.create') }}">
                        <button id="drawerBtn"
                            class="text-white bg-primary-light-one bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                            type="button">
                            Create Ticket
                        </button>
                    </a>
                    @endcan
                    <button class="hidden engineer-change-btn mr-3" type="button"
                        data-modal-target="change-enginner-model"
                        data-modal-toggle="change-enginner-model">
                        Change
                    </button>
                </div>

            </div>

        </div>


        <div class="card-body relative ">


            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-blue-800 hover:text-blue-800 dark:text-blue-800 dark:hover:text-blue-800 border-blue-600 dark:border-blue-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                    <li class="me-2" role="presentation">
                        <button @click="activeTab = 'all'" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="all-styled-tab" data-tabs-target="#styled-all" type="button" role="tab" aria-controls="all" aria-selected="false">All</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button @click="activeTab = 'offered'" class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-styled-tab" data-tabs-target="#styled-Offered" type="button" role="tab" aria-controls="Offered" aria-selected="false">Offered</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button @click="activeTab = 'accepted'" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="accepted-styled-tab" data-tabs-target="#styled-accepted" type="button" role="tab" aria-controls="accepted" aria-selected="false">Accepted</button>
                    </li>
                    <li role="presentation">
                        <button @click="activeTab = 'inprogress'" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="in_progress-styled-tab" data-tabs-target="#styled-in_progress" type="button" role="tab" aria-controls="in_progress" aria-selected="false">In Progress</button>
                    </li>
                    <li role="presentation">
                        <button @click="activeTab = 'onhold'" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="on_hold-styled-tab" data-tabs-target="#styled-on_hold" type="button" role="tab" aria-controls="on_hold" aria-selected="false">On Hold</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button @click="activeTab = 'close'" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="close-styled-tab" data-tabs-target="#styled-close" type="button" role="tab" aria-controls="close" aria-selected="false">Close</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button @click="activeTab = 'expired'" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="expired-styled-tab" data-tabs-target="#styled-expired" type="button" role="tab" aria-controls="expired" aria-selected="false">Expired</button>
                    </li>
                    
                    
                </ul>
            </div>
            <div id="default-styled-tab-content">
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-Offered" role="tabpanel" aria-labelledby="Offered-tab">
                    <!-- <p class="text-sm text-gray-500 dark:text-gray-400">
                        Offered
                    </p> -->
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="offered-table">

                    </table>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-in_progress" role="tabpanel" aria-labelledby="in_progress-tab">
                    <!-- <p class="text-sm text-gray-500 dark:text-gray-400">
                        In Progress
                    </p> -->
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="inprogress-table">

                    </table>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-on_hold" role="tabpanel" aria-labelledby="on_hold-tab">
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="onhold-table">

                    </table>
                </div>

                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-close" role="tabpanel" aria-labelledby="close-tab">
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="close-table">

                    </table>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-expired" role="tabpanel" aria-labelledby="expired-tab">
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="expired-table">

                    </table>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-accepted" role="tabpanel" aria-labelledby="accepted-tab">
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="accepted-table">

                    </table>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-all" role="tabpanel" aria-labelledby="all-tab">
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="all-table">

                    </table>
                </div>

            </div>


            <!-- <div class="border  border-1 overflow-x-auto border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                    id="lead-table">

                </table>
            </div> -->




        </div>



    </div>
    @include('backend.customer.popup_customer_detail')
    <div id="change-enginner-model" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Change Engineer
                    </h3>
                    <button type="button"
                        class="close-engineer-modal text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="change-enginner-model">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form id="ticket-engineer-form" action="{{ route('engineer-change.update') }}" method="POST"
                    class="">
                    @csrf
                    <div class="grid gap-3 p-4 grid-cols-1">
                        <input type="hidden" name="ticket_id" id="ticket_id" value="" required="" />
                        @php
                        $status = array_map(function ($engineer) {
                        return [
                        'name' =>
                        ($engineer['first_name'] ?? '') . ' ' . ($engineer['last_name'] ?? ''),
                        'value' => $engineer['id'] ?? '',
                        ];
                        }, $engineers ?? []);
                        @endphp

                        <div class="customer">
                            <label class="text-sm dark:text-white">Engineers</label>
                            <x-input-dropdown name="engineer" id="engineer" placeholder="Select Engineers"
                                class="" :options="$status" optionalLabel="name" optionalValue="value"
                                value="" />
                        </div>

                        <hr class="md:col-span-2 dark:opacity-20" />

                        <div class="md:col-span-2 mt-2">
                            <button type="submit" id="btn-update-engineer"
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
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
<script>
    var TICKET_DATATABLE = "{{ route('ticket.dataTable') }}";
    setTimeout(function(){
        const $targetEl = document.getElementById('change-enginner-model');
        const $engineerModal = new Modal($targetEl, {}, {
            id: 'change-enginner-model',
            override: true
        });
        $(document).on('click', '.engineer-change-btn', function() {
            $engineerModal.toggle();
            let ticket_id = $(this).data('ticket-id');
            $('#ticket_id').val(ticket_id);
        });
        $(document).on('click', '.close-engineer-modal', function() {
            $engineerModal.toggle();
        });
    }, 200);
</script>
@vite([
'resources/js/tickets/all.js',
'resources/js/tickets/accepted.js',
'resources/js/tickets/close.js',
'resources/js/tickets/in_progress.js',
'resources/js/tickets/offered.js',
'resources/js/tickets/expired.js',
'resources/js/tickets/on_hold.js',
'resources/js/customer/detail.js',
])
<script>
    $(document).on('click', '.del-button', function() {
        if (confirm("Are you sure?")) {
            var ticket_id = $(this).data('ticket-id');
            $.ajax({
                url: '{{ route("ticket.destroy", "") }}/' + ticket_id,
                type: 'DELETE',
                dataType: 'json',
                data: typeof post_parameter !== "undefined" ? post_parameter : {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showSuccessToast(response.message);
                    if ($("#all-table").is(":visible")) {
                        var currentPage = $("#all-table").DataTable().page.info().page;
                        $("#all-table").DataTable().page(currentPage).draw(false);
                    }else if($("#offered-table").is(":visible")) {
                        var currentPage = $("#offered-table").DataTable().page.info().page;
                        $("#offered-table").DataTable().page(currentPage).draw(false);
                    }else if($("#accepted-table").is(":visible")) {
                        var currentPage = $("#accepted-table").DataTable().page.info().page;
                        $("#accepted-table").DataTable().page(currentPage).draw(false);
                    }else if($("#inprogress-table").is(":visible")) {
                        var currentPage = $("#inprogress-table").DataTable().page.info().page;
                        $("#inprogress-table").DataTable().page(currentPage).draw(false);
                    }else if($("#onhold-table").is(":visible")) {
                        var currentPage = $("#onhold-table").DataTable().page.info().page;
                        $("#onhold-table").DataTable().page(currentPage).draw(false);
                    }else if($("#close-table").is(":visible")) {
                        var currentPage = $("#close-table").DataTable().page.info().page;
                        $("#close-table").DataTable().page(currentPage).draw(false);
                    }else if($("#expired-table").is(":visible")) {
                        var currentPage = $("#expired-table").DataTable().page.info().page;
                        $("#expired-table").DataTable().page(currentPage).draw(false);
                    }
                    
                }
            });
        }
    });
</script>
@endsection