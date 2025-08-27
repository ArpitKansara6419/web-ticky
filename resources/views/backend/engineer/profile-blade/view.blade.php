@extends('layouts.app')

@section('title', 'Engineers Profile')

@section('content')

    <div class="">

        <div class="card">

            {{--  card-header  --}}
            <div class="card-header flex justify-between items-center">
                <h3 class="font-extrabold">
                    Engineer Details
                </h3>                 
                <div class="text-center">
                    <a href="{{route('engg.index')}}">                     
                        <button 
                            type="button" 
                            class="text-white flex items-center justify-center bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                        >    
                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6v12m8-12v12l-8-6 8-6Z"/>
                            </svg>
                          
                            Back
                        </button>
                    </a>
                   
                </div>
            </div>

            {{--  page-body  --}}
             
            <div class="mb-4 mt-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab" data-tabs-target="#profile-main" type="button" role="tab" aria-controls="profile-main" aria-selected="false">PROFILE</button>
                    </li>
                    {{--  <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">TRAVEL</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">PAYMENT</button>
                    </li>  --}}
                    <li role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">TICKETS</button>
                    </li>

                


                </ul>
            </div>
            
            <div id="default-tab-content">

                <!-- Profile Tab -->
                <div class="hidden p-4 rounded-lg dark:bg-gray-800" id="profile-main" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="grid md:grid-cols-6 gap-4">
                        <div class="md:col-span-4 p-6 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border dark:border-gray-700">
                           
                            <!-- Profile Info -->
                            <div class="flex items-center gap-4 p-1">
                                <img class="w-24 h-24 border rounded-full" src="/user_profiles/user/user.png" alt="User profile picture">
                                <div class="text-base">
                                    <p class="capitalize text-2xl font-medium text-gray-900 dark:text-white leading-7">{{$engineer['first_name']}} {{$engineer['last_name']}}</p>
                                    <p class="text-gray-500">{{$engineer['email']}}</p>
                                </div>
                            </div>

                            <!-- Profile Details -->
                            <div class="grid md:grid-cols-3 gap-2 p-2 text-sm mt-4 text-gray-600 dark:text-gray-400">
                                <p>Contact: {{$engineer['contact']}}</p>
                                <p>Gender: {{ $engineer['gender'] ?? 'N/A' }}</p>
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
                                <p>Created At: {{ \Carbon\Carbon::parse($engineer['created_at'])->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <!-- Language Skills -->
                        <div class="md:col-span-2 border dark:border-gray-800 dark:bg-gray-700 rounded-lg p-4">
                            <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 dark:text-white">
                                <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m13 19 3.5-9 3.5 9m-6.125-2h5.25M3 7h7m0 0h2m-2 0c0 1.63-.793 3.926-2.239 5.655M7.5 6.818V5m.261 7.655C6.79 13.82 5.521 14.725 4 15m3.761-2.345L5 10m2.761 2.655L10.2 15"/>
                                </svg>                                  
                                Language Skills
                            </p>
                            
                            @if (isset($engineer['enggLang']))
                                <div class="mt-6">
                                    @foreach ($engineer['enggLang'] as $lang)
                                    
                                        <div class="text-sm mt-2 text-gray-600 dark:text-gray-300 border-gray-600 border-opacity-20 dark:border-opacity-80 border border-1 rounded-lg p-4">

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
                                                        <svg class="w-4 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                        </svg>                                                      
                                                    @else                                                           
                                                        <svg class="w-4 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        

                        </div>

                        {{--  Documnets  --}}
                        <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                            <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                                <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z"/>
                                </svg>   
                                Documents
                            </p>
                            <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                                @if (isset($engineer['enggDoc']) && !empty($engineer['enggDoc']) && count($engineer['enggDoc']) > 0)
                                    @foreach ($engineer['enggDoc'] as $doc )
                                        <li class="pb-2 sm:pb-4 pt-2">
                                            <div class="flex items-center space-x-4 rtl:space-x-reverse">                                               
                                               
                                                <div class="flex-1 min-w-0">                                                    
                                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                        {{$doc['document_label']}}
                                                    </p>
                                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                        {{$doc['document_type']}}
                                                    </p>
                                                    <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                                       Issued : {{$doc['issue_date']}} | Exp. : {{$doc['expiry_date']}} 
                                                    </p>
                                                </div>
                                                <div class="inline-flex p-2 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                                    <a href="{{ asset('public/engineer_docs/'.$doc['media_file']) }}" target="__blank">
                                                        doc_link
                                                    </a>                                                  
                                                </div>
                                            </div>
                                        </li> 
                                    @endforeach
                                @else
                                    <p class="text-center text-sm  dark:text-gray-400">No Documents Found</p>
                                @endif
                                                             
                            </ul>
 
                        </div>

                        {{--  education  --}}
                        <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                            <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                                <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v13H7a2 2 0 0 0-2 2Zm0 0a2 2 0 0 0 2 2h12M9 3v14m7 0v4"/>
                                </svg>                                    
                                Education
                            </p>
                            <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                                @if (isset($engineer['enggEdu']) && count($engineer['enggEdu']) > 0)
                                    @foreach ($engineer['enggEdu'] as $edu )
                                        <li class="pb-2 sm:pb-4 pt-3">
                                            <div class="flex items-center space-x-4 rtl:space-x-reverse">                                               
                                               
                                                <div class="flex-1 min-w-0">                                                    
                                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                        {{$edu['degree_name']}}
                                                    </p>
                                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                        {{$edu['university_name']}}
                                                    </p>
                                                </div>
                                                <div class="flex flex-col justify-end items-end gap-1">
                                                    <p>
                                                        <a href="{{ asset('public/engineer_docs/'.$doc['media_files']) }}" target="__blank" class="inline-flex px-2 py-1 border rounded-lg items-center text-sm border-blue-500 dark:border-blue-700 font-semibold text-blue-800 dark:text-blue-600">
                                                            doc_link
                                                        </a> 
                                                    </p> 
                                                    <p class="text-xs dark:text-gray-400">Issued : {{$edu['issue_date']}} </p>                                                
                                                </div>
                                            </div>
                                        </li> 
                                    @endforeach
                                @else
                                    <p class="text-center text-sm  dark:text-gray-400">No Education Details Found</p>
                                @endif
                                                             
                            </ul>
 
                        </div>

                        {{--  Payment Details  --}}
                        <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                            <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                                <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z"/>
                                </svg>                                                                      
                                Payment Details
                            </p>
                            <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                                @if (isset($engineer['enggPay']) )
                                   
                                    <li class="pb-2 sm:pb-4 pt-3 text-sm">
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Bank :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggPay']['bank_name']}}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Account Type :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggPay']['account_type']}}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Account Type :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggPay']['account_type']}}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Account No. :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggPay']['account_number']}}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Iban No. :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggPay']['iban']}}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Swift Code :</p>                                                   
                                            <p class="font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggPay']['swift_code']}}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Holder Name :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggPay']['holder_name']}}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Bank Address :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggPay']['bank_address']}}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Personal Tax Id :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggPay']['personal_tax_id']}}
                                            </p>                                                  
                                        </div>
                                        
                                        
                                    </li> 
                                   
                                @else
                                    <p class="text-center text-sm  dark:text-gray-400">No Payment Details Found</p>
                                @endif
                                                             
                            </ul>
 
                        </div>

                        {{--  Travel Details  --}}
                        <div class="md:col-span-2 p-4 border border-1 dark:border-gray-700 rounded-lg">
                            <p class="capitalize flex gap-2 text-lg font-bold mt-2 text-gray-600 mb-4 dark:text-white">
                                <svg class="w-6 h-6 text-gray-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 12A2.5 2.5 0 0 1 21 9.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 1 0 5V17a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                </svg>                                                                                                        
                                Travel Details
                            </p>
                            <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                                @if (isset($engineer['enggTravel']) )
                                    <li class="pb-2 sm:pb-4 pt-3 text-sm">
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Driving Licence :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggTravel']['driving_license'] == 0 ? 'No' : 'Yes' }}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Own Vehicle :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggTravel']['own_vehicle'] == 1 ? 'Yes' : 'No'}}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Type of Vehicle :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white capitalize">
                                                {{$engineer['enggTravel']['type_of_vehicle'] ?
                                                    implode(', ', json_decode($engineer['enggTravel']['type_of_vehicle'], true)) : ''
                                                }}
                                            </p>                                                  
                                        </div>
                                        <div class="flex items-center justify-between  space-x-4 rtl:space-x-reverse">                                              
                                            <p class=" text-gray-600 dark:text-gray-400 ">Working Radius. :</p>                                                   
                                            <p class=" font-medium text-gray-900  truncate dark:text-white">
                                                {{$engineer['enggTravel']['working_radius']}}
                                            </p>                                                  
                                        </div>                                   
                                        
                                    </li> 
                                   
                                @else
                                    <p class="text-center text-sm  dark:text-gray-400">No Payment Details Found</p>
                                @endif
                                                             
                            </ul>
 
                        </div>

                    </div>
                </div>

                <!-- Other Tabs -->

                {{--  <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Dashboard Content</p>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Settings Content</p>
                </div>  --}}

                
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <div class="card-body relative ">

                            {{--  data-table  --}}
                            <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4">
                                <table id="search-table">
                                    <thead>
                                        <tr>
                                            <th class="bg-blue-100 dark:bg-gray-900">
                                                <span class="flex items-center">
                                                   Id
                                                </span>
                                            </th>
                                            <th class="bg-blue-100  dark:bg-gray-900">
                                                <span class="flex items-center">
                                                    Customer
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 dark:bg-gray-900">
                                                <span class="flex items-center">
                                                    Lead & Task
                                                </span>
                                            </th>
                                            <th class="bg-blue-100 dark:bg-gray-900">
                                                <span class="flex items-center">
                                                    Date & Time
                                                </span>
                                            </th>                                           
                                            <th class="bg-blue-100  dark:bg-gray-900">
                                                <span class="flex items-center">
                                                    Status
                                                </span>
                                            </th>
                                            <th class="bg-blue-100  dark:bg-gray-900">
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
                                                    <td class="capitalize">
                                                        {{$ticket['id']}}                               
                                                    </td>
                                                    <td> 
                                                        <div class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                
                                                            <img class="w-10 h-10 rounded-full border" 
                                                            src="{{ $ticket['customer']['profile_image'] ? asset('storage/profiles/' . $ticket['customer']['profile_image']) : asset('user_profiles/user/user.png') }}" 
                                                            alt="Rounded avatar">
                                                    
                                                            <div class="">
                                                                <p class="capitalize leading-4">{{ $ticket['customer']['name']}}</p>
                                                                <p class="text-gray-400">{{ $ticket['customer']['email']}}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                
                                                    <td class="capitalize">                                       
                                                        <p class="leading-4">Lead Id : {{$ticket['lead_id']}}</p>                             
                                                        <p>Task : {{$ticket['task_name']}}</p>                             
                                                    </td>
                
                                                    <td class="capitalize">                                       
                                                        <p class="leading-4">Date : {{$ticket['task_date']}}</p>
                                                        <p>Time :  {{$ticket['task_time']}}</p>                          
                                                    </td>            
                                                
                                                    <td>
                                                        Pending
                                                    </td> 
                
                                                    <td> 
                                                        <div class="flex gap-1 ">
                                                        <a href="{{route('ticket.edit', $ticket->id)}}">
                                                            <button                                            
                                                                type="button"
                                                                title="Edit"
                                                                data-customer-id="{{ $ticket->id }}"  
                                                                class="editBtn  text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600 font-medium rounded-lg text-sm px-5 py-2 text-center  flex"
                                                            >
                                                                <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                                                </svg>  
                                                                <span class="sr-only">Icon description</span>
                                                                {{--  Edit  --}}
                                                            </button>
                                                        </a>
                
                                                        <form id="deleteForm" action="{{route('ticket.destroy', $ticket->id)}}" method="Post">
                                                            @csrf
                                                            @method('DELETE')                                    
                                                            <button type="button"  title="Delete" id='deleteBtn' class="deleteBtn text-white flex bg-gradient-to-r from-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700   font-medium rounded-lg text-sm px-5 py-2 text-center">
                                                                <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                                                </svg>      
                                                                {{--  Delete --}}
                                                            </button>
                                                        </form> 
                                                        </div>                                 
                                                        
                                                    </td> 
                
                                                </tr>
                                            @endforeach

                                        @else

                                            <tr>
                                                <td colspan="5">
                                                    Hello
                                                </td>
                                            </tr>

                                        @endif
                                    </tbody>
                                    {{--  @php
                                        print_r($errors->all());
                                    @endphp  --}}
                                
                                </table>
                            </div>
            
                            {{-- toast-message component --}}  
                            @if(session('toast'))
                                <x-toast-message 
                                    type="{{ session('toast')['type'] }}" 
                                    message="{{ session('toast')['message'] }}"
                                    {{--  error="{{ session('toast')['error'] }}"  --}}
                                />
                            @elseif($errors->any())
                                <x-toast-message 
                                    type="danger" 
                                    message="Oops, Something went wrong, Check the form again!"
                                />                
                            @endif  
                               
            
                        </div>
                    </p>
                </div>

          

            </div>

            @section('scripts')
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                <style>
                    .datatable-wrapper .datatable-top {
                        display: flex;                  
                        justify-content: content-between; /* Align items to the start */
                    }            
                    .custom-button {
                        order: 3; /* Set order for your custom button */
                        margin-left: 10px; /* Add some spacing */
                    }
                </style>
                <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
            <script> 

                if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
                    const dataTable = new simpleDatatables.DataTable("#search-table", {
                        searchable: true,
                        sortable: true,
                        header:true,
                        perPage: 5,
                        paging:true,
                    });
                }            
                
            </script>
            <script>
                $(document).ready(function(){

                    $('.deleteBtn').click(function(){
                        
                        const confirmation = confirm('Are you sure you want to delete this customer & related data ?')

                        if(confirmation){
                            $('#deleteForm').submit()
                        }   

                    })
                })
                
            </script>

            @endsection

        </div>
    </div>
@endsection

