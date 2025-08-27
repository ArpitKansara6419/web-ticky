@extends('layouts.app')

@section('title', 'Admins Index')

@section('content')

    <div class="grid">

        <div class="card">

            {{--  card-header  --}}
            <div class="card-header flex justify-between items-center">
                <h3 class="font-extrabold">
                    Admins
                </h3> 
                <div class="text-center">
                    <button 
                        id ='drawerBtn'
                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 " 
                        type="button" 
                        data-drawer-target="drawer-right-example" 
                        data-drawer-show="drawer-right-example" 
                        data-drawer-placement="right" 
                        aria-controls="drawer-right-example"
                        data-drawer-body-scrolling="true"
                    >
                        Add Customer
                    </button>
                </div>
            </div>

            {{--  card-body  --}}
            <div class="card-body relative ">

                {{--  data-table  --}}
                <table id="search-table">
                    <thead>
                        <tr>
                            <th>
                                <span class="flex items-center">
                                   Customer
                                </span>
                            </th>
                            {{--  <th>
                                <span class="flex items-center">
                                    Email
                                </span>
                            </th>  --}}
                            <th>
                                <span class="flex items-center">
                                    Contact
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Status
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center">
                                    Action
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin )
                            <tr>
                                <td> 
                                    <div class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                        <img class="w-10 h-10 rounded-full" src="/user_profiles/user/user.png" alt="Rounded avatar">
                                        <div class="">
                                            <p class="capitalize">{{$admin['name']}}</p>
                                            <p class="text-gray-400">{{$admin['email']}}</p>
                                        </div>
                                    </div>
                                </td>
                                {{--  <td></td>  --}}
                                <td>{{$admin['contact']}}</td>
                                <td>
                                    @if ($admin['status'] == 0)
                                       <x-badge type="danger" label="Inactive" class="" />
                                    @else
                                        <x-badge type="success" label="Active" class="dark:bg-green-500" />
                                    @endif
                                </td> 

                                <td>                                  
                                    <div class="flex gap-1 ">
                                        {{--  <a href="{{route('admin.edit', $admin->id)}}">  --}}
                                            <button                                                
                                                data-admin-id = "{{ $admin->id }}"
                                                type="button" 
                                                class="editBtn text-white bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600 shadow-lg dark:shadow-lg dark:shadow-green-400/40 font-medium rounded-lg text-sm px-5 py-2 text-center mb-2 flex"
                                            >
                                                <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                                </svg>  
                                                <span class="sr-only">Icon description</span>
                                                Edit
                                            </button>
                                        {{--  </a>  --}}

                                        <form id='deleteForm' action="{{route('admin.destroy', $admin->id)}}" method="Post">
                                            @csrf
                                            @method('DELETE')                                    
                                            <button id='deleteBtn' type="submit" class="text-white flex bg-gradient-to-r from-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700   font-medium rounded-lg text-sm px-5 py-2 text-center mb-2">
                                                <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                                </svg>      
                                                Delete                                     
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

                {{-- toast-message component --}}  
                @if(session('toast'))
                    <x-toast-message 
                        type="{{ session('toast')['type'] }}" 
                        message="{{ session('toast')['message'] }}"
                        {{--  error="{{ session('toast')['error'] }}"  --}}
                    />
                @endif             

                {{--  create-sidebar  --}}
                <!-- drawer component -->
                <div id="drawer-right-example" class="fixed top-14 pb-14  right-0 z-40 h-screen max-h-full  p-4 overflow-y-auto transition-transform translate-x-full bg-white w-80 dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-right-label">
                    
                    <h5 id="drawer-label" class="inline-flex gap-2 items-center mb-6 text-base font-semibold text-gray-500 uppercase dark:text-gray-400">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>  
                        {{isset($user) ? 'Edit Profile' : 'Add Admin'}}                      
                        
                    </h5>                    

                    <button type="button" data-drawer-hide="drawer-right-example" aria-controls="drawer-right-example" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close menu</span>
                    </button>

                    {{--  create-or-update Admin  --}}
                    <form method="POST" id='adminForm' action="{{ route('admin.store') }}" class="mb-6">
                        @csrf

                        <input type="hidden" id="id" name="id" value="{{optional($user)->id}}">
                        
                        <div class="mb-4">            
                            <x-text-field 
                                id="name" 
                                name="name" 
                                label="Name" 
                                placeholder="Enter Name" 
                                class="" 
                                value="{{ isset($user) ? $user->name : old('name') }}"
                            />    
                            <span id="error-name" class="text-red-500 text-sm"></span>                
                        </div>

                        <div class="mb-4">                          
                            <x-text-field 
                                id="email" 
                                name="email" 
                                label="Email" 
                                placeholder="Enter Email Name" 
                                class="" 
                                value="{{ isset($user) ? $user->email : old('email') }}"
                            />
                            <span id="error-email" class="text-red-500 text-sm"></span>                
                        </div>

                        <div class="max-w-sm mb-4 mx-auto">
                            <label for="contact" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone number:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 18">
                                        <path d="M18 13.446a3.02 3.02 0 0 0-.946-1.985l-1.4-1.4a3.054 3.054 0 0 0-4.218 0l-.7.7a.983.983 0 0 1-1.39 0l-2.1-2.1a.983.983 0 0 1 0-1.389l.7-.7a2.98 2.98 0 0 0 0-4.217l-1.4-1.4a2.824 2.824 0 0 0-4.218 0c-3.619 3.619-3 8.229 1.752 12.979C6.785 16.639 9.45 18 11.912 18a7.175 7.175 0 0 0 5.139-2.325A2.9 2.9 0 0 0 18 13.446Z"/>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    id="contact" 
                                    name='contact'
                                    aria-describedby="helper-text-explanation"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                    {{--  pattern="0000000000"   --}}
                                    placeholder="1234567890" 
                                    {{--  required   --}}
                                    value="{{ isset($user) ? $user->contact : old('contact') }}" 
                                />
                            </div>   
                            <span id="error-contact" class="text-red-500 text-sm"></span>                      
                        </div>
                        
                        {{--  status button  --}}
                        <div>
                            <x-status-toggle 
                            activeLabel="Active" 
                            inactiveLabel="Inactive" 
                            :value="isset($user) ?? $user->status" />    
                            <span id="error-status" class="text-red-500 text-sm"></span>                       
                        </div>                    
                        
                        <hr class="mt-5 opacity-30" />                                                             
                        
                        <button id='submitBtn' type="button" class="text-white mt-6 justify-center flex items-center bg-blue-700 hover:bg-blue-800 w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                           {{isset($user) ? 'Update' : 'Add'}}
                        </button>
                    </form>                  

                </div>

            </div>

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

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script>
                $(document).ready(() => {

                    //Handel admin From Submit
                    $('#submitBtn').click(()=>{

                        const formData = {

                            id              : $('#id').val(),
                            name            : $('#name').val(),
                            email           : $('#email').val(), 
                            contact         : $('#contact').val(),                            
                            status          : $('#status').val()

                        }

                        axios.post('{{route('admin.store')}}', formData)
                            .then((res)=>{

                                console.log('response from admin store ==>', res)

                                if(res.data.success){
                                    window.location.reload();
                                }

                            })
                            .catch((error)=>{

                                console.log('error from admin store ==>', error)

                                if(error.response?.status == 422){

                                    const errors = error.response.data.errors;
                                    for(field in errors ){
                                        $(`#error-${field}`).text(errors[field][0]);
                                    }

                                }else{
                                    alert(error.response?.data.message || 'An error occurred. Please try again.');
                                }

                            })


                    })

                    //Handel Edit admin details with drawer
                    $('.editBtn').click((e)=>{

                        var selectedAdmin = $(e.currentTarget).data('admin-id');
                        console.log('response oselectedAdmin ==> ', selectedAdmin);


                        axios.get(`/admin/${selectedAdmin}/edit`)
                        .then((res) => {

                            console.log('response of fetched admin data for edit ==> ', res);

                            const user = res.data.user;

                            $('#drawerBtn').click();

                            $('#id').val(user.id);
                            $('#contact').val(user.contact);
                            $('#email').val(user.email);
                            $('#name').val(user.name);
                            $('#status').val(user.status); 


                            $('#drawer-label').text('Edit Customer'); 
                            $('#submitBtn').text('Update'); 

                            {{--  setStatus(user.status);  --}}
                            initializeStatus();
                            console.log('called in index page setstatus');  



                        }).catch((error)=>{

                            console.log('error of fetched admin data while edit ==> ', error);

                        })
                    })

                    //Handel delete button
                    $('#deleteBtn').click((e)=>{
                        event.preventDefault(); 

                        const isConfirmed  = confirm('Are you sure you really want to delete selected admin user ?');
                        
                        if(isConfirmed){
                            $('#deleteForm').submit();
                        }
                    });

                    //for new admin add after edit
                    $('#drawerBtn').click(()=>{

                        $('#adminForm')[0].reset()
                        $('#drawer-label').text('Add Customer');
                        $('#submitBtn').text('Add');
                    })

                });
            </script>
    

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
           
        </div>

    </div>
@endsection

