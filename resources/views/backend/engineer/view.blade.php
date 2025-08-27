@extends('layouts.app')

@section('title', 'Engineers Profile')

@section('content')

<div class="">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Engineer Details
            </h3>
            <div class="text-center">
                <a href="{{ route('engg.index') }}">
                    <button type="button"
                        class="text-white flex items-center justify-center bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 6v12m8-12v12l-8-6 8-6Z" />
                        </svg>

                        Back
                    </button>
                </a>

            </div>
        </div>

        {{-- page-body  --}}

        <div class="mb-4 mt-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                data-tabs-toggle="#default-tab-content" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab"
                        data-tabs-target="#profile-main" type="button" role="tab" aria-controls="profile-main"
                        aria-selected="false">PROFILE</button>
                </li>
                {{-- <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">TRAVEL</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">PAYMENT</button>
                    </li>  --}}
                <li role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab" aria-controls="contacts"
                        aria-selected="false">TICKETS</button>
                </li>

                <li class="" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="job-tab" data-tabs-target="#jobs"
                        type="button" role="tab" aria-controls="jobs" aria-selected="false"> CHARGES </button>
                </li>

                @if ($engineer->job_type == 'full_time')

                <li class="" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="leave-tab" data-tabs-target="#leaves"
                        type="button" role="tab" aria-controls="jobs" aria-selected="false"> LEAVES </button>
                </li>

                @endif

            </ul>
        </div>

        <div id="default-tab-content">

            <!-- Profile Tab -->
            <div class="hidden p-4 rounded-lg dark:bg-gray-800" id="profile-main" role="tabpanel"
                aria-labelledby="profile-tab">
                <div class="grid md:grid-cols-6 gap-4">
                    <div
                        class="md:col-span-4 p-6 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border dark:border-gray-700">

                        <!-- Profile Info -->
                        <div class="flex items-center gap-4 p-1">
                            <img class="w-24 h-24 border rounded-full"
                                src="{{ $engineer['profile_image'] ? asset('storage/' . $engineer['profile_image'] ) : asset('user_profiles/user/user.png') }}"
                                alt="User profile picture">
                            <div class="text-base">
                                <p class="capitalize text-2xl font-medium text-gray-900 dark:text-white leading-7">
                                    {{ $engineer['first_name'] }} {{ $engineer['last_name'] }}
                                </p>
                                <p class="text-gray-500">{{ $engineer['email'] }}</p>
                                <p class="text-gray-500">{{ $engineer['timezone'] ?? '' }}
                                    ({{ fetchTimezone($engineer['timezone'])['gmtOffsetName'] ?? '' }})
                                </p>
                            </div>
                        </div>

                        <!-- Profile Details -->
                        <div class="grid md:grid-cols-3 gap-2 p-2 text-sm mt-4 text-gray-600 dark:text-gray-400">

                            <p>
                                Contact:
                                @if (!empty($engineer['contact']))
                                +{{ $engineer['country_code'] }} {{ $engineer['contact'] }}
                                @else
                                -
                                @endif
                            </p>

                            <p>
                                Alternative Contact:
                                @if (!empty($engineer['alternative_contact']))
                                +{{ $engineer['country_code'] }} {{ $engineer['alternative_contact'] }}
                                @else
                                -
                                @endif
                            </p>


                            <p class="capitalize">Gender: {{ $engineer['gender'] ?? 'N/A' }}</p>
                            <p class="capitalize">Birthday: {{ $engineer['birthdate'] ?? 'N/A' }}</p>
                            <p class="capitalize">Address:
                                {{ implode(', ', array_filter([
                                        $engineer['addr_apartment'] ?? null,
                                        $engineer['addr_street'] ?? null,
                                        $engineer['addr_address_line_1'] ?? null,
                                        $engineer['addr_address_line_2'] ?? null,
                                        $engineer['addr_zipcode'] ?? null,
                                        $engineer['addr_city'] ?? null,
                                        $engineer['addr_country'] ?? null,
                                    ])) ?: 'N/A' }}
                            </p>

                            <p>Engineer Status:
                                @if ($engineer['status'] == 0)
                                <x-badge type="danger" label="Inactive" />
                                @else
                                <x-badge type="success" label="Active" />
                                @endif
                            </p>
                            <p>Email Verified:
                                @if ($engineer['email_verification'] == 0)
                                <x-badge type="danger" label="Unverified" />
                                @else
                                <x-badge type="success" label="Verified" />
                                @endif
                            </p>
                            <p>Contact Verified:
                                @if ($engineer['phone_verification'] == 0)
                                <x-badge type="danger" label="Unverified" />
                                @else
                                <x-badge type="success" label="Verified" />
                                @endif
                            </p>
                            <p>Created At:
                                {{ utcToTimezone($engineer['created_at'], $engineer['timezone'])->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Language Skills -->
                    <div class="md:col-span-2 border dark:border-gray-800 dark:bg-gray-700 rounded-lg p-4">
                        <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 dark:text-white">
                            <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m13 19 3.5-9 3.5 9m-6.125-2h5.25M3 7h7m0 0h2m-2 0c0 1.63-.793 3.926-2.239 5.655M7.5 6.818V5m.261 7.655C6.79 13.82 5.521 14.725 4 15m3.761-2.345L5 10m2.761 2.655L10.2 15" />
                            </svg>
                            Language Skills
                        </p>

                        @if (count($engineer['enggLang']) > 0 && !empty($engineer['enggLang']))
                        <div class="mt-6">
                            @foreach ($engineer['enggLang'] as $lang)
                            <div
                                class="text-sm mt-2 text-gray-600 dark:text-gray-300 border-gray-600 border-opacity-20 dark:border-opacity-80 border border-1 rounded-lg p-4">

                                <div class="flex justify-between items-center uppercase mb-2 font-bold">
                                    <p class="font-bold">{{ $lang['language_name'] }}</p>
                                    <p>{{ $lang['proficiency_level'] }}</p>
                                </div>

                                @php
                                $skills = ['read' => 'Read', 'write' => 'Write', 'speak' => 'Speak'];
                                @endphp

                                <div class="flex justify-between items-center">
                                    @foreach ($skills as $key => $label)
                                    <div class="flex gap-2">
                                        {{ $label }}
                                        @if ($lang[$key] == 1)
                                        <svg class="w-4 h-5 text-green-500 dark:text-green-500" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        @else
                                        <svg class="w-4 h-5 text-red-500 dark:text-red-500" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-center text-sm  dark:text-gray-400 h-full mt-[20%]">No Language Found</p>
                        @endif

                    </div>


                    {{-- education  --}}
                    <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                        <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                            <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 19V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v13H7a2 2 0 0 0-2 2Zm0 0a2 2 0 0 0 2 2h12M9 3v14m7 0v4" />
                            </svg>
                            Education
                        </p>
                        <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                            @if (isset($engineer['enggEdu']) && count($engineer['enggEdu']) > 0)
                            @foreach ($engineer['enggEdu'] as $edu)
                            <li class="pb-2 sm:pb-4 pt-3">
                                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ $edu['degree_name'] }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            {{ $edu['university_name'] }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col justify-end items-end gap-1">
                                        @if (!empty($edu['media_files']) && is_string($edu['media_files']))
                                        @php
                                        $mediaFiles = json_decode($edu['media_files'], true);
                                        @endphp

                                        @if (is_array($mediaFiles) && count($mediaFiles) > 0)
                                        @foreach ($mediaFiles as $file)
                                        @php
                                        $filePath = 'public/' . $file; // Correct storage path
                                        @endphp
                                        @if (!empty($file) && Storage::exists($filePath))
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                            class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                            View Doc
                                        </a>
                                        @endif
                                        @endforeach
                                        @else
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No document available</p>
                                        @endif
                                        @else
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No document available</p>
                                        @endif

                                        <p class="text-xs dark:text-gray-400">Issued: {{ $edu['issue_date'] }}</p>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                            @else
                            <p class="text-center text-sm dark:text-gray-400">No Education Details Found</p>
                            @endif
                        </ul>




                    </div>


                    {{-- Documnets  --}}
                    <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                        <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                            <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                    d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                            </svg>
                            Documents
                        </p>
                        <ul class="max-w-md max-h-[15rem] overflow-auto divide-y divide-gray-200 dark:divide-gray-700">
                            @if (isset($engineer['enggDoc']) && !empty($engineer['enggDoc']) &&
                            count($engineer['enggDoc']) > 0)
                            @foreach ($engineer['enggDoc'] as $doc)
                            <li class="pb-2 sm:pb-4 pt-2">
                                <div class="flex items-center space-x-4 rtl:space-x-reverse">

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ $doc['document_label'] }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            {{ slugToString($doc['document_type']) }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                            Issued : {{ $doc['issue_date'] }} | Exp. :
                                            {{ $doc['expiry_date'] }}
                                        </p>
                                    </div>

                                    @if (!empty($doc['media_file']) && is_string($doc['media_file']))
                                    @php
                                    $mediaFiles = json_decode($doc['media_file'], true);
                                    @endphp

                                    @if (is_array($mediaFiles) && count($mediaFiles) > 0)
                                    @foreach ($mediaFiles as $file)
                                    @if (!empty($file) && Storage::exists('public/' . $file))
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                        class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                        View Doc
                                    </a>
                                    @endif
                                    @endforeach
                                    @endif
                                    @endif

                                </div>
                            </li>
                            @endforeach
                            @else
                            <p class="text-center text-sm  dark:text-gray-400">No Documents Found</p>
                            @endif

                        </ul>

                    </div>


                    {{-- Right To Work --}}
                    <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                        <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                            <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 3v4a1 1 0 0 1-1 1H5m4 6 2 2 4-4m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                            </svg>
                            Right To Work
                        </p>
                        <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                            @if (isset($engineer['enggRightToWork']) && count($engineer['enggRightToWork']) > 0)
                            @foreach ($engineer['enggRightToWork'] as $rtw)
                            <li class="pb-2 sm:pb-4 pt-3">
                                <div class="flex items-center space-x-4 rtl:space-x-reverse capitalize">

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white ">
                                            {{ slugToString($rtw['type']) }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            {{ $rtw['document_type'] }}
                                        </p>
                                    </div>
                                    <!-- <div class="flex flex-col justify-end items-end gap-1">
                                        <p class="flex flex-col gap-3">
                                            <a href="{{ asset('storage/' . $rtw['document_file']) }}"
                                                target="__blank"
                                                class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                                view doc
                                            </a>

                                            <a href="{{ asset('storage/' . $rtw['university_certificate_file']) }}"
                                                target="__blank"
                                                class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                                view doc
                                            </a>

                                            <a href="{{ asset('storage/' . $rtw['visa_copy_file']) }}"
                                                target="__blank"
                                                class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                                view doc
                                            </a>

                                        </p>
                                    </div> -->
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm dark:text-gray-400">Issued : {{ $rtw['issue_date'] }}
                                    </p>
                                    <p class="text-sm dark:text-gray-400">Expire : {{ $rtw['expire_date'] }}
                                    </p>
                                </div>
                                <p class="flex flex-row gap-3 mt-3">
                                    @if (!empty($rtw['document_file']) && Storage::exists('public/' .
                                    $rtw['document_file']))
                                    <a href="{{ asset('storage/' . $rtw['document_file']) }}" target="__blank"
                                        class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                        View Doc
                                    </a>
                                    @endif

                                    @if (!empty($rtw['university_certificate_file']) && Storage::exists('public/' .
                                    $rtw['university_certificate_file']))
                                    <a href="{{ asset('storage/' . $rtw['university_certificate_file']) }}"
                                        target="__blank"
                                        class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                        View Doc
                                    </a>
                                    @endif

                                    @if (!empty($rtw['visa_copy_file']) && Storage::exists('public/' .
                                    $rtw['visa_copy_file']))
                                    <a href="{{ asset('storage/' . $rtw['visa_copy_file']) }}" target="__blank"
                                        class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                        View Doc
                                    </a>
                                    @endif
                                </p>

                            </li>
                            @endforeach
                            @else
                            <p class="text-center text-sm  dark:text-gray-400">No Right to Work Details Found</p>
                            @endif

                        </ul>

                    </div>


                    {{-- Travel Details  --}}
                    <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                        <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                            <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M18.5 12A2.5 2.5 0 0 1 21 9.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 1 0 5V17a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                            </svg>
                            Travel Details
                        </p>
                        <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                            @if (isset($engineer['enggTravel']))
                            <li class="pb-2 sm:pb-4 pt-3 text-sm">
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Driving Licence :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggTravel']['driving_license'] == 0 ? 'No' : 'Yes' }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Own Vehicle :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggTravel']['own_vehicle'] == 1 ? 'Yes' : 'No' }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Type of Vehicle :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white capitalize">
                                        @if (!empty($engineer['enggTravel']['type_of_vehicle']))
                                        @php
                                        $vehicles = json_decode(
                                        $engineer['enggTravel']['type_of_vehicle'],
                                        true,
                                        );
                                        @endphp
                                        @if (is_array($vehicles))
                                        {{ implode(', ', $vehicles) }}
                                        @else
                                        {{ '' }}
                                        @endif
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Working Radius. :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggTravel']['working_radius'] }}
                                    </p>
                                </div>

                            </li>
                            @else
                            <p class="text-center text-sm  dark:text-gray-400">No Travel Details Found</p>
                            @endif

                        </ul>

                    </div>

                    {{-- Payment Details  --}}
                    <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                        <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                            <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                            </svg>
                            Payment Details
                        </p>
                        <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                            @if (isset($engineer['enggPay']))
                            <li class="pb-2 sm:pb-4 pt-3 text-sm">
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Bank :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggPay']['bank_name'] }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Account Type :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggPay']['account_type'] }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Account Type :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggPay']['account_type'] }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Account No. :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggPay']['account_number'] }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Iban No. :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggPay']['iban'] }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Swift Code :</p>
                                    <p class="font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggPay']['swift_code'] }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Holder Name :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggPay']['holder_name'] }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Bank Address :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggPay']['bank_address'] }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">
                                    <p class=" text-gray-600 dark:text-gray-400 ">Personal Tax Id :</p>
                                    <p class=" font-medium text-gray-900  truncate dark:text-white">
                                        {{ $engineer['enggPay']['personal_tax_id'] }}
                                    </p>
                                </div>
                            </li>
                            @else
                            <p class="text-center text-sm  dark:text-gray-400">No Payment Details Found</p>
                            @endif
                        </ul>
                    </div>

                    {{-- Technical Certification  --}}
                    {{-- <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                        <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                            <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                            </svg>
                            Technical Certification
                        </p>
                        <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                            @if (isset($engineer['enggTechCerty']) && count($engineer['enggTechCerty']) > 0)
                            @foreach ($engineer['enggTechCerty'] as $certy)
                            <li class="pb-2 sm:pb-4 pt-3">
                                <div class="flex items-center space-x-4 rtl:space-x-reverse capitalize">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ str_replace('_', ' ', $certy['certification_type']) }}
                    </p>
                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                        {{ $certy['certification_id'] }}
                    </p>
                    <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                        Issued : {{ $certy['issue_date'] }} | Exp. :
                        {{ $certy['expire_date'] }}
                    </p>
                </div>
                <div class="flex flex-col justify-end items-end gap-1">
                    @if (!empty($certy['certificate_file']) && Storage::exists('public/' . $certy['certificate_file']))
                    <p>
                        <a href="{{ asset('storage/'  . $certy['certificate_file']) }}" target="__blank"
                            class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                            View Doc
                        </a>
                    </p>
                    @else
                    <p class="text-sm text-gray-400 dark:text-gray-500">No document available</p>
                    @endif
                </div>
            </div>
            </li>
            @endforeach
            @else
            <p class="text-center text-sm  dark:text-gray-400">No Education Details Found</p>
            @endif

            </ul>

        </div> --}}

        {{-- Engineer Skills  --}}
        <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
            <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M7.05 4.05A7 7 0 0 1 19 9c0 2.407-1.197 3.874-2.186 5.084l-.04.048C15.77 15.362 15 16.34 15 18a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1c0-1.612-.77-2.613-1.78-3.875l-.045-.056C6.193 12.842 5 11.352 5 9a7 7 0 0 1 2.05-4.95ZM9 21a1 1 0 0 1 1-1h4a1 1 0 1 1 0 2h-4a1 1 0 0 1-1-1Zm1.586-13.414A2 2 0 0 1 12 7a1 1 0 1 0 0-2 4 4 0 0 0-4 4 1 1 0 0 0 2 0 2 2 0 0 1 .586-1.414Z"
                        clip-rule="evenodd" />
                </svg>
                Skills
            </p>
            <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                @if (count($engineer['enggLang']) > 0)

                <li class="pb-2 sm:pb-4 pt-3 text-sm">
                    @foreach ($engineer['enggSkills'] as $skill)
                    <div class="flex items-center justify-between space-x-4 rtl:space-x-reverse">
                        <p class=" text-gray-600 dark:text-gray-400 ">
                            {{ slugToString($skill['name']) }}
                        </p>
                        <p class=" font-medium text-gray-900 truncate dark:text-white">
                            {{ fetchFromMasterDataByKey($skill['level']) }}
                        </p>
                    </div>
                    @endforeach


                </li>
                @else
                <p class="text-center text-sm  dark:text-gray-400">No Skills Found</p>
                @endif

            </ul>

        </div>


    </div>
</div>

<!-- Ticket Tab -->

<div class="hidden rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
    <p class="text-sm text-gray-500 dark:text-gray-400">
    <div class="card-body relative ">

        {{-- data-table  --}}
        <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4">
            <table id="search-table">
                <thead>
                    <tr>
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Sr.
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Ticket Code
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Customer
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Lead & Task
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Date & Time
                            </span>
                        </th>
                        <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Status
                            </span>
                        </th>
                        <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                            <span class="flex items-center">
                                Action
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($engineer['enggTicket']))

                    @foreach ($engineer['enggTicket'] as $ticket)
                    <tr>
                        <td class="capitalize px-6 py-4">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4"> <a href="{{ route('ticket.show', $ticket->id) }}"
                                class="text-decoration hover:dark:text-gray-300 hover:text-gray-800">
                                {{ $ticket?->ticket_code }}</a></td>

                        <td class="px-6 py-4">
                            <div
                                class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">

                                <img class="w-10 h-10 rounded-full border"
                                    src="{{ $ticket['customer']['profile_image'] ? asset('storage/' . $ticket['customer']['profile_image']) : asset('user_profiles/user/user.png') }}"
                                    alt="Rounded avatar">

                                <div class="">
                                    <p class="capitalize leading-4">
                                        {{ $ticket['customer']['name'] }}
                                    </p>
                                    <p class="text-gray-400">{{ $ticket['customer']['email'] }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="capitalize px-6 py-4">
                            <p class="leading-4">Lead Id : {{ $ticket?->lead?->lead_code ?? ' - ' }}</p>
                            <p>Task : {{ $ticket['task_name'] }}</p>
                        </td>

                        <td class="capitalize px-6 py-4">
                            <p class="leading-4">Date : {{ $ticket['ticket_start_date_tz'] }}</p>
                            <p>Time : {{ $ticket['ticket_time_tz'] }}</p>
                        </td>

                        <td class="px-6 py-4">
                            @if ($ticket['status'] === 'inprogress')
                            <x-badge type="success" label="In Progress" class="" />
                            @elseif($ticket['status'] === 'hold')
                            <x-badge type="info" label="On Hold" class="" />
                            @elseif($ticket['status'] === 'break')
                            <x-badge type="warning" label="On Break" class="" />
                            @elseif($ticket['status'] === 'close')
                            <x-badge type="success" label="Close" class="" />
                            @else
                            <x-badge type="purple" label="Not Started" class="" />
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex gap-1 ">
                                <a href="{{ route('ticket.edit', $ticket->id) }}">
                                    <button type="button" title="Edit" data-customer-id="{{ $ticket->id }}"
                                        class="editBtn  text-white bg-gradient-to-r bg-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                        <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                        </svg>
                                        <span class="sr-only">Icon description</span>
                                        {{-- Edit  --}}
                                    </button>
                                </a>

                                <form id="deleteForm" action="{{ route('ticket.destroy', $ticket->id) }}" method="Post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" title="Delete" id='deleteBtn'
                                        class="deleteBtn text-white flex bg-gradient-to-r bg-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700   font-medium rounded-lg text-sm px-5 py-2 text-center">
                                        <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                        {{-- Delete --}}
                                    </button>
                                </form>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                    @else
                    {{-- <tr>
                                                    <td colspan="5">
                                                        Hello
                                                    </td>
                                                </tr>  --}}

                    @endif
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
        @elseif($errors->any())
        <x-toast-message type="danger" message="Oops, Something went wrong, Check the form again!" />
        @endif
    </div>
    </p>
</div>

<!-- Charges Tab -->

<div class="hidden rounded-lg bg-gray-50 dark:bg-gray-800" id="jobs" role="tabpanel" aria-labelledby="job-tab">
    <p class="text-sm text-gray-500 dark:text-gray-400">
    <div class="card-body relative ">

        {{-- data-table  --}}
        {{-- <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4"> --}}

        <form id='myForm' action="{{ route('engineer-jobdetails.update') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            {{-- divider --}}

            <input type="hidden" name="engineer_id" value="{{ $engineer->id }}">

            <div class="inline-flex items-center w-full">
                <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <span
                    class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                    Job Details
                </span>
            </div>

            <div class="grid md:grid-cols-3 gap-3">

                <div class="Jobs">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Job Type<span class="text-red-500"> *</span>
                    </label>
                    <div class="flex gap-4">

                        <div class="flex items-center mb-4">
                            <input id="full_time" type="radio" value="full_time" name="job_type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                {{ old('job_type', isset($engineer) ? $engineer->job_type : '') == 'full_time' ? 'checked' : '' }}>
                            <label for="full_time"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Full
                                Time</label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input id="part_time" type="radio" value="part_time" name="job_type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                {{ old('job_type', isset($engineer) ? $engineer->job_type : '') == 'part_time' ? 'checked' : '' }}>
                            <label for="part_time"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Part
                                Time</label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input id="dispatch" type="radio" value="dispatch" name="job_type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                {{ old('job_type', isset($engineer) ? $engineer->job_type : '') == 'dispatch' ? 'checked' : '' }}>
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

                <div>
                    <x-text-field id="job_title" name="job_title" label="Job Title" placeholder="Enter Job Title"
                        value="{{ old('job_title', isset($engineer) ? $engineer->job_title : '') }}" required />
                    <div>
                        @error('job_title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="job_start_date">
                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white" for="file_input">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input id="job_start_date" name="job_start_date" type="date" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Select date"
                        value="{{ old('job_start_date', isset($engineer) ? $engineer->job_start_date : '') }}">
                    <div>
                        @error('job_start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="grid md:grid-cols-3 gap-3">

                <div class="mt-3">
                    <label for="time" class="block text-sm mb-1 font-medium text-gray-900 dark:text-white">
                        Check-In Time<span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="time" id="check_in_time" name="check_in_time"
                            class="bg-gray-50 border ticketing-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            value="{{ isset($engineer) ? utcToTimezone($engineer?->enggCharge?->check_in_time, $engineer->timezone)->format('H:i') : old('check_in_time') ?? '00:00' }}"
                            required />
                        <div>
                            @error('check_in_time')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="time" class="block text-sm mb-1 font-medium text-gray-900 dark:text-white">
                        Check-Out Time<span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="time" id="check_out_time" name="check_out_time"
                            class="bg-gray-50 border ticketing-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            value="{{ isset($engineer) ?  utcToTimezone($engineer?->enggCharge?->check_out_time, $engineer->timezone)->format('H:i') : old('check_out_time') ?? '00:00' }}"
                            required />
                        <div>
                            @error('check_out_time')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div
                    class="mt-3 vat_no full_time_rate {{ isset($engineer) && $engineer->job_type && $engineer->job_type == 'full_time' ? '  ' : ' hidden ' }} ">
                    <x-input-number id="annual_leaves" name='annual_leaves' label="Allocated Annual Leaves"
                        placeholder="Enter Annual Leaves" class=""
                        value="{{ old('annual_leaves', isset($engineer) && $engineer->enggCharge ? $engineer->enggCharge?->annual_leaves : 0) }}"
                        required />
                    @error('annual_leaves')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="grid md:grid-cols-3 gap-3">

                <div
                    class="mt-3 vat_no full_time_rate {{ isset($engineer) && $engineer->job_type && $engineer->job_type == 'full_time' ? '  ' : ' hidden ' }} ">
                    <x-input-number id="accumulated_leaves" name='accumulated_leaves'
                        label="Till Date Accumulated Leaves" placeholder="Enter Accumulated  Leaves" class=""
                        value="{{ old('accumulated_leaves', isset($engineer) && $engineer->enggCharge ? $engineer->enggCharge?->accumulated_leaves : 0) }}"
                        required />
                    @error('accumulated_leaves')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            {{-- divider --}}

            <div class="inline-flex items-center w-full mt-4">
                <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <span
                    class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                    Costing & Rates
                </span>
            </div>

            <div class="grid grid-cols-3 gap-3">

                @php
                $currency = [
                ['name' => '$ - Dollar', 'value' => 'dollar'],
                ['name' => '€ - Euro', 'value' => 'euro'],
                ['name' => '£ - Pound', 'value' => 'pound'],
                ['name' => 'zł - Zloty', 'value' => 'zloty'],
                ];
                @endphp
                <div class="customer">
                    <label class="text-sm dark:text-white">Select Currency
                        <span class="text-red-500 text-sm">*</span>
                    </label>
                    <x-input-dropdown name="currency_type" id="currency_type" placeholder="Choose Currency" class=""
                        :options="$currency" optionalLabel="name" optionalValue="value"
                        value="{{ old('currency_type', isset($engineer) && $engineer->enggCharge ? $engineer->enggCharge?->currency_type : '') }}"
                        required />
                </div>

                <div
                    class="mt-2 vat_no part_time_rate dispatch_rate {{ isset($engineer) && $engineer->job_type && ($engineer->job_type == 'part_time' || $engineer->job_type == 'dispatch') ? '  ' : ' hidden ' }}">
                    <x-input-number id="hourly_charge" name='hourly_charge' label="Hourly Charge"
                        placeholder="Enter hourly Charge" class=""
                        value="{{ old('hourly_charge', isset($engineer) && $engineer->enggCharge ? $engineer->enggCharge?->hourly_charge : 0) }}" />
                    @error('hourly_charge')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div
                    class="mt-2 vat_no part_time_rate dispatch_rate {{ isset($engineer) && $engineer->job_type && ($engineer->job_type == 'part_time' || $engineer->job_type == 'dispatch') ? '  ' : ' hidden ' }}">
                    <x-input-number id="half_day_charge" name="half_day_charge" label="Half Day Charge"
                        placeholder="Enter half day charge" class=""
                        value="{{ old('half_day_charge', isset($engineer) && $engineer->enggCharge ? $engineer->enggCharge->half_day_charge : 0) }}" />
                    @error('half_day_charge')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div
                    class="mt-2 vat_no part_time_rate dispatch_rate {{ isset($engineer) && $engineer->job_type && ($engineer->job_type == 'part_time' || $engineer->job_type == 'dispatch') ? '  ' : ' hidden ' }}">
                    <x-input-number id="full_day_charge" name="full_day_charge" label="Full Day Charge"
                        placeholder="Enter full day charge" class=""
                        value="{{ old('full_day_charge', isset($engineer) && $engineer->enggCharge ? $engineer->enggCharge->full_day_charge : '') }}" />
                    @error('full_day_charge')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div
                    class="mt-2 vat_no full_time_rate {{ isset($engineer) && $engineer->job_type && $engineer->job_type == 'full_time' ? '  ' : ' hidden ' }}">
                    <x-input-number id="monthly_charge" name='monthly_charge' label="Monthly Charge"
                        placeholder="Enter monthly Charge" class=""
                        value="{{ old('monthly_charge', isset($engineer) && $engineer->enggCharge ? $engineer->enggCharge->monthly_charge : '') }}" />
                    @error('monthly_charge')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- divider --}}
            <div class="inline-flex items-center w-full mt-4">
                <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <span
                    class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                    Extra Work Pay
                </span>
            </div>

            {{-- extra work fields --}}

            <div class="grid grid-cols-3 gap-3">
                <div class="mt-2">
                    <x-input-number id="overtime" name="overtime" label="Over Time (Hourly rate)"
                        placeholder="Enter Over Time"
                        value="{{ old('overtime', isset($engineer) && $engineer->enggExtraPay ? $engineer->enggExtraPay->overtime : '') }}"
                        required step=".01" />
                    @error('overtime')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-2">
                    <x-input-number id="out_of_office_hour" name="out_of_office_hour"
                        label="Out of Office Hour (Hourly rate)" placeholder="Enter Out of Office Hour"
                        value="{{ old('out_of_office_hour', isset($engineer) && $engineer->enggExtraPay ? $engineer->enggExtraPay->out_of_office_hour : '') }}"
                        required step=".01" />
                    @error('out_of_office_hour')viwe
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-2">
                    <x-input-number id="weekend" name="weekend" label="Weekend (Hourly rate)"
                        placeholder="Enter Weekend"
                        value="{{ old('weekend', isset($engineer) && $engineer->enggExtraPay ? $engineer->enggExtraPay->weekend : '') }}"
                        required step=".01" />
                    @error('weekend')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-2">
                    <x-input-number id="public_holiday" name="public_holiday" label="Public Holiday (Hourly rate)"
                        placeholder="Enter Public Holiday"
                        value="{{ old('public_holiday', isset($engineer) && $engineer->enggExtraPay ? $engineer->enggExtraPay->public_holiday : '') }}"
                        required step=".01" />
                    @error('public_holiday')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="">

                <hr class="dark:opacity-20 my-8" />

                {{-- submit form  --}}
                <div class="grid md:grid-cols-4 gap-2">

                    <div class="md:col-span-1  ">
                        <a href="{{ route('engg.index') }}">
                            <button type="button" id='cancelButton'
                                class="text-gray-700 hover:text-white border border-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-center me-2 dark:border-gray-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 justify-center flex items-center w-full focus:ring-4font-medium rounded-lg text-sm py-2.5">
                                Cancel
                            </button>
                        </a>
                    </div>
                    <div>
                        <button type="submit"
                            class="text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            {{ isset($engineer) ? 'Update' : 'Add' }}
                        </button>
                    </div>
                </div>

            </div>

        </form>
        {{-- </div> --}}

        {{-- toast-message component --}}
        @if (session('toast'))
        <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}"
            {{--  error="{{ session('toast')['error'] }}" --}} />
        @elseif($errors->any())
        <x-toast-message type="danger" message="Oops, Something went wrong, Check the form again!" />
        @endif


    </div>
    </p>
</div>

<!-- Leave Tab -->

@if ($engineer->job_type == 'full_time')
<div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="leaves" role="tabpanel" aria-labelledby="leaves-tab">
    <div class=" grid grid-cols-5 gap-4 mb-4">
        <div class="flex p-3   items-center rounded-xl gap-4 bg-white border border-gray-300 shadow-sm">
            <div class="bg-blue-100 text-primary p-2 border-2 border-primary rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                </svg>

            </div>
            <div class="flex  flex-col ">
                <p class="text-[1rem]font-medium">Leave Balance</p>
                <div class="text-[1rem] font-semibold ">{{$dashCounts['freezed_balance']}} <span
                        class="text-[.7rem]">({{$dashCounts['leave_balance']}})</span></div>
            </div>
        </div>

        <div class="flex p-3   items-center rounded-xl gap-4 bg-white border border-gray-300 shadow-sm">
            <div class="bg-[#FEF7D4] text-[#BA951E] p-2 border-2 border-[#BA951E] rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                </svg>

            </div>
            <div class="flex  flex-col ">
                <p class="text-[1rem]font-medium">Pending Leaves</p>
                <span class="text-[1rem] font-semibold ">{{$dashCounts['pending_leaves']}}</span>
            </div>
        </div>

        <div class="flex p-3   items-center rounded-xl gap-4 bg-white border border-gray-300 shadow-sm">
            <div class="bg-[#E5FEE6] text-[#00A854] border-2 border-[#00A854] p-2 rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                </svg>
            </div>
            <div class="flex flex-col ">
                <p class="text-[1rem] font-medium">Approved Leaves</p>
                <span class="text-[1rem] font-semibold ">{{$dashCounts['approved_leaves']}}</span>
            </div>
        </div>

        <div class="flex p-3   items-center rounded-xl gap-4 bg-white border border-gray-300 shadow-sm">
            <div class="bg-[#FFE9E9] text-[#EA0C15] border-2 border-[#EA0C15] p-2 rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                </svg>
            </div>
            <div class="flex flex-col ">
                <p class="text-[1rem] font-medium">Declined Leaves</p>
                <span class="text-[1rem] font-semibold ">{{$dashCounts['declined_leaves']}}</span>
            </div>
        </div>


        <div class="flex p-3   items-center rounded-xl gap-4 bg-white border border-gray-300 shadow-sm">
            <div class="bg-[#E8EFEE] text-[#003AA6] border-2 border-[#003AA6] p-2 rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                </svg>
            </div>
            <div class="flex flex-col ">
                <p class="text-[1rem] text-nowrap font-medium">Total Leaves Applied</p>
                <span class="text-[1rem] font-semibold ">{{$dashCounts['total_leaves']}}</span>
            </div>
        </div>
    </div>
    <div class="p-3 bg-white  shadow-sm rounded-xl">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold mb-4">Leaves</h2>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-[.73rem] font-medium">SR.NO</th>
                        <th class="px-4 py-2 text-[.73rem] font-medium">Start Date</th>
                        <th class="px-4 py-2 text-[.73rem] font-medium">Total Days</th>
                        <th class="px-4 py-2 text-[.73rem] font-medium">Leave Type</th>
                        <!-- <th class4x-1.2 py-1 text-[.73rem] font-medium">Reason</th> -->
                        <th class="px-4 py-2 text-[.73rem] font-medium">Document </th>
                        <th class="px-4 py-2 text-[.73rem] font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$leaves->isEmpty())
                    @foreach ($leaves as $leave)
                    <tr class="text-center border-b last:rounded-b-xl">
                        <td class="px-2 py-1 text-xs">{{ $loop->iteration}}</td>

                        <td class="px-2 py-1 text-xs">
                            {{ \Carbon\Carbon::parse($leave->paid_from_date ?? $leave->unpaid_from_date)->format('d F')}}
                            To
                            {{ \Carbon\Carbon::parse($leave->unpaid_to_date ?? $leave->paid_to_date)->format('d F') }}
                        </td>
                        <td class="px-2 py-1 text-xs">
                            {{ (int)($leave->paid_leave_days ?? 0 + $leave->unpaid_leave_days ?? 0) }}
                        </td>
                        <td class="px-2 py-1 text-xs text-center">
                            <div class="">
                                @if ((int)$leave->paid_leave_days > 0)
                                <div
                                    class="bg-green-100 text-green-500 border  w-fit py-[.15rem] px-[.4rem] rounded-lg inline-block">
                                    {{ $leave->paid_from_date ? 'Paid' : '' }}
                                </div>
                                @endif
                                @if ((int)$leave->unpaid_leave_days > 0)
                                <div
                                    class="mt-2 bg-red-100 text-red-500 border  w-fit py-[.15rem] px-[.4rem] rounded-lg inline-block">
                                    {{ $leave->unpaid_from_date ? 'Unpaid' : '' }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <!-- <td class="px-2 py-1 text-xs text-center">
                                        <div class=" inline-block">
                                            @if (!empty($leave->signed_paid_document))
                                            <a title="Paid Document" href="{{asset('storage')}}/{{$leave->signed_paid_document}}" target="_blank">
                                                <div class="flex items-center gap-3">
                                                    <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                                </div>
                                            </a>
                                            @else
                                            <p>
                                                -
                                            </p>
                                            @endif

                                            @if (!empty($leave->signed_unpaid_document))
                                            <a title="Unpaid Document" href="{{asset('storage')}}/{{$leave->signed_unpaid_document}}" target="_blank">
                                                <div class="flex items-center gap-3">
                                                    <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                                </div>
                                            </a>
                                            @else
                                            <p>
                                                -
                                            </p>
                                            @endif
                                        </div>
                                    </td> -->
                        <td class="px-2 py-1 text-xs text-center">
                            <div class="inline-block">
                                @if ($leave->leave_approve_status === 'approved')
                                {{-- Show signed documents if approved --}}
                                @if (!empty($leave->signed_paid_document))
                                <a title="Sogmed Paid Document"
                                    href="{{ asset('storage') }}/{{ $leave->signed_paid_document }}" target="_blank">
                                    <div class="flex items-center gap-3">
                                        <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                    </div>
                                </a>
                                @endif
                                @if (!empty($leave->signed_unpaid_document))
                                <a title="Signed Unpaid Document"
                                    href="{{ asset('storage') }}/{{ $leave->signed_unpaid_document }}" target="_blank">
                                    <div class="flex items-center gap-3">
                                        <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                    </div>
                                </a>
                                @endif
                                @else
                                {{-- Show unsigned documents if not approved --}}
                                @if (!empty($leave->unsigned_paid_document))
                                <a title="Unsigned Paid Document"
                                    href="{{ asset('storage') }}/{{ $leave->unsigned_paid_document }}" target="_blank">
                                    <div class="flex items-center gap-3">
                                        <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                    </div>
                                </a>
                                @endif

                                @if (!empty($leave->unsigned_unpaid_document))
                                <a title="Unsigned Unpaid Document"
                                    href="{{ asset('storage') }}/{{ $leave->unsigned_unpaid_document }}"
                                    target="_blank">
                                    <div class="flex items-center gap-3">
                                        <img src="/assets/pdf-icon.png" class="w-8 h-8" alt="">
                                    </div>
                                </a>
                                @endif
                                @endif
                            </div>
                        </td>

                        <td class="px-2 py-1 text-xs">
                            @php
                            $status = strtolower($leave->leave_approve_status); // Convert to lowercase for consistency
                            $colors = [
                            'approved' => 'bg-green-100 text-green-500 border-green-500',
                            'pending' => 'bg-yellow-100 text-yellow-500 border-yellow-500',
                            'reject' => 'bg-red-100 text-red-500 border-red-500'
                            ];
                            @endphp

                            <div
                                class="border w-fit py-[.15rem] px-[.4rem] rounded-lg inline-block {{ $colors[$status] ?? 'bg-gray-100 text-gray-500 border-gray-500' }}">
                                {{ ucfirst($status) }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr class="text-center border-b last:rounded-b-xl">
                        <td colspan="8">
                            <p class="p-2">
                                No record found.
                            </p>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>

        </div>
    </div>
</div>
@endif

</div>

<div id="change-status-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Change Leave approval status
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-toggle="change-status-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form id="leave-approval-status-form" action="{{route('leave-status.update')}}" method="POST" class="">
                @csrf
                <div class="grid gap-3 p-4 grid-cols-2">
                    <input type="hidden" name="leave_id" id="leave_id" value="" required="" />
                    @php
                    $status = [
                    ['name' => 'Pending' , 'value' => 'pending'],
                    ['name' => 'Approved' , 'value' => 'approved'],
                    ['name' => 'reject', 'value' => 'reject'],
                    ];
                    @endphp

                    <div class="customer">
                        <label class="text-sm dark:text-white">Status</label>
                        <x-input-dropdown name="leave_approve_status" id="leave_approve_status"
                            placeholder="Leave Approval Status" class="" :options="$status" optionalLabel="name"
                            optionalValue="value" value="" />
                    </div>

                    <hr class="md:col-span-2 dark:opacity-20" />

                    <div class="md:col-span-2 mt-2">
                        <button type="submit" id="btn-update-status"
                            class="text-white w-full inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Update
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
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
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
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
$(document).ready(function() {
    $('.deleteBtn').click(function() {
        const confirmation = confirm(
            'Are you sure you want to delete this customer & related data ?')

        if (confirmation) {
            $('#deleteForm').submit()
        }

    })
})
</script>

<script>
$(document).ready(function() {
    $('input[name="job_type"]').change(() => {
        const selectedJobType = $('input[name="job_type"]:checked').val();
        if (selectedJobType == 'part_time' || selectedJobType == 'dispatch') {
            $('.full_time_rate').addClass('hidden');
        } else {
            $('.full_time_rate').removeClass('hidden');
        }

        if (selectedJobType == 'full_time') {
            $('.part_time_rate').addClass('hidden');
        } else {
            $('.part_time_rate').removeClass('hidden');
        }

    });
})
</script>

<script>
$(document).ready(function() {

    $(document).on('click', '.leave-status-change-btn', function() {
        let leave_id = $(this).data('leave-id');
        let leave_status = $(this).data('leave-status');
        $('#leave_id').val(leave_id);
        $('#leave_status').val(leave_status);
    });

    $(document).on('click', '#btn-update-status', function() {
        var leave_approve_status = $('#leave_approve_status').val();

        console.log('leave_approve_status', leave_approve_status);
        const confirmation = confirm('Are you sure you want to update status?')
        if (confirmation) {
            $('#leave-approval-status-form').submit()
        }
    });

})
</script>


@endsection

</div>
</div>
@endsection