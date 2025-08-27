@extends('layouts.app')

@section('title', 'Master Data')

@section('content')

    <div class="grid">

        <div class="card">

            {{--  card-header  --}}
            <div class="card-header flex justify-between items-center">
                <h3 class="font-extrabold">
                    Master Data
                </h3>                 
                <div class="text-center">
                    <a href="{{route('master.create')}}">
                        <button 
                            id="drawerBtn"
                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 " 
                            type="button" 
                        >
                            Add Master Data
                        </button>
                    </a>
                </div>
            </div>

            {{--  card-body  --}}
            <div class="mt-4 relative">

                {{--  data-table  --}}
                <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4" style="">
                    <table id="search-table" class="">
                        <thead>
                            <tr class="">
                                <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                    <span class="flex items-center">
                                        Key Name
                                    </span>
                                </th>                              
                                <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                    <span class="flex items-center">
                                        Label Name
                                    </span>
                                </th>
                                <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                    <span class="flex items-center">
                                        Type
                                    </span>
                                </th>
                                <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
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
                            @foreach ($masterData as $data )
                                <tr class="capitalize">                            
                                    <td class="px-6 py-4">{{$data['key_name']}}</td>
                                    <td class="px-6 py-4">{{$data['label_name']}}</td>
                                    <td class="px-6 py-4">{{$data['type']}}</td>
                                    <td class="px-6 py-4">
                                        @if ($data['status'] == 0)
                                        <x-badge type="danger" label="Inactive" class="" />
                                        @else
                                            <x-badge type="success" label="Active" class="" />
                                        @endif
                                    </td> 

                                    <td class="px-6 py-4">
                                        <div class="flex gap-1 ">                        

                                        <a href="{{route('master.edit', $data->id)}}">
                                            <button type="button" title="Edit" class="editBtn  text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex"
                                            >
                                                <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                                </svg>                                               
                                                  
                                                <span class="sr-only">Icon description</span>
                                                {{--  Edit  --}}
                                            </button>
                                        </a>

                                        <form id="deleteForm" title="Delete" action="{{route('master.destroy', $data->id)}}" method="Post">
                                            @csrf
                                            @method('DELETE')                                    
                                            <button type="button" id='deleteBtn' class="text-white flex bg-gradient-to-r from-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700   font-medium rounded-lg text-sm px-5 py-2 text-center">
                                                <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                                </svg>     
                                        
                                            </button>
                                        </form>                                   

                                        </div>                                 
                                        
                                    </td> 

                                </tr>
                            @endforeach
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
                @endif             

            </div>

            <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
            <script> 

                if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
                    const dataTable = new simpleDatatables.DataTable("#search-table", {
                        searchable: true,
                        sortable: true,
                        header:true,
                        perPage: 10,
                        paging:true,
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
                
            </script>
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
            <script>
                $(document).ready(function(){

                    $('#deleteBtn').click(function(){
                        
                        const confirmation = confirm('Are you sure you want to delete this customer & related data ?')

                        if(confirmation){
                            $('#deleteForm').submit()
                        }   

                    })
                })
                
            </script>

        </div>
    </div>
@endsection

