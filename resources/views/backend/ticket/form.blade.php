@extends('layouts.app')

@section('title', 'Ticket')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<style>
    /* Apply to the dropdown list */
    .dropdown-list {
        max-height: 250px;
        /* Keeps it scrollable */
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: gray transparent;
        /* Only stick is visible */
    }

    /* Webkit (Chrome, Edge, Safari) */
    .dropdown-list::-webkit-scrollbar {
        width: 4px;
        /* Ultra-thin scrollbar */
    }

    .dropdown-list::-webkit-scrollbar-thumb {
        background: gray;
        /* Teal color stick */
        border-radius: 10px;
    }

    .dropdown-list::-webkit-scrollbar-track {
        background: transparent;
        /* No visible track */
    }
</style>
<div class="">
    <div class="card">
        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center mb-2">
            <h3 class="font-extrabold">
                {{ isset($ticket) ? 'Edit' : 'Create' }} Ticket
            </h3>
            <div class="mb-0">
                <a
                    href="{{ isset($customer_id) ? route('customer.lead', ['id' => $customer_id]) : route('ticket.index') }}">
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
            {{-- {{$errors}} --}}

            @if ($errors->any())
            <div class="alert alert-border-danger alert-dismissible fade show">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-danger"><span class="material-icons-outlined fs-2">report_gmailerrorred</span>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-danger">Error</h6>
                        <div class="">{!! implode('', $errors->all('<div>:message</div>')) !!}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form id='myForm' action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="">

                    <input type='hidden' id="id" name="id" value="{{ isset($ticket) ? $ticket->id : '' }}" />

                    <div class="grid grid-cols-3 md:gap-4">

                        <div class="customer">
                            <label class="text-sm dark:text-white">
                                Select Customer <span class="text-red-500">*</span>
                            </label>
                            <x-input-dropdown name="customer_id" id="customer_id" placeholder="Choose Customer"
                                class="" :options="$customers" optionalLabel="name" optionalValue="id"
                                value="{{ isset($ticket) ? $ticket['customer_id'] : (isset($customer_id) ? $customer_id : old('customer_id')) }}"
                                readonly :readonly="isset($ticket)" />
                        </div>

                        <div class="lead">
                            <label class="text-sm dark:text-white">
                                Select Lead <span class="text-red-500">*</span>
                            </label>
                            <x-input-dropdown name="lead_id" id="lead_id" placeholder="Choose Lead" class=""
                                :options="$customerLeads" optionalLabel="name" optionalValue="id"
                                value="{{ isset($ticket) ? $ticket['lead_id'] : (isset($customer_id) || isset($lead_id) ? $lead_id : old('lead_id')) }}"
                                {{--  disabled="{{ $readonly ?? false }}" --}} :readonly="isset($ticket)" />
                        </div>

                        @if (isset($ticket))
                        <input type="hidden" id='ticket_id' name="customer_id"
                            value="{{ $ticket['customer_id'] }}">
                        @endif

                        <div class="mt-2">
                            <x-text-field id="client_name" name='client_name' label="Client Name"
                                placeholder="Enter Client's Name" class=""
                                value="{{ isset($ticket) ? $ticket->client_name : old('client_name') }}" :readonly="isset($ticket)" />
                        </div>

                        {{-- <div class="mt-2">
                                <x-text-field id="client_address" name='client_address' label="Client Address"
                                    placeholder="Enter Client's Address" class=""
                                    value="{{ isset($ticket) ? $ticket->client_address : old('client_address') }}"
                        required />
                    </div> --}}


                    {{-- <div class="mt-2">                            
                                <x-text-field
                                    id="poc_details"
                                    name='poc_details'
                                    label="POC Details"
                                    placeholder="Enter Poc details"
                                    class=""
                                    value="{{ isset($ticket) ? $ticket->poc_details : old('poc_details') }}"
                    required
                    />
                </div>


                <div class="mt-2">
                    <x-text-field
                        id="re_details"
                        name='re_details'
                        label="RE Details"
                        placeholder="Enter RE Details"
                        class=""
                        value="{{ isset($ticket) ? $ticket->re_details : old('re_details') }}"
                        required />
                </div>

                <div class="mt-2">
                    <x-text-field
                        id="call_invites"
                        name='call_invites'
                        label="Call Invites"
                        placeholder="Enter call invites"
                        class=""
                        value="{{ isset($ticket) ? $ticket->call_invites : old('call_invites') }}"
                        required />
                </div> --}}



                
        </div>

           {{-- DIVIVDER --}}
        <div class="inline-flex items-center w-full mt-4">
            <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            <span
                class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                Task Details
            </span>
        </div>

        <div class="grid md:grid-cols-3 gap-3">
            <div class="mt-2">
                <x-text-field id="task_name" name='task_name' label="Task Name"
                    placeholder="Enter Task Name" class=""
                    value="{{ isset($ticket) ? $ticket->task_name : old('task_name') }}" :readonly="isset($ticket)" required />
            </div>

            <div class="mt-2 task_start_date">
                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                    for="file_input">
                    Task Start Date <span class="text-red-500">*</span>
                </label>
                <input id="task_start_date" name="task_start_date" {{-- datepicker  --}} type="date"
                    min="<?php echo  isset($ticket) ? '' : date('Y-m-d'); ?>"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Select date"
                    value="{{ old('task_start_date', $ticket->task_start_date ?? '') }}" @if(isset($ticket)) readonly @endif>
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
                <input id="task_end_date" name="task_end_date" {{-- datepicker  --}} type="date"
                    min="<?php echo  isset($ticket) ? '' : date('Y-m-d'); ?>"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Select date"
                    value="{{ old('task_end_date', $ticket->task_end_date ?? '') }}" @if(isset($ticket)) readonly @endif>
                <div>
                    @error('task_end_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>


            <div class="mt-2">
                <label for="time"
                    class="block text-sm mb-1 font-medium text-gray-900 dark:text-white">
                    Task Time<span class="text-red-500">*</span>
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
                        class="bg-gray-50 border ticketing-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        value="{{ isset($ticket) ? $ticket->task_time : old('task_time') ?? '00:00' }}"
                        required @if(isset($ticket)) readonly @endif />
                    <div>
                        @error('task_time')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-2 md:mt-2">
            <div class="mt-2 md:col-span-1/2">
                <x-text-area id="scope_of_work" name='scope_of_work' label="Scope Of Work"
                    placeholder="Enter scope / Details of work" class=""
                    value="{{ isset($ticket) ? $ticket->scope_of_work : old('scope_of_work') }}"
                    required :readonly="isset($ticket)" />
            </div>
            <div class="mt-2 md:col-span-1/2">
                <x-text-area id="tools" name='tools' label="Tools"
                    placeholder="Tools" class=""
                    value="{{ isset($ticket) ? $ticket->tools : old('tools') }}"
                     />
            </div>
        </div>


        <!-- Engineer DropDown -->

        <div class="inline-flex items-center w-full mt-4">
            <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            <span
                class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                Engineer
            </span>
        </div>

        


        <div class="grid md:grid-cols-3 gap-2 md:mt-2">
            <div class="customer">
                <label class="text-sm dark:text-white">
                    Select Engineer <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <!-- Dropdown Button -->
                    <button id="dropdownButton" type="button" class="flex items-center gap-3 p-2 border rounded-md bg-white dark:bg-gray-800 w-full justify-between">
                        <span class="flex items-center gap-2">
                            <img id="selectedImage" class="w-8 h-8 rounded-full hidden" src="" alt="Selected Engineer">
                            <span id="selectedValue" class="text-gray-900 dark:text-white">Choose Engineer</span>
                        </span>
                        <svg class="w-5 h-5 text-gray-600 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Hidden Input for Engineer ID -->
                    <input type="hidden" id="engineer_id" name="engineer_id"
                        value="{{ isset($ticket) ? $ticket['engineer_id'] : old('engineer_id') }}">

                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu" class="dropdown-list hidden absolute bg-white dark:bg-gray-800 shadow-md rounded-md w-full z-50 mt-2 max-h-60 overflow-y-auto custom-scrollbar">
                        <input type="text" id="searchInput" class="w-full p-2 border-b dark:bg-gray-900 dark:text-white" placeholder="Search...">
                        <ul id="dropdownList">
                            @include('backend.engineer.engineer_timezone', ['engineers' => $engineers, 'currencySymbols' => $currencySymbols])
                        </ul>
                    </div>

                </div>

                @error('engineer_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-0 engineer_agreed_rate_input {{ isset($ticket) && $ticket->rate_type == 'agreed' &&  $ticket->engineer_agreed_rate > 0 ? '' :  'hidden' }}" >
                <x-text-field id="engineer_agreed_rate" name='engineer_agreed_rate' label="Engineer Agreed Rate "
                placeholder="Enter engineer agreed rate" class=""
                value="{{ isset($ticket) && $ticket->rate_type == 'agreed' ? $ticket->engineer_agreed_rate : old('engineer_agreed_rate') }}" />
            </div>
        </div>

        {{-- DIVIVDER --}}
        <div class="inline-flex items-center w-full mt-4">
            <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            <span
                class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                Address
            </span>
        </div>
        <div class="grid md:grid-cols-3 gap-3">
            <div class="mt-2">
                <x-text-field id="apartment" name="apartment" label="Apartment"
                    placeholder="Enter Apartment Name" class=""
                    value="{{ isset($ticket) ? $ticket->apartment : old('apartment') }}" required :readonly="isset($ticket)" />
            </div>
            <div class="mt-2">
                <x-text-field id="add_line_1" name="add_line_1" label="Address Line 1"
                    placeholder="Enter Address Line 1" class=""
                    value="{{ isset($ticket) ? $ticket->add_line_1 : old('add_line_1') }}" required :readonly="isset($ticket)" />
                <ul id="suggestions-list" style="border:1px solid #ccc; max-height:150px; overflow:auto; padding:0; margin:0; position:absolute; background:#fff; z-index:9999; list-style:none;"></ul>
                <input type="hidden" name="latitude" id="latitude" value="{{ isset($ticket) ? $ticket->latitude : old('latitude') }}"/>
                <input type="hidden" name="longitude" id="longitude" value="{{ isset($ticket) ? $ticket->longitude : old('longitude') }}"/>
            </div>
            <div class="mt-2">
                <x-text-field id="add_line_2" name="add_line_2" label="Address Line 2"
                    placeholder="Enter Address Line 2" class=""
                    value="{{ isset($ticket) ? $ticket->add_line_2 : old('add_line_2') }}" :readonly="isset($ticket)" />
            </div>
            <div class="mt-2">
                <x-text-field id="city" name="city" label="City" placeholder="Enter City"
                    class="" value="{{ isset($ticket) ? $ticket->city : old('city') }}" required :readonly="isset($ticket)" />
            </div>
            <div class="mt-2">
                <x-text-field id="country" name="country" label="Country" placeholder="Enter Country"
                    class="" value="{{ isset($ticket) ? $ticket->country : old('country') }}"
                    required :readonly="isset($ticket)" />
            </div>
            <div class="mt-2">
                <x-text-field id="zipcode" name="zipcode" label="Zip code"
                    placeholder="Enter Zip code" class=""
                    value="{{ isset($ticket) ? $ticket->zipcode : old('zipcode') }}" required :readonly="isset($ticket)" />
            </div>
            <div class="mt-2">
                <x-timezone-drop-down name="timezone" id="timezone" value="{{ isset($ticket) ? $ticket->timezone : old('timezone') }}" class="select2" />
            </div>
        </div>
        <div class="inline-flex items-center w-full mt-4">
            <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            <span
                class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                Extra Info
            </span>
        </div>

        <div class="grid md:grid-cols-3 gap-2">
            <!-- Extra Information  -->
            <div class="mt-2">
                <x-text-field id="poc_details" name='poc_details' label="POC Details"
                    placeholder="Enter Poc details" class=""
                    value="{{ isset($ticket) ? $ticket->poc_details : old('poc_details') }}" />
            </div>
            <div class="mt-2">
                <x-text-field id="re_details" name='re_details' label="RE Details"
                    placeholder="Enter RE Details" class=""
                    value="{{ isset($ticket) ? $ticket->re_details : old('re_details') }}" />
            </div>
            <div class="mt-2">
                <x-text-field id="call_invites" name='call_invites' label="Call Invites"
                    placeholder="Enter call invites" class=""
                    value="{{ isset($ticket) ? $ticket->call_invites : old('call_invites') }}" />
            </div>
            <div class="md:col-span-1 mt-2">
                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                    for="profile_image">
                    Documents
                </label>
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="documents" name="documents" type="file">
                @error('documents')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
                @if (isset($ticket) && $ticket['documents'])
                <p class="text-right text-sm text-blue-500 mb-0 pb-0"><a
                        href="{{ asset('storage/' . $ticket['documents']) }}"
                        target="__blank">document</a></p>
                @endif
            </div>
            <div class="md:col-span-1 mt-2">
                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                    for="profile_image">
                    Sign Of Sheet
                </label>
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="ref_sign_sheet" name="ref_sign_sheet" type="file">
                @error('ref_sign_sheet')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
                @if (isset($ticket) && $ticket['ref_sign_sheet'])
                <p class="text-right text-sm text-blue-500 mb-0 pb-0"><a
                        href="{{ asset('storage/' . $ticket['ref_sign_sheet']) }}"
                        target="__blank">sign_sheet</a></p>
                @endif
            </div>
            <!-- End Extra Information  -->
        </div>

        {{-- divider  --}}
        <div class="inline-flex items-center w-full mt-4">
            <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            <span
                class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                Rates
            </span>
        </div>

        <div class="grid md:grid-cols-4 gap-2 ">

            @php
            $currency = [
            ['name' => '$ - Dollar', 'value' => 'dollar'],
            ['name' => '€ - Euro', 'value' => 'euro'],
            ['name' => '£ - Pound', 'value' => 'pound'],
            ];
            @endphp
            <div class="currency_type">
                <label class="text-sm dark:text-white">Select Currency</label>
                <x-input-dropdown name="currency_type" id="currency_type" placeholder="Choose Currency"
                    class="" :options="$currency" optionalLabel="name" optionalValue="value"
                    value="{{ isset($ticket) ? $ticket->currency_type : old('currency_type', '') }}" :readonly="isset($ticket)" />
            </div>


            <div class="radioBtn md:col-span-3">

                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Standard Rates <span class="text-red-500"> *</span>
                </label>

                <div class="flex gap-4 mt-4">

                    <input type="hidden" id="rate_type" class="rate_type" name="rate_type" value="{{ isset($ticket) ? $ticket->rate_type : '' }}">

                    <!-- Hourly -->
                    <div class="flex items-center mb-4">
                        <input id="standard_rate_hourly" type="radio" data-rate-type="hourly"

                            value="{{ isset($ticket) ? $selectedLead->hourly_rate : 0 }}"
                            name="standard_rate"
                            class="standard_rate_hourly w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ isset($ticket) && $ticket->standard_rate == $selectedLead->hourly_rate ? 'checked' : '' }}>
                        <label for="standard_rate_hourly"
                            class="standard_rate_hourly ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Hourly - {{ isset($ticket) ? $selectedLead->hourly_rate : 0 }}
                        </label>
                    </div>

                    <!-- Half Day -->
                    <div class="flex items-center mb-4">
                        <input id="standard_rate_halfday" type="radio"
                            value="{{ isset($ticket) ? $selectedLead->half_day_rate : 0 }}"
                            name="standard_rate" data-rate-type="halfday"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ isset($ticket) && $ticket->standard_rate == $selectedLead->half_day_rate ? 'checked' : '' }}>
                        <label for="standard_rate_halfday"
                            class=" standard_rate_halfday ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Half Day - {{ isset($ticket) ? $selectedLead->half_day_rate : 0 }}
                        </label>
                    </div>

                    <!-- Full Day -->
                    <div class="flex items-center mb-4">
                        <input id="standard_rate_fullday" type="radio"
                            value="{{ isset($ticket) ? $selectedLead->full_day_rate : 0 }}"
                            name="standard_rate" data-rate-type="fullday"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ isset($ticket) && $ticket->standard_rate == $selectedLead->full_day_rate ? ' checked ' : '' }}>
                        <label for="standard_rate_fullday"
                            class="standard_rate_fullday ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Full Day - {{ isset($ticket) ? $selectedLead->full_day_rate : 0 }}
                        </label>
                    </div>

                    <!-- Monthly -->
                    <div class="flex items-center mb-4">
                        <input id="standard_rate_monthly" type="radio" data-rate-type="monthly"
                            value="{{ isset($ticket) ? $selectedLead->monthly_rate : 0 }}"
                            name="standard_rate"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ isset($ticket) && $ticket->standard_rate == $selectedLead->monthly_rate ? ' checked ' : '' }}>
                        <label for="standard_rate_monthly"
                            class="standard_rate_monthly ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Monthly - {{ isset($ticket) ? $selectedLead->monthly_rate : 0 }}
                        </label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="standard_rate_agreed" type="radio" data-rate-type="agreed"
                            value="{{ isset($ticket) ? $ticket->standard_rate : 0 }}"
                            name="standard_rate"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ isset($ticket) && $ticket->rate_type == 'agreed' ? ' checked ' : '' }}>
                        <label for="standard_rate_agreed"
                            class="standard_rate_agreed ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Agreed 
                        </label>
                        
                        
                    </div>

                    
                    
                </div>

                <div>
                    @error('standard_rate')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            
            </div>

            <div class="md:col-span-1 mt-2 agreed_rate_input {{ isset($ticket) && $ticket->rate_type == 'agreed' ? '' : 'hidden' }}">
                        <div class="mt-0">
                            <x-text-field id="agreed_rate" name='agreed_rate' label="Agreed Rate"
                            placeholder="Enter agreed rate" class=""
                            value="{{ isset($ticket) ? $ticket->standard_rate : old('agreed_rate') }}" />
                        </div>
                    </div>
            </div>
            {{-- <div class="md:col-span-1 mt-2">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                                    for="profile_image">
                                    Food Expenses <span class="text-red-500">*</span>
                                </label>
                                <input
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    id="food_expenses" name="food_expenses" type="file">
                                @error('food_expenses')
                                    <span class="text-red-500">{{ $message }}</span>
            @enderror
            @if (isset($ticket) && $ticket['food_expenses'])
            <p class="text-right text-sm text-blue-500 mb-0 pb-0"><a
                    href="{{ asset('storage/ticket_docs/' . $ticket['food_expenses']) }}"
                    target="__blank">food_expenses</a></p>
            @endif
        </div>

        <div class="md:col-span-1 mt-2">
            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                for="profile_image">
                Miscellaneous Expenses <span class="text-red-500">*</span>
            </label>
            <input
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                id="misc_expenses" name="misc_expenses" type="file">
            @error('misc_expenses')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
            @if (isset($ticket) && $ticket['misc_expenses'])
            <p class="text-right text-sm text-blue-500 mb-0 pb-0"><a
                    href="{{ asset('storage/ticket_docs/' . $ticket['misc_expenses']) }}"
                    target="__blank">miscellaneous_expense_doc</a></p>
            @endif
        </div> --}}

    </div>


    {{-- divider  --}}
    <div class="inline-flex items-center w-full mt-4">
        <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
        <span
            class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
            Cost
        </span>
    </div>

    <div class="grid md:grid-cols-4 gap-2">
        <div class="mt-2 vat_no">
            <x-input-number id="travel_cost" name='travel_cost' label="Travel Cost / Day"
                placeholder="Enter travel cost / day" class=""
                value="{{ isset($ticket) ? $ticket->travel_cost : old('travel_cost') }}" :readonly="isset($ticket)" />
        </div>

        <div class="mt-2 vat_no">
            <x-input-number id="tool_cost" name='tool_cost' label="Tool Cost"
                placeholder="Enter tool cost" class=""
                value="{{ isset($ticket) ? $ticket->tool_cost : old('tool_cost') }}" :readonly="isset($ticket)" />
        </div>
    </div>

    {{-- add document  --}}
    <div class="grid md:grid-cols-3 gap-2 md:mt-2" id="documents-container">


    </div>


</div>

{{-- customer documnets  --}}
<div class="">

    <hr class="dark:opacity-20 mt-4 mb-4" />

    {{-- submit form  --}}
    <div class="grid md:grid-cols-4 gap-2">

        <div class="md:col-span-1  ">
            <a
                href="{{ isset($customer_id) ? route('customer.lead', ['id' => $customer_id]) : route('ticket.index') }}">
                <button type="button" id='cancelButton'
                    class="text-gray-700 hover:text-white border border-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-center me-2 dark:border-gray-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 justify-center flex items-center w-full focus:ring-4font-medium rounded-lg text-sm py-2.5">
                    Cancel
                </button>
            </a>
        </div>
        <div>
            <button type="submit"
                class="text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                {{ isset($ticket) ? 'Update' : 'Create' }}
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
    })
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDVz2pXtvfL3kvQ6m5kNjDYRzuoIwSZTI&libraries=places"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(".select2").select2({
        width: '100%',
    });
    $(".select2-container").removeClass("select2-container--default");
    // resources/js/geocode.js
    /*$(document).ready(function() {
        // Initialize Google Places Autocomplete
        var autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('add_line_1'),
            // {types: ['address']},
        );

        // When user selects an address from the dropdown
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                alert("No details available for input: '" + place.name + "'");
                return;
            }

            fillAddressComponents(place);
            fillShortAddress(place)
        });

        function fillShortAddress(place) {
            let fullAddress = "";
            fullAddress += place.name;
            fullAddress += ' ' + place.vicinity;

            $('#add_line_1').val(fullAddress);
        }

        
        

        // Function to fill address components
        function fillAddressComponents(place) {
            // Get latitude and longitude
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            $('#latitude').val(lat);
            $('#longitude').val(lng);

            // Get address components
            var country = '';
            var zipCode = '';
            var city = '';

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
    });*/
    let leadId = @json($leadId ?? null);
    let leads = @json($leads);

    $(document).ready(function() {

        // Disable the dropdown if `leadId` exists
        if (leadId) {
            $('#lead_id').attr('disabled', true);
        }

        function getQueryParam(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Get the 'customer_id' from the query parameter
        const customerId = getQueryParam('customer_id');
        const queryLeadId = getQueryParam('lead_id');

        if (customerId && queryLeadId) {
            $('#customer_id').val(customerId);
            setTimeout(() => {
                $('#customer_id').trigger('change');
            }, 1000);
            setTimeout(() => {
                $('#lead_id').val(queryLeadId).change();
            }, 1500);
        }

        //handel lead details base on customer selection
        $('#customer_id').change(function() {

            let customerId = $(this).val()
            let leadArray = leads.filter((lead) => {
                console.log('lead', lead);
                return lead.customer_id == customerId && lead.lead_status == 'confirm'
            });

            console.log('leadArray : ', leadArray);

            $('#lead_id').empty();

            // Append a placeholder option
            $('#lead_id').append('<option value="">Choose Lead</option>');

            // Dynamically populate options
            leadArray.forEach((lead) => {
                if (queryLeadId) {
                    $('#lead_id').append(`<option value="${lead.id}" ${lead.id == queryLeadId ? ' selected ' : ''} >${lead.name}</option>`);
                } else {
                    $('#lead_id').append(`<option value="${lead.id}">${lead.name}</option>`);
                }
            });

            var selectedText = $(this).find('option:selected').text();

            // $('#client_name').val(selectedText.replace(/\s/g, " "));
            $('#client_name').val(selectedText.trim().replace(/\s/g, " "));

        })


        // Handle lead :  change event for the dropdown
        $('#lead_id').change(function() {

            console.log('#lead_id : change');


            let selectedId = $(this).val();
            let leadData = leads.find((item) => item.id == selectedId);

            if (leadData) {
                $('#task_start_date').val(leadData.task_start_date);
                $('#task_end_date').val(leadData.task_end_date);
                $('#task_name').val(leadData.name);
                $('#task_time').val(leadData.task_time);
                $('#client_address').val(leadData.task_location);
                $('#scope_of_work').val(leadData.scope_of_work);
                $('#city').val(leadData.city);
                $('#zipcode').val(leadData.zipcode);
                $('#add_line_1').val(leadData.add_line_1);
                $('#add_line_2').val(leadData.add_line_2);
                $('#country').val(leadData.country);
                $('#timezone').val(leadData.timezone).trigger('change');
                getEngineersByTimezone()
                $('#apartment').val(leadData.apartment);
                $('#currency_type').val(leadData.currency_type);
                $('#travel_cost').val(leadData.travel_cost);
                $('#tool_cost').val(leadData.tool_cost);
                $('#latitude').val(leadData.latitude);
                $('#longitude').val(leadData.longitude);

                $('.standard_rate_hourly').text(`Hourly Rate - ${leadData.hourly_rate}`);
                $('#standard_rate_hourly').val(leadData.hourly_rate);

                $('.standard_rate_monthly').text(`Monthly Rate - ${leadData.monthly_rate}`);
                $('#standard_rate_monthly').val(leadData.monthly_rate);

                $('.standard_rate_halfday').text(`Half Day Rate - ${leadData.half_day_rate}`);
                $('#standard_rate_halfday').val(leadData.half_day_rate);

                $('.standard_rate_fullday').text(`Full Day Rate - ${leadData.full_day_rate}`);
                $('#standard_rate_fullday').val(leadData.full_day_rate);

            } else {
                console.warn('No matching lead found');
            }
        });


        $('input[name="standard_rate"]').on('change', function() {
            // Get the selected radio button's data-rate-type attribute value
            let rateType = $(this).data('rate-type');
            if(rateType === 'agreed')
            {
                $(".agreed_rate_input").removeClass('hidden')
            }else{
                $(".agreed_rate_input").addClass('hidden')
            }

            // Set the hidden input's value to the selected rate type
            $('#rate_type').val(rateType);

            // Log the rate type for debugging (optional)
            console.log('Selected Rate Type:', rateType);

        });

    });

    function getEngineersByTimezone()
    {
        var lead_id = $("#lead_id").val();
        $.ajax({
            url: '{{ route("engg.engineerByTimezone", "") }}/' + lead_id,
            type: 'GET',
            dataType: 'json',
            data: typeof post_parameter !== "undefined" ? post_parameter : {},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("response => ", response)
                $("#dropdownList").html(response.html);
            }
        });
    }


    document.addEventListener("DOMContentLoaded", function() {
        const selectedValue = document.getElementById("selectedValue");
        const selectedImage = document.getElementById("selectedImage");
        const engineerIdInput = document.getElementById("engineer_id");

        // Retrieve the old or existing value from Laravel (set this from backend)
        let selectedEngineerId = engineerIdInput.value;

        if (selectedEngineerId) {
            // Find the corresponding dropdown item
            let selectedItem = document.querySelector(`#dropdownList li[data-value="${selectedEngineerId}"]`);

            if (selectedItem) {
                selectedValue.textContent = selectedItem.getAttribute("data-name") + ' ('+selectedItem.getAttribute("data-currency")+')';
                selectedImage.src = selectedItem.getAttribute("data-image");
                selectedImage.classList.remove("hidden");
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const dropdownButton = document.getElementById("dropdownButton");
        const dropdownMenu = document.getElementById("dropdownMenu");
        const searchInput = document.getElementById("searchInput");
        const dropdownList = document.getElementById("dropdownList").getElementsByTagName("li");
        const selectedValue = document.getElementById("selectedValue");
        const selectedImage = document.getElementById("selectedImage");
        const hiddenInput = document.getElementById("engineer_id");

        // Toggle Dropdown
        dropdownButton.addEventListener("click", (event) => {
            event.stopPropagation();
            dropdownMenu.classList.toggle("hidden");
        });

        // Close Dropdown when clicking outside
        document.addEventListener("click", (event) => {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add("hidden");
            }
        });

        // Search Functionality
        searchInput.addEventListener("input", () => {
            const filter = searchInput.value.toLowerCase();
            Array.from(dropdownList).forEach((item) => {
                let text = item.textContent || item.innerText;
                item.style.display = text.toLowerCase().includes(filter) ? "" : "none";
            });
        });

        // Handle Option Selection
        $(document).on('click', "#dropdownList li", function(){
            Array.from(dropdownList).forEach((item) => {
                item.addEventListener("click", function() {
                    const jobType = this.getAttribute("data-job-type"); // Get job_type

                    if (!jobType || jobType.trim() === "") {
                        // If job_type is missing, prevent selection
                        showErrorToast("The selected engineer type is not available. Please choose a valid engineer type from the list.");
                        return;
                    }

                    if(jobType !== 'full_time')
                    {
                        $(".engineer_agreed_rate_input").removeClass('hidden');   
                    }else{
                        $(".engineer_agreed_rate_input").addClass('hidden');
                    }

                    const name = this.getAttribute("data-name");
                    var currency = this.getAttribute("data-currency");
                    const image = this.getAttribute("data-image");

                    // Update selected value & image
                    selectedValue.textContent = name + ' ('+currency+')';
                    selectedImage.src = image;
                    selectedImage.classList.remove("hidden");

                    hiddenInput.value = this.getAttribute("data-value");

                    // Hide dropdown
                    dropdownMenu.classList.add("hidden");
                });
            });
        });
    });
</script>

@vite([
    'resources/js/geo_address/geo_address.js'
])
@endsection

@endsection