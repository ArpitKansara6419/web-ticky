@extends('layouts.app')

@section('title', 'Holiday')

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
                    Holiday
                </h3>
                <div class=" gap-2 flex justify-between items-center">
                    <div class="  ">
                        <div class="mt-2 mb-3 w-100">
                            <x-text-field id="search" name="search" label=""
                                placeholder="Search..." class="" />

                        </div>
                        
                        
                    </div>
                    <div>
                        <div class="mt-2 mb-3 w-100">
                            <x-country-drop-down name="country" id="country_id" />
                        </div>
                    </div>
                    @can($ModuleEnum::SETTING_HOLIDAY_CREATE->value)
                        <div>
                            <a class="mt-2 mb-3" href="{{ route('holiday.create') }}">
                                <button id="drawerBtn"
                                    class="text-white bg-primary bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                                    type="button">
                                    Add Holiday
                                </button>
                            </a>
                        </div>

                        <div>
                            <button
                                id="syncBtn"
                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 " 
                                type="button" >
                                Sync Holiday
                            </button>
                        </div>
                    @endcan
                </div>

            </div>

            <div class="mt-4 relative">
                <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4  overflow-x-auto">
                    <table class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        id="holiday-table">


                    </table>
                </div>
            </div>

        </div>
    </div>

    @include('backend.holiday.sync_modal')
@endsection


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script>
        var HOLIDAY_DATATABLE = "{{ route('holiday.dataTable') }}";
        var HOLIDAT_REMOVE = "{{ route('holiday.destroy', '') }}";

        var HOLIDAY_COUNTRY_LOAD = "{{ route('holiday.countries') }}";
        var SYNC_HOLIDAY = "{{ route('holiday.sync') }}";
        var HOLIDAY_ACTIVE_INACTIVE = "{{ route('holiday.activeInactive', '') }}/";
    </script>

    @vite([
        'resources/js/holiday/index.js',
        'resources/js/holiday/holiday_sync.js'
    ])
@endsection
