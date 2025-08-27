@extends('layouts.app')

@section('title', 'Holiday form')

@section('content')

    <div class="">

        <div class="card">

            {{--  card-header  --}}
            <div class="card-header flex justify-between items-center mb-2">
                <h3 class="font-extrabold">
                     Holiday Form
                </h3>                 
                <div class="mb-0">
                    <a href="{{route('holiday.index')}}">                     
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

            <hr class="dark:opacity-20 mt-6 mb-2"/>

            {{--  card-body  --}}
            <div class="card-body relative">
                {{-- Toast Message --}}
                @if(session('toast'))
                    <x-toast-message 
                        type="{{ session('toast')['type'] }}" 
                        message="{{ session('toast')['message'] }}"
                        @if(isset(session('toast')['error'])) 
                            error="{{ session('toast')['error'] }}" 
                        @endif
                    />
                @elseif($errors->any())
                    <x-toast-message 
                        type="danger" 
                        message="Oops, Something went wrong, Check the form again!"
                    />                
                @endif  
            
                {{-- Form --}}
                <form id='myForm' action="{{ route('holiday.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
            
                    <input type="hidden" id="id" name="id" value="{{ $holiday->id ?? old('id') }}" />
            
                    <div class="grid md:grid-cols-2 md:gap-4">  
                        {{-- Title --}}
                        <div>
                            <x-text-field 
                                id="title"
                                name="title"
                                label="Title" 
                                placeholder="Enter Title" 
                                value="{{ old('title', $holiday->title ?? '') }}"  
                                required                      
                            />
                            @error('title')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-0 date">
                            <x-timezone-countries name="country_name" id="country_name" value="{{ old('country_name', $holiday->country_name ?? '') }}"   />
                            @error('country_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-text-field 
                                id="type"
                                name="type"
                                label="Holiday Type" 
                                placeholder="Enter Holiday Type" 
                                value="{{ old('type', $holiday->type ?? '') }}"  
                                required                      
                            />
                            @error('type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
            
                        {{-- Holiday Date --}}
                        <div class="mt-2 date">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                Holiday Date <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="date" 
                                name="date" 
                                {{-- datepicker --}}
                                type="date"
                                min="<?php echo date('Y-m-d') ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date" 
                                value="{{ old('date', $holiday->date ?? '') }}"
                            />
                            @error('date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
            
                        {{-- Note --}}
                        <div>
                            <x-text-field 
                                id="note"
                                name="note"
                                label="Holiday Note" 
                                placeholder="Enter Note" 
                                value="{{ old('note', $holiday->note ?? '') }}"  
                                required                      
                            />
                            @error('note')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
            
                        {{-- Status Toggle --}}
                        <div>
                            <x-status-toggle 
                                name="status"
                                value="{{ old('status', $holiday->status ?? 1) }}" 
                                required
                            />
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
            
                    {{-- Submit Button --}}
                    <div class="grid md:grid-cols-4 gap-2 mt-6">
                        <div>
                            <a href="{{route('holiday.index')}}">     
                            <button 
                                type="button" 
                                class="text-gray-700 hover:text-white border border-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-center me-2 dark:border-gray-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 justify-center flex items-center w-full focus:ring-4font-medium rounded-lg text-sm py-2.5">
                                Cancel
                            </button> 
                            </a>                               
                        </div>
                        <div>
                            <button 
                                type="submit" 
                                class="text-white bg-blue-700 hover:bg-blue-800 w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                {{ isset($holiday) ? 'Update' : 'Add' }}
                            </button>
                        </div>
                    </div> 
                </form>
            </div>
            

        </div>

    </div>

    @section('scripts')
        <script>

            document.getElementById('cancelButton').addEventListener('click', function(){
                document.getElementById('myForm').reset();
                {{--  window.location.href = '{{ route('customers.index') }}';   --}}
            })

        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
        <script>
            
            $(document).ready(function(){
                
                // alert("Welcome");
                

            })
            
        </script>
    @endsection

@endsection