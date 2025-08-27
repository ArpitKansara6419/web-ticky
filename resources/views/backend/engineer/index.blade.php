@extends('layouts.app')

@section('title', 'Engineers')

@section('content')

<div class="grid">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Engineers
            </h3>
            {{-- <div class="text-center">
                    <a href="{{route('engg.create')}}">
            <button
                id="drawerBtn"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                type="button">
                Add Engineer
            </button>
            </a>
        </div> --}}
    </div>

    {{-- card-body  --}}
    <div class="mt-4 relative">

        {{-- data-table  --}}
        <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4  overflow-x-auto" style="">
            <table id="search-table" class="">
                <thead>
                    <tr class="">
                        <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center ">
                                Code
                            </span>
                        </th>
                        <th class="bg-blue-100  dark:bg-gray-900  px-6 py-3">
                            <span class="flex items-center ">
                                Name
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900  px-6 py-3">
                            <span class="flex items-center">
                                Contact
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900  px-6 py-3">
                            <span class="flex items-center ">
                                Job Title
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900  px-6 py-3">
                            <span class="flex items-center ">
                                Job Type
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900  px-6 py-3">
                            <span class="flex items-center ">
                                Location
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900  px-6 py-3">
                            <span class="flex items-center ">
                                Timezone
                            </span>
                        </th>
                        <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center ">
                                Status
                            </span>
                        </th>
                        <th class="bg-blue-100  dark:bg-gray-900">
                            <span class="flex items-center ">
                                Action
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($engineers as $engg)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">#{{ $engg['engineer_code'] }}</td>
                        <td class="px-6 py-4">
                            <div
                                class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                <img class="w-10 h-10 rounded-full" src="{{ $engg['profile_image'] ? asset('storage/' . $engg['profile_image']) : asset('user_profiles/user/user.png') }}"
                                    alt="Rounded avatar">
                                <div class="">
                                    <p class="capitalize">{{ $engg['first_name'] . ' ' . $engg['last_name'] }}
                                    </p>
                                    <p class="text-gray-400">{{ $engg['email'] }}</p>
                                </div>
                            </div>
                            <div class="w-48 bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="bg-primary-dark text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: <?php echo $engineer_profile_status[$engg->id] * 20; ?>%"> {{ $engineer_profile_status[$engg->id] * 20 }}% </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-nowrap">
                            @if (!empty($engg['country_code']))
                            +{{ $engg['country_code'] }}
                            @endif
                            {{ $engg['contact'] }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $engg['job_title'] ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $engg['job_type'] ? ucfirst(str_replace('_', ' ', $engg['job_type'])) : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span>{{ $engg['addr_city'] ?? '-' }} {{ $engg['addr_country'] ? ' | ' . $engg['addr_country'] :'-' }} </span>
                        </td>
                        <td class="px-6 py-4">
                            <span>
                                {{ $engg['timezone'] ?? '-' }}
                                ({{ fetchTimezone($engg['timezone'])['gmtOffsetName'] ?? '' }})
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($engg['status'] == 0)
                            <x-badge type="danger" label="Inactive" class="" />
                            @else
                            <x-badge type="success" label="Active" class="" />
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-1 ">

                                {{-- <a href="{{route('engg.editPage', $engg->id)}}">
                                <button
                                    type="button"
                                    class="editBtn text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600 shadow-lg dark:shadow-lg dark:shadow-green-700/80 font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                    </svg>

                                    <span class="sr-only">Icon description</span>

                                </button>
                                </a> --}}
                                
                                @can($ModuleEnum::ENGINEER_DETAIL->value)
                                <a href="{{ route('engg.show', $engg->id) }}">
                                    <button type="button" title="View" data-engg-id="{{ $engg->id }}"
                                        class="text-white bg-gradient-to-r from-blue-400 via-blue-400 to-blue-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-blue-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                        <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        <span class="sr-only">Icon description</span>
                                        {{-- Show  --}}
                                    </button>
                                </a>
                                @endcan

                                <!-- <button
                                    type="button"
                                    data-modal-target="edit-modal-{{ $engg->id }}"
                                    data-modal-toggle="edit-modal-{{ $engg->id }}"
                                    class="editBtn text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600 shadow-lg dark:shadow-lg dark:shadow-green-700/80 font-medium rounded-lg text-sm px-5 py-2 text-center  flex"
                                >
                                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                    </svg>
                                    <span class="sr-only">Icon description</span>
                                </button> -->

                                {{-- Payout Button --}}
                                @can($ModuleEnum::ENGINEER_PAYOUT->value)
                                <a href="{{ route('payout.show', [ $engg->id, 'filter' => 'all']) }}">
                                    <button type="button" title="Payout" {{-- data-engg-id="{{ $engg->id }}" --}}
                                        class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2 flex text-center">
                                        <svg class="w-5 h-5 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                                d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                        </svg>
                                        <span class="sr-only">Icon description</span>
                                    </button>
                                </a>
                                @endcan

                                @can($ModuleEnum::ENGINEER_DELETE->value)
                                <form id="deleteForm_{{$engg->id}}" action="{{ route('engg.destroy', $engg->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" title="Delete" id="deleteBtn"
                                        data-eng-id="{{$engg->id}}"
                                        class="deleteBtn text-white flex bg-gradient-to-r from-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700 font-medium rounded-lg text-sm px-5 py-2 text-center">
                                        <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                        {{-- Delete --}}
                                    </button>
                                </form>
                                @endcan

                            </div>

                        </td>
                    </tr>
                    {{-- edit dialog  --}}
                    <div id="edit-modal-{{ $engg->id }}" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                {{-- <!-- Modal header -->  --}}
                                <!-- <div class="flex items-center px-6 justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        Edit Engineer Charges
                                                    </h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-modal-{{ $engg->id }}">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div> -->
                                {{-- <!-- Modal body -->  --}}
                                <!-- <form action="{{ route('engg.update', ['engg' => $engg->id]) }}" method="POST" class="">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid gap-3 p-4 grid-cols-2">
                                                        <div class="col-span-2">
                                                            <label for="name" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                                            <input type="text" name="name" id="name" value="{{ $engg->first_name . ' ' . $engg->last_name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                                                        </div>

                                                        <input type="hidden" name="engineer_id" id="engineer_id" value={{ $engg->id }} required=""/>
                                                       
                                                        <div class="col-span-2 sm:col-span-1">
                                                            <label for="hourly_charge" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                                                Hourly Charge
                                                            </label>
                                                            <input type="number" name="hourly_charge" id="hourly_charge" value={{ isset($engg['enggCharge']) ? $engg['enggCharge']['hourly_charge'] : 00 }} class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$00" required="">
                                                        </div>
                                                        <div class="col-span-2 sm:col-span-1">
                                                            <label for="half_day_charge" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                                                Half Day Charge
                                                            </label>
                                                            <input type="number" name="half_day_charge" id="half_day_charge" value={{ isset($engg['enggCharge']) ? $engg['enggCharge']['half_day_charge'] : 00 }} class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$00" required="">
                                                        </div>
                                                        <div class="col-span-2 sm:col-span-1">
                                                            <label for="full_day_charge" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                                                Full Day Charge
                                                            </label>
                                                            <input type="number" name="full_day_charge" id="full_day_charge"  value={{ isset($engg['enggCharge']) ? $engg['enggCharge']['full_day_charge'] : 00 }} class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$00" required="">
                                                        </div>
                                                        <div class="col-span-2 sm:col-span-1">
                                                            <label for="monthly_charge" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                                                Monthly Charge
                                                            </label>
                                                            <input type="number" name="monthly_charge" id="monthly_charge"  value={{ isset($engg['enggCharge']) ? $engg['enggCharge']['monthly_charge'] : 00 }} class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$00" required="">
                                                        </div>
                                        
                                                        <hr class="md:col-span-2 dark:opacity-20"/>
                                                         
                                                            <div class="md:col-span-2 mt-2">
                                                                <button type="submit" class="text-white w-full inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                                    Update
                                                                </button>
                                                            </div>
                                                    </div>
                                                </form> -->
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
                {{-- @php
                            print_r($errors->all());
                        @endphp  --}}

            </table>
        </div>

        {{-- toast-message component --}}
        @if (session('toast'))
        <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}"
            {{--  error="{{ session('toast')['error'] }}" --}} />
        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#search-table", {
                searchable: true,
                sortable: true,
                header: true,
                perPage: 10,
                paging: true,
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('ready!');

            // Fetch the current URL
            var currentUrl = window.location.href;

            // Check if the URL contains '/edit'
            if (currentUrl.indexOf('/edit') !== -1) {
                console.log("Helooooo");
            }
        });

        $(document).ready(function() {

            loadData(1);

            $('.deleteBtn').click(function() {

                var engId = $(this).data('eng-id');
                const confirmation = confirm('Are you sure you want to delete this lead & related data?');
                if (confirmation) {
                    $(`#deleteForm_${engId}`).submit();
                }
            });

            // Function to load data via AJAX
            function loadData(page) {
                $.ajax({
                    url: '/engineers/ajax-pagination?page=' + page,
                    type: 'GET',
                    success: function(response) {
                        console.log('response == ', response);
                    }
                });
            }

        })
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .datatable-wrapper .datatable-top {
            display: flex;
            justify-content: content-between;
            /* Align items to the start */
        }

        .custom-button {
            order: 3;
            /* Set order for your custom button */
            margin-left: 10px;
            /* Add some spacing */
        }
    </style>
</div>
</div>
@endsection