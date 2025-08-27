@extends('layouts.app')

@section('title', 'Master Data form')

@section('content')

    <div class="">

        <div class="card">

            {{--  card-header  --}}
            <div class="card-header flex justify-between items-center mb-2">
                <h3 class="font-extrabold">
                     Master Data Form
                </h3>                 
                <div class="mb-0">
                    <a href="{{route('master.index')}}">                     
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
            <div class="card-body relative ">

                {{-- toast-message component --}}  
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
           
                <form id='myForm' action="{{route('master.store')}}" method="POST" enctype="multipart/form-data" >
                    @csrf

                    <div class="">

                        <input type='hidden' id="id" name="id" value="{{isset($masterD) ? $masterD->id : ""}}"/>
                        
                        <div class="grid md:grid-cols-2 md:gap-4">  
                            
                            <div>
                                <x-text-field 
                                    id="label_name"
                                    name="label_name"
                                    label="Label Name" 
                                    placeholder="Enter Label Name" 
                                    class="" 
                                    value="{{ isset($masterD) ? $masterD->label_name : old('label_name') }}"  
                                    required                      
                                />
                            </div>
                            
                            <div>
                                <x-text-field 
                                    id="key_name"
                                    name="key_name"
                                    label="Key Name" 
                                    placeholder="Enter Key Name" 
                                    class="" 
                                    value="{{ isset($masterD) ? $masterD->key_name : old('key_name') }}"  
                                    required                      
                                />
                            </div>

                            <!-- <div>
                                <x-text-field 
                                    id="type"
                                    name="type"
                                    label="Type" 
                                    placeholder="Enter data type" 
                                    class="" 
                                    value="{{ isset($masterD) ? $masterD->type : old('type') }}"  
                                    required                      
                                />
                            </div> -->


                            @php
                                $dataTypes = [
                                    ['name' => 'Document Type' , 'value' => 'document_type'],
                                    ['name' => 'Vehicles Type' , 'value' => 'vehicle_type'],
                                    ['name' => 'Industry Type', 'value' => 'industry_type'],
                                    ['name' => 'Experiance Type', 'value' => 'experiance_type'],
                                    ['name' => 'Technical Skills', 'value' => 'technical_skills'],
                                    ['name' => 'Technical Skills Level', 'value' => 'technical_skills_level'],
                                    ['name' => 'Spoken Languages', 'value' => 'spoken_languages'],
                                    ['name' => 'Spoken Language Proficiency', 'value' => 'spoken_language_proficiency'],
                                    ['name' => 'Spoken Language Level', 'value' => 'spoken_language_level'],
                                    ['name' => 'Industry Experience', 'value' => 'industry_experience'],
                                    ['name' => 'Industry Experience Level', 'value' => 'industry_experience_level'],
                                    ['name' => 'Travel vehicle', 'value' => 'travel_vehicle'],
                                    ['name' => 'Technical Certification Level', 'value' => 'technical_certifications_level'],
                                    ['name' => 'Technical Certification', 'value' => 'technical_certification'],
                                    ['name' => 'Right To Work', 'value' => 'right_to_work'], 
                                    ['name' => 'Gender', 'value' => 'gender']
                                ]
                            @endphp

                            <div class="customer">
                                <label class="text-sm dark:text-white">Type</label>
                                <x-input-dropdown 
                                    name="type" 
                                    id="type"                                  
                                    placeholder="Data Types"                              
                                    class=""
                                    :options="$dataTypes" 
                                    optionalLabel="name" 
                                    optionalValue="value" 
                                    value="{{ isset($masterD) ? $masterD->type : old('type') }}" 
                                />  
                            </div>
                            
                            {{--  <x-text-field 
                                id="extra"
                                name="extra"
                                label="extra" 
                                placeholder="Enter data type" 
                                class="" 
                                value="{{ isset($masterD) ? $masterD->type : old('type') }}"  
                                required                      
                            />  --}}

                            <div class="">                              
                                <x-status-toggle 
                                    value="{{ isset($customer) ? $masterD->status : null }}" 
                                    required
                                />                              
                            </div>

                        </div>
                          
                        <hr class="dark:opacity-40 mt-4 " />
                        <div class="grid md:grid-cols-4 gap-2 mt-6">

                            <div class="md:col-span-1  ">
                                <a href="{{route('master.index')}}">                                     
                                <button type="button" id='cancelButton' class="text-gray-700 hover:text-white border border-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-center me-2 dark:border-gray-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 justify-center flex items-center w-full focus:ring-4font-medium rounded-lg text-sm py-2.5">
                                    Cancel
                                </button>         
                                </a>                       
                            </div>
                            <div>
                                <button type="submit" class="text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    {{isset($masterD) ? 'Update' : 'Add'}}
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

            document.getElementById('cancelButton').addEventListener('click', function(){
                document.getElementById('myForm').reset();
                {{--  window.location.href = '{{ route('customers.index') }}';   --}}
            })

        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
        <script>
            
            $(document).ready(function(){
                
                // alert("Welcome");
                const leadId = $('#lead_id').val(); // Get the value of the hidden field

                if (leadId) {
                    $('#customer_id').attr('disabled', true); // Disable dropdown if editing
                }

                $('#label_name').on('change', function() {
                    var inputValue = $(this).val();
                    inputValue = inputValue.toLowerCase().replace(/\s+/g, '_');
                    $('#key_name').val(inputValue);
                });

            })
            
        </script>
    @endsection

@endsection