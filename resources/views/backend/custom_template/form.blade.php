@extends('layouts.app')

@section('title', 'New Notification Template')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header flex justify-between items-center mb-2">
            <h3 class="font-extrabold">
                New Notification Template
            </h3>
        </div>

        <hr class="dark:opacity-20 mt-6 mb-2" />

        <div class="card-body relative">
            @if(session('toast'))
            <x-toast-message
                type="{{ session('toast')['type'] }}"
                message="{{ session('toast')['message'] }}" />
            @elseif($errors->any())
            <x-toast-message type="danger" message="Oops, Something went wrong!" />
            @endif

            <form id="myForm" action="{{ route('custom_template.store') }}" method="POST" enctype="multipart/form-data"
                class=" p-2">
                @csrf

                <div class="grid grid-cols-2">

                    <div class="grid grid-cols-1 gap-5  border-gray-200 border-r p-5">
                        <div class="Jobs">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Select Engineer Job Type
                            </label>
                            <div class="flex gap-4">
                                @php
                                $selectedJobTypes = old('job_type', isset($engineer) ? explode(',', $engineer->job_type) : []);
                                @endphp

                                <div class="flex items-center mb-4">
                                    <input id="full_time" type="checkbox" value="full_time" name="job_type[]"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ in_array('full_time', $selectedJobTypes) ? 'checked' : '' }}>
                                    <label for="full_time"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Full Time</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="part_time" type="checkbox" value="part_time" name="job_type[]"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ in_array('part_time', $selectedJobTypes) ? 'checked' : '' }}>
                                    <label for="part_time"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Part Time</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="dispatch" type="checkbox" value="dispatch" name="job_type[]"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ in_array('dispatch', $selectedJobTypes) ? 'checked' : '' }}>
                                    <label for="dispatch"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Dispatch</label>
                                </div>
                            </div>

                            <div>
                                @error('job_type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="engineer">
                            <label class="text-sm dark:text-white">
                                Select Engineer <span class="text-red-500">*</span>
                            </label>
                            <x-input-dropdown
                                name="engineers[]"
                                id="engineer_select"
                                placeholder="Search and Select Engineer"
                                class=""
                                :options="$engineers"
                                optionalLabel="first_name"
                                optionalValue="id"
                                :value="old('engineers', isset($selectedEngineers) ? $selectedEngineers : [])"
                                multiple />
                        </div>


                        <div class="notification_template">
                            <label class="text-sm dark:text-white">
                                Select Notification Tempate <span class="text-red-500">*</span>
                            </label>
                            <x-input-dropdown
                                name="notification_template"
                                id="notification_template"
                                placeholder="Notification Tempate (Single Selection)"
                                class=""
                                :options="$templates"
                                optionalLabel="template_name"
                                optionalValue="id"
                                value="{{ isset($lead) ? $lead['notification_template_id'] : (isset($notification_template_id) ? $notification_template_id : old('notification_template')) }}"
                                readonly />
                        </div>


                        {{-- Title Name --}}
                        <div>
                            <x-text-field
                                id="notification_title"
                                name="notification_title"
                                label="Notification Title"
                                placeholder="Enter Notification Title"
                                value="{{ old('notification_title', $notification->notification_title ?? '') }}"
                                required />
                            @error('notification_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Subtitle Name --}}
                        <div>
                            <x-text-field
                                id="notification_subtitle"
                                name="notification_subtitle"
                                label="Notification Sub Title"
                                placeholder="Enter Notification Sub Title"
                                value="{{ old('notification_subtitle', $notification->notification_subtitle ?? '') }}"
                                required />
                            @error('notification_subtitle')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Description (Full Width) --}}
                        <div>
                            <x-text-area
                                id="notification_text"
                                name="notification_text"
                                label="Notification Text"
                                placeholder="Enter Notification Text"
                                value="{{ old('notification_text', $notification->notification_text ?? '') }}"
                                required />
                            @error('notification_text')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-5   p-5 ">
                        <div class="interval w-full">
                            <label class="block mb-2 text-xl font-medium text-gray-900 dark:text-white">
                                Select Notification Interval
                            </label>
                            <div class="flex gap-4">
                                @php
                                $selectedInterval = old('notification_interval', isset($engineer) ? $engineer->notification_interval : '');
                                @endphp

                                <div class="flex items-center mb-4">
                                    <input id="daily" type="radio" value="daily" name="notification_interval"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedInterval === 'daily' ? 'checked' : '' }}>
                                    <label for="daily" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Daily</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="weekly" type="radio" value="weekly" name="notification_interval"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedInterval === 'weekly' ? 'checked' : '' }}>
                                    <label for="weekly" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Weekly</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="monthly" type="radio" value="monthly" name="notification_interval"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedInterval === 'monthly' ? 'checked' : '' }}>
                                    <label for="monthly" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Monthly</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="custom" type="radio" value="custom" name="notification_interval"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedInterval === 'custom' ? 'checked' : '' }}>
                                    <label for="custom" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Custom</label>
                                </div>
                            </div>

                            <div>
                                @error('notification_interval')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>



                        <div class="weekdays  w-full">
                            <label class="block mb-2 text-xl font-medium text-gray-900 dark:text-white">
                                Week Days
                            </label>
                            <div class="flex gap-5 flex-wrap">
                                @php
                                $selectedDay = old('weekday', isset($engineer) ? $engineer->weekday : '');
                                @endphp

                                <div class="flex items-center mb-4">
                                    <input id="monday" type="radio" value="monday" name="weekday"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedDay == 'monday' ? 'checked' : '' }}>
                                    <label for="monday"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Monday</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="tuesday" type="radio" value="tuesday" name="weekday"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedDay == 'tuesday' ? 'checked' : '' }}>
                                    <label for="tuesday"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tuesday</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="wednesday" type="radio" value="wednesday" name="weekday"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedDay == 'wednesday' ? 'checked' : '' }}>
                                    <label for="wednesday"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Wednesday</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="thursday" type="radio" value="thursday" name="weekday"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedDay == 'thursday' ? 'checked' : '' }}>
                                    <label for="thursday"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Thursday</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="friday" type="radio" value="friday" name="weekday"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedDay == 'friday' ? 'checked' : '' }}>
                                    <label for="friday"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Friday</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="saturday" type="radio" value="saturday" name="weekday"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedDay == 'saturday' ? 'checked' : '' }}>
                                    <label for="saturday"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Saturday</label>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input id="sunday" type="radio" value="sunday" name="weekday"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ $selectedDay == 'sunday' ? 'checked' : '' }}>
                                    <label for="sunday"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sunday</label>
                                </div>

                            </div>

                            <div>
                                @error('notification_interval')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="mt-2 w-full">
                            <label for="time" class="block text-sm mb-1 font-medium text-gray-900 dark:text-white">
                                Time<span class="text-red-500">*</span>
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
                                <input type="time" id="time" name="time"
                                    class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    value="{{ isset($lead) ? $lead->time : old('time') ?? '00:00' }}"
                                    required />
                            </div>
                        </div>



                        <div class="month-dropdown  w-full">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Select Month
                            </label>
                            <select name="month" id="month"
                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>

                        <div class="day-dropdown   w-full">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Select Day
                            </label>
                            <select name="day" id="day"
                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <!-- Dynamically fill with JS if needed -->
                                <script>
                                    let daySelect = document.getElementById("day");
                                    for (let i = 1; i <= 31; i++) {
                                        let option = document.createElement("option");
                                        option.value = i;
                                        option.textContent = i;
                                        daySelect.appendChild(option);
                                    }
                                </script>
                            </select>
                        </div>

                        <div class="mt-2 end_date w-full">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                                for="end_date">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="end_date"
                                name="end_date"
                                type="date"
                                min="<?php echo date('Y-m-d'); ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date" value="{{ old('end_date', $lead['end_date'] ?? '') }}">
                            <div>
                                @error('end_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-2  date w-full">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                                for="file_input">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="custom_date"
                                name="custom_date"
                                type="date"
                                min="<?php echo date('Y-m-d'); ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date" value="{{ old('custom_date', $lead['custom_date'] ?? '') }}">
                            <div>
                                @error('custom_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>

                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('notification_template.create') }}" class="px-6 py-2 text-gray-900 dark:text-gray-100 border rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600">
                        {{ isset($notification) ? 'Update' : 'Add' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection