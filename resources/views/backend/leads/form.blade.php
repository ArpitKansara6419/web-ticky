@extends('layouts.app')

@section('title', 'Leads form')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.tailwindcss.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center mb-2">
            <h3 class="font-extrabold">
                {{ isset($lead) ? ' Update ' : 'Generate' }} Lead
            </h3>
            <div class="mb-0">
                <a
                    href="{{ route('lead.index') }}">
                    <button type="button"
                        class="text-white flex items-center justify-center bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 6v12m8-12v12l-8-6 8-6Z" />
                        </svg>

                        Back
                    </button>
                </a>
            </div>
        </div>

        <hr class="dark:opacity-20 mt-6 mb-2" />

        {{-- card-body  --}}
        <div class="card-body relative ">

            {{-- toast-message component --}}
            @if (session('toast'))
            <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}"
                @if (isset(session('toast')['error'])) error="{{ session('toast')['error'] }}" @endif />
            @elseif($errors->any())
            <x-toast-message type="danger" message="Oops, Something went wrong, Check the form again!" />
            @endif

            <form id='myForm' action="{{ route('lead.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="">
                    @if (isset($clone))
                        <!-- <input type='hidden' id="id" name="id" value="#" /> -->
                    @else
                        <input type='hidden' id="id" name="id" value="{{ isset($lead) ? $lead->id : '' }}" />
                    @endif

                    <div class="grid grid-cols-3 md:gap-4">

                        <div class="mt-2">
                            <x-text-field id="name" name="name" label="Task Name" placeholder="Enter Task Name"
                                class="" value="{{ isset($lead) ? $lead->name : old('name') }}" required />
                        </div>

                        <div class="customer">
                            <label class="text-sm dark:text-white">
                                Select Customer <span class="text-red-500">*</span>
                            </label>
                            <x-input-dropdown name="customer_id" id="customer_id" placeholder="Choose Customer"
                                class="" :options="$customers" optionalLabel="name" optionalValue="id"
                                value="{{ isset($lead) ? $lead['customer_id'] : (isset($customer_id) ? $customer_id : old('customer_id')) }}"
                                readonly />
                        </div>

                        @if (isset($lead))
                        <input type="hidden" id='lead_id' name="customer_id" value="{{ $lead['customer_id'] }}">
                        @endif

                        <div class="radioBtn">

                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Lead Type<span class="text-red-500"> *</span>
                            </label>

                            <div class="flex gap-4 mt-4">

                                <div class="flex items-center mb-4">
                                    <input id="lead_type_fulltime" type="radio" value="full_time" name="lead_type"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ old('lead_type', isset($lead) ? $lead->lead_type : '') == 'full_time' ? 'checked' : '' }}>
                                    <label for="lead_type_fulltime"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Full
                                        Time</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="lead_type_shortterm" type="radio" value="short_term" name="lead_type"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ old('customer_type', isset($lead) ? $lead->lead_type : '') == 'short_term' ? 'checked' : '' }}>
                                    <label for="lead_type_shortterm"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Short
                                        Term</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="customer_type_dispatchterm" type="radio" value="dispatch_term"
                                        name="lead_type"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ old('lead_type', isset($lead) ? $lead->lead_type : '') == 'dispatch_term' ? 'checked' : '' }}>
                                    <label for="customer_type_dispatchterm"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Dispatch
                                        Term</label>
                                </div>

                            </div>
                            <div>
                                @error('lead_type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-2">
                            <x-text-field id="end_client_name" name="end_client_name" label="End Client Name" placeholder="Enter End Client Name"
                                class="" value="{{ isset($lead) ? $lead->end_client_name : old('end_client_name') }}"  />
                        </div>

                        <div class="mt-2">
                            <x-text-field id="client_ticket_no" name="client_ticket_no" label="Client Ticket Number" placeholder="Enter Client Ticket Number"
                                class="" value="{{ isset($lead) ? $lead->client_ticket_no : old('client_ticket_no') }}"  />
                        </div>
                    </div>


                    {{-- divider  --}}
                    <div class="inline-flex items-center w-full mt-4">
                        <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                        <span
                            class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                            Task Details
                        </span>
                    </div>

                    <div class="grid md:grid-cols-3 gap-2 md:mt-2 ">

                        {{-- <div class="mt-2 task_date">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                                    for="file_input">
                                    Task Date <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    id="task_date" 
                                    name="task_date" 
                                    type="date"
                                    min="<?php echo date('Y-m-d'); ?>"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date" value="{{ old('task_date', $lead['task_date'] ?? '') }}">
                        <div>
                            @error('task_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div> --}}

                    <div class="mt-2 task_start_date">
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                            for="file_input">
                            Task Start Date <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="task_start_date"
                            name="task_start_date"
                            type="date"
                            min="<?php echo date('Y-m-d'); ?>"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Select date" value="{{ old('task_start_date', $lead['task_start_date'] ?? '') }}">
                        <div>
                            @error('task_start_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-2 task_end_date">
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                            for="file_input">
                            Task End Date <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="task_end_date"
                            name="task_end_date"
                            type="date"
                            min="<?php echo date('Y-m-d'); ?>"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Select date" value="{{ old('task_end_date', $lead['task_end_date'] ?? '') }}">
                        <div>
                            @error('task_end_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <div class="mt-2">
                        <label for="time" class="block text-sm mb-1 font-medium text-gray-900 dark:text-white">
                            Task Time (Hour) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="time" id="task_time" name="task_time"
                                class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="{{ isset($lead) ? $lead->task_time : old('task_time') ?? '00:00' }}"
                                required />
                        </div>
                    </div>

                </div>

                <div class="grid md:grid-cols-3 gap-2 md:mt-2">
                    <div class="mt-2 md:col-span-2">
                        <!-- <x-text-field id="scope_of_work" name='scope_of_work' label="Scope Of Work"
                                    placeholder="Enter scope/details of work" class=""
                                    value="{{ isset($lead) ? $lead->scope_of_work : old('scope_of_work') }}" required /> -->
                        <x-text-area id="scope_of_work" name='scope_of_work' label="Scope Of Work"
                            placeholder="Enter scope/details of work" class=""
                            value="{{isset($lead)?$lead->scope_of_work:old('scope_of_work')}}" required />
                    </div>
                </div>


                {{-- divider  --}}
                <div class="inline-flex items-center w-full mt-4">
                    <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                    <span
                        class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                        Address
                    </span>
                </div>

                <div class="grid md:grid-cols-3 gap-2 md:mt-2 ">
                    <div class="mt-2">
                        <x-text-field id="apartment" name="apartment" label="Apartment"
                            placeholder="Enter Apartment Name" class=""
                            value="{{ isset($lead) ? $lead->apartment : old('apartment') }}" required />
                    </div>

                    <div class="mt-2">
                        <x-text-field id="add_line_1" name="add_line_1" label="Address Line 1"
                            placeholder="Enter Address Line 1" class=""
                            value="{{ isset($lead) ? $lead->add_line_1 : old('add_line_1') }}" required />
                        <ul id="suggestions-list" style="border:1px solid #ccc; max-height:150px; overflow:auto; padding:0; margin:0; position:absolute; background:#fff; z-index:9999; list-style:none;"></ul>

                        <input type="hidden" name="latitude" id="latitude" value="{{ isset($lead) ? $lead->latitude : old('latitude') }}"/>
                        <input type="hidden" name="longitude" id="longitude" value="{{ isset($lead) ? $lead->longitude : old('longitude') }}"/>
                    </div>

                    <div class="mt-2">
                        <x-text-field id="add_line_2" name="add_line_2" label="Address Line 2"
                            placeholder="Enter Address Line 2" class=""
                            value="{{ isset($lead) ? $lead->add_line_2 : old('add_line_2') }}" />
                    </div>

                    <div class="mt-2">
                        <x-text-field id="city" name="city" label="City" placeholder="Enter City"
                            class="" value="{{ isset($lead) ? $lead->city : old('city') }}" required />
                    </div>

                    <div class="mt-2">
                        <x-text-field id="country" name="country" label="Country" placeholder="Enter Country"
                            class="" value="{{ isset($lead) ? $lead->country : old('country') }}"
                            required />
                    </div>

                    <div class="mt-2">
                        <x-text-field id="zipcode" name="zipcode" label="Zip code"
                            placeholder="Enter Zip code" class=""
                            value="{{ isset($lead) ? $lead->zipcode : old('zipcode') }}" required />
                    </div>

                    <div class="mt-2">
                        <x-timezone-drop-down 
                            
                            name="timezone" id="timezone" value="{{ isset($lead) ? $lead->timezone : old('timezone') }}" />
                    </div>

                </div>

                {{-- divider  --}}

                <div class="inline-flex items-center w-full mt-4">
                    <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                    <span
                        class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                        Rates
                    </span>
                </div>

                <div class="grid md:grid-cols-3 gap-2">

                    @php
                    $currency = [
                    ['name' => '$ - Dollar', 'value' => 'dollar'],
                    ['name' => '€ - Euro', 'value' => 'euro'],
                    ['name' => '£ - Pound', 'value' => 'pound'],
                    ];
                    @endphp
                    <div class="customer">
                        <label class="text-sm dark:text-white">Select Currency</label>
                        <x-input-dropdown name="currency_type" id="currency_type" placeholder="Choose Currency"
                            class="" :options="$currency" optionalLabel="name" optionalValue="value"
                            value="{{ isset($lead) ? $lead->currency_type : old('currency_type') }}" />
                    </div>

                    <div class="mt-2 vat_no">
                        <x-input-number id="hourly_rate" name='hourly_rate' label="Hourly Rate"
                            placeholder="Enter hourly rate" class=""
                            value="{{ isset($lead) ? $lead->hourly_rate : old('hourly_rate') }}"
                             required  />
                    </div>

                    <div class="mt-2 vat_no">
                        <x-input-number id="half_day_rate" name='half_day_rate' label="Half Day Rate"
                            placeholder="Enter half day rate" class=""
                            value="{{ isset($lead) ? $lead->half_day_rate : old('half_day_rate') }}"
                             required />
                    </div>

                    <div class="mt-2 vat_no">
                        <x-input-number id="full_day_rate" name='full_day_rate' label="Full Day Rate"
                            placeholder="Enter full day rate" class=""
                            value="{{ isset($lead) ? $lead->full_day_rate : old('full_day_rate') }}"
                             required                                />
                    </div>

                    <div class="mt-2 vat_no">
                        <x-input-number id="monthly_rate" name='monthly_rate' label="Monthly Rate"
                            placeholder="Enter monthly rate" class=""
                            value="{{ isset($lead) ? $lead->monthly_rate : old('monthly_rate') }}"
                            {{--  required                                --}} />
                    </div>

                    <!-- @php
                                $status = [
                                    ['name' => 'Bid', 'value' => 'bid'],
                                    ['name' => 'Confirm', 'value' => 'confirm'],
                                    ['name' => 'Reschedule', 'value' => 'reschedule'],
                                    ['name' => 'Cancelled', 'value' => 'cancelled'],
                                ];
                            @endphp
                            <div class="customer">
                                <label class="text-sm dark:text-white">Status <span class="text-red-500">*</span></label>
                                <x-input-dropdown name="lead_status" id="lead_status" placeholder="Lead Status"
                                    class="" :options="$status" optionalLabel="name" optionalValue="value"
                                    value="{{ isset($lead) ? $lead['lead_status'] : 'bid'}}" />
                            </div>  -->

                </div>

                {{-- divider  --}}

                <div class="inline-flex items-center w-full mt-4">
                    <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                    <span
                        class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                        Cost
                    </span>
                </div>


                <div class="grid md:grid-cols-3 gap-2 md:mt-2" id="documents-container">

                    <div class="mt-2 vat_no">
                        <x-input-number id="travel_cost" name='travel_cost' label="Travel Cost / Day"
                            placeholder="Enter travel cost / day" class=""
                            value="{{ isset($lead) ? $lead->travel_cost : old('travel_cost') }}"
                             />
                    </div>

                    <div class="mt-2 vat_no">
                        <x-input-number id="tool_cost" name='tool_cost' label="Tool Cost"
                            placeholder="Enter tool cost" class=""
                            value="{{ isset($lead) ? $lead->tool_cost : old('tool_cost') }}"
                             />
                    </div>

                </div>
        </div>

        {{-- customer documnets  --}}

        {{-- divider  --}}
        <div class="inline-flex items-center w-full mt-4">
            <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            <span
                class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                Status
            </span>
        </div>

        <div class="grid md:grid-cols-3 gap-2">
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
                    value="{{ isset($lead) ? $lead['lead_status'] : 'bid' }}" />
            </div>

            <div class="mt-2 reschedule_date_container hidden">
                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                    for="file_input">
                    Select Reschedule Date <span class="text-red-500">*</span>
                </label>
                <input
                    id="reschedule_date"
                    name="reschedule_date"
                    type="date"
                    min="<?php echo date('Y-m-d'); ?>"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Select date" value="{{ old('reschedule_date', $lead['reschedule_date'] ?? '') }}">
                <div>
                    @error('reschedule_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

        </div>


        <div class="">

            <hr class="dark:opacity-20 mt-4 mb-4" />

            {{-- submit form  --}}
            <div class="grid md:grid-cols-4 gap-2">

                <div class="md:col-span-1  ">
                    <a
                    href="{{ route('lead.index') }}">
                    <button type="button" id='cancelButton'
                        class="text-gray-700 hover:text-white border border-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-center me-2 dark:border-gray-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 justify-center flex items-center w-full focus:ring-4font-medium rounded-lg text-sm py-2.5">
                        Cancel
                    </button>
                    </a>
                </div>
                <div>
                    <button type="submit"
                        class="text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        {{ isset($lead) ? 'Update' : 'Add' }}
                    </button>
                </div>
            </div>

        </div>

        </form>
    </div>

</div>

</div>

@section('scripts')
<script>
    document.getElementById('cancelButton').addEventListener('click', function() {
        document.getElementById('myForm').reset();
        // {{--  window.location.href = '{{ route('customers.index') }}';   --}}
    })
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDVz2pXtvfL3kvQ6m5kNjDYRzuoIwSZTI&libraries=places"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // import 'moment-timezone' from 'moment-timezone';

    $(".select2").select2({
        width: '100%',
    });
    $(".select2-container").removeClass("select2-container--default");
    $(document).ready(function() {

        /*const map = new google.maps.places.Place({
            id: "AIzaSyDDVz2pXtvfL3kvQ6m5kNjDYRzuoIwSZTI",
            requestedLanguage: "en", // optional
        });

        $("#add_line_1").on('input', function(){
            init($(this).val())
        });
        
        async function init(SearchValue) {
            let sessionToken = new google.maps.places.AutocompleteSessionToken();

            // Define request options.
            let request = {
                    input: SearchValue,
                    sessionToken: sessionToken,
                    
            };

            

            // Perform the query and display the results.
            const { suggestions } =
                await google.maps.places.AutocompleteSuggestion.fetchAutocompleteSuggestions(request);

            // const resultsElement = document.getElementById("results");

            for (let suggestion of suggestions) {
                const placePrediction = suggestion.placePrediction;
                const listItem = document.createElement("li");

                listItem.appendChild(
                document.createTextNode(placePrediction.text.text),
                );


                // resultsElement.appendChild(listItem);
            }

            // Show the first predicted place.
            let place = suggestions[0].placePrediction.toPlace();

            await place.fetchFields({
                fields: ["displayName", "formattedAddress"],
            });

            console.log(` First predicted place: ${place.displayName} at ${place.formattedAddress}`)

            // const placeInfo = document.getElementById("prediction");

            // placeInfo.textContent = `
            //     First predicted place: ${place.displayName} at ${place.formattedAddress}`
        }*/

        // Initialize Google Places Autocomplete
        // var autocomplete = new google.maps.places.Autocomplete(
        //     document.getElementById('add_line_1'),
        //     // { types: ['geocode'] }
        // );


        // When user selects an address from the dropdown
        /*autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                alert("No details available for input: '" + place.name + "'");
                return;
            }
            // console.log("place => ",place)
            fillAddressComponents(place);
            fillShortAddress(place);
        });

        
        

        // Function to fill address components
        async function fillAddressComponents(place) {
            // Get latitude and longitude
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            $('#latitude').val(lat);
            $('#longitude').val(lng);

            await getTimezone(lat, lng);

            // Get address components
            var country = '';
            var zipCode = '';
            var city = '';

            console.log("component => ",place.address_components)

            for (var i = 0; i < place.address_components.length; i++) {
                var component = place.address_components[i];
                
                if (component.types.includes('country')) {
                    country = component.long_name;
                    $('#country').val(country);
                }

                if (component.types.includes('country')) {
                    country = component.long_name;
                    $('#city').val(country);
                }
                
                if (component.types.includes('postal_code')) {
                    zipCode = component.long_name;
                    $('#zipcode').val(zipCode);
                }

                if (component.types.includes('locality')) {
                    city = component.long_name;
                } else if (component.types.includes('administrative_area_level_2') && !city) {
                    city = component.long_name;
                } else if (component.types.includes('administrative_area_level_3') && !city) {
                    city = component.long_name;
                }
                
                // For some addresses, city might be in postal_town
                if (component.types.includes('postal_town') && !city) {
                    city = component.long_name;
                }
                $("#city").val(city)
            }
        }

        function fillShortAddress(place) {
            let fullAddress = "";
            fullAddress += place.name;
            fullAddress += ' ' + place.vicinity;

            $('#add_line_1').val(fullAddress);
        }

        async function getTimezone(lat, lng) {
            const timestamp = Math.floor(Date.now() / 1000);
            const apiKey = 'AIzaSyDDVz2pXtvfL3kvQ6m5kNjDYRzuoIwSZTI'; // Ensure your API key has Timezone API enabled

            const response = await fetch(`https://maps.googleapis.com/maps/api/timezone/json?location=${lat},${lng}&timestamp=${timestamp}&key=${apiKey}`);
            const data = await response.json();

            if (data.status === "OK") {
                // return data.timeZoneId; // e.g., 'Asia/Kolkata'
                console.log(data.timeZoneId)
                console.log(data.timeZoneId.trim().length)
                $("#timezone").val(data.timeZoneId.trim()).trigger('change');
                return data.timeZoneId; 
            } else {
                console.error('Timezone API error:', data);
                return null;
            }
        }*/
    });
    $(document).ready(function() {

        const leadId = $('#lead_id').val(); // Get the value of the hidden field

        if (leadId) {
            $('#customer_id').attr('disabled', true); // Disable dropdown if editing
        }

        $(document).on('change', '#lead_status', function() {
            var currentVal = $(this).val();
            if (currentVal === 'reschedule') {
                $('.reschedule_date_container').removeClass('hidden');
                $('#reschedule_date').prop('required', true);
            } else {
                $('.reschedule_date_container hidden').addClass('hidden');
                $('#reschedule_date').val('');
                $('#reschedule_date').prop('required', false);
            }
        });

    })
</script>
@vite([
    'resources/js/geo_address/geo_address.js'
])
@endsection

@endsection