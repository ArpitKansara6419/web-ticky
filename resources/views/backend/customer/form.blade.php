@extends('layouts.app')

@section('title', 'Customers form')

@section('content')

<div class="">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center mb-2">
            <h3 class="font-extrabold">
                {{ isset($customer) ? ' Edit ' : ' Add ' }} Customer
            </h3>
            <div class="mb-0">
                <a href="{{ route('customer.index') }}">
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
                error="{{ session('toast')['error'] }}" />
            @endif

            <form id='myForm' action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="">

                    <input type='hidden' id="id" name="id"
                        value="{{ isset($customer) ? $customer->id : '' }}" />

                    <div class="Customer">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Customer Type<span class="text-red-500"> *</span>
                        </label>
                        <div class="flex gap-4">
                            <div class="flex items-center mb-4">
                                <input id="customer_type_company" type="radio" value="company" name="customer_type"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    {{ old('customer_type', isset($customer) ? $customer->customer_type : '') == 'company' ? 'checked' : '' }}>
                                <label for="customer_type_company"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Company</label>
                            </div>
                            <div class="flex items-center mb-4">
                                <input id="customer_type_freelancer" type="radio" value="freelancer"
                                    name="customer_type"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    {{ old('customer_type', isset($customer) ? $customer->customer_type : '') == 'freelancer' ? 'checked' : '' }}>
                                <label for="customer_type_freelancer"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Freelancer</label>
                            </div>
                        </div>
                        <div class="text-red-500">
                            @error('customer_type')
                            {{ $message }}
                            @enderror
                        </div>

                    </div>


                    <div class="grid md:grid-cols-3 gap-2 ">

                        <div class="mt-2">
                            <x-text-field id="name" name="name" label="Name"
                                placeholder="Enter customers name" class=""
                                value="{{ isset($customer) ? $customer->name : old('name') }}" required />
                        </div>


                        <div class="mt-2 company_reg_no">
                            <x-text-field id="company_reg_no" name='company_reg_no' label="Company Registartion No."
                                placeholder="Enter company reg. no." class=""
                                value="{{ isset($customer) ? $customer->company_reg_no : old('company_reg_no') }}" />
                        </div>

                        <div class="mt-2 vat_no">
                            
                                <label for="vat_number" >
                                    VAT Number
                                </label>
                                <input 
                                    type="number" 
                                    id="vat_number" 
                                    name="vat_number" 
                                    placeholder="Enter VAT number" 
                                    value="{{ isset($customer) ? $customer->vat_no : old('vat_no') }}" 
                                    class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 "
                                    oninput="validity.valid||(value='');"
                                    step="1"
                                />
                        </div>

                        <div class="md:col-span-2 mt-2">
                            <x-text-field id="address" name="address" label="Address" placeholder="Enter address"
                                class="" value="{{ isset($customer) ? $customer->address : old('address') }}" />
                        </div>

                        <div class="mt-2">
                            <x-text-field id="email" name="email" label="Account Email"
                                placeholder="Enter company email" class=""
                                value="{{ isset($customer) ? $customer->email : old('email') }}" required />
                        </div>

                        <div class="md:col-span-1 mt-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                                for="profile_image">Upload Profile Picture</label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                id="profile_image" name="profile_image" type="file">
                            @error('profile_image')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <x-status-toggle
                                name="status"
                                activeLabel="Active"
                                inactiveLabel="Inactive"
                                :value="isset($customer) ? $customer->status : 1" required />
                        </div>

                        @if (isset($customer->profile_image))
                        <div class="md:col-span-2 flex items-center gap-4 md:mt-4">
                            <label class="text-sm">Current Profile :</label>
                            <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="Profile Image"
                                class="w-14 h-14 rounded-full border border-1">
                        </div>
                        @endif

                    </div>

                    {{-- divider  --}}
                    <div class="inline-flex items-center w-full mt-4">
                        <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                        <span
                            class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">Authorised
                            Person Details</span>
                    </div>

                    <div id="simple-clone" class="demo-wrap">
                        @isset($customer->authorisedPersons)
                            @foreach ($customer->authorisedPersons as $exist)
                            <div class="">
                                <div class="toclone cloneya grid md:grid-cols-5 gap-2">
                                    <div class="mt-2">
                                        <x-text-field id="auth_person" name="auth_person[]" label="Person Name"
                                            placeholder="Enter person name" class=""
                                            value="{{ isset($customer) ? $exist->person_name : '' }}"
                                            required />
                                    </div>

                                    <div class="mt-2 auth_person_email">
                                        <x-text-field id="auth_person_email" name="auth_person_email[]" label="Person Email"
                                            placeholder="Enter person email" class=""
                                            value="{{ isset($customer) ? $exist->person_email : '' }}"
                                            required />
                                    </div>

                                    <div class="mt-2">
                                        <x-text-field id="auth_person_contact" name="auth_person_contact[]" label="Person Contact"
                                            placeholder="Enter person contact" class=""
                                            value="{{ isset($customer) ? $exist->person_contact_number : '' }}"
                                            required />
                                    </div>
                                    <a href="#" class="clone text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 h-11 w-full m-auto mt-[30px]">ADD</a>
                                    <a href="#" class="delete text-white justify-center flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 h-11 w-full m-auto mt-[30px]">DELETE</a>
                                </div>
                            </div>
                                    
                            @endforeach
                        @endisset
                        @if(!isset($customer->authorisedPersons))
                        <div class="">
                            <div class="toclone cloneya grid md:grid-cols-5 gap-2">
                                <div class="mt-2">
                                    <label for="auth_person" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                        Person Name <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        id="auth_person"
                                        name="auth_person[]"
                                        placeholder="Enter Person Name"
                                        required
                                    />
                                    <!-- <span id="auth_person-error" class="invalid-feedback"></span> -->
                                </div>

                                <div class="mt-2 auth_person_email">
                                    <label for="auth_person_email" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                        Person Email <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        id="auth_person_email"
                                        name="auth_person_email[]"
                                        placeholder="Enter Person Name"
                                        required
                                    />
                                    <!-- <span id="auth_person_email-error" class="invalid-feedback"></span> -->
                                </div>

                                <div class="mt-2">
                                    <label for="auth_person_contact" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                        Person Contact <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        id="auth_person_contact"
                                        name="auth_person_contact[]"
                                        placeholder="Enter Person Contact"
                                        required
                                    />
                                    <!-- <span id="auth_person_contact-error" class="invalid-feedback"></span> -->
                                </div>
                                <a href="#" class="clone text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 h-11 w-full m-auto mt-[30px]">ADD</a>
                                <a href="#" class="delete text-white justify-center flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 h-11 w-full m-auto mt-[30px]">DELETE</a>
                            </div>
                            <!-- <div class="toclone cloneya grid md:grid-cols-5 gap-2">
                                <div class="mt-2">
                                    <x-text-field id="auth_person[]" name="auth_person[]" label="Person Name"
                                        placeholder="Enter person name" class=""
                                        value="{{ isset($customer) ? $customer->auth_person : '' }}"
                                        required />
                                </div>

                                <div class="mt-2 auth_person_email">
                                    <x-text-field id="auth_person_email[]" name="auth_person_email[]" label="Person Email"
                                        placeholder="Enter person email" class=""
                                        value="{{ isset($customer) ? $customer->auth_person_email : '' }}"
                                        required />
                                </div>

                                <div class="mt-2">
                                    <x-text-field id="auth_person_contact[]" name="auth_person_contact[]" label="Person Contact"
                                        placeholder="Enter person contact" class=""
                                        value="{{ isset($customer) ? $customer->auth_person_contact : '' }}"
                                        required />
                                </div>
                                <a href="#" class="clone text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 h-11 w-full m-auto mt-[30px]">ADD</a>
                                <a href="#" class="delete text-white justify-center flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 h-11 w-full m-auto mt-[30px]">DELETE</a>
                            </div> -->
                            
                        </div>
                        @endif
                    </div>


                    <!-- <div class="grid md:grid-cols-3 gap-2">
                        <div class="mt-2">
                            <x-text-field id="auth_person" name="auth_person" label="Person Name"
                                placeholder="Enter person name" class=""
                                value="{{ isset($customer) ? $customer->auth_person : old('auth_person') }}"
                                required />
                        </div>

                        <div class="mt-2 auth_person_email">
                            <x-text-field id="auth_person_email" name="auth_person_email" label="Person Email"
                                placeholder="Enter person email" class=""
                                value="{{ isset($customer) ? $customer->auth_person_email : old('auth_person_email') }}"
                                required />
                        </div>

                        <div class="mt-2">
                            <x-text-field id="auth_person_contact" name="auth_person_contact" label="Person Contact"
                                placeholder="Enter person contact" class=""
                                value="{{ isset($customer) ? $customer->auth_person_contact : old('auth_person_contact') }}"
                                required />
                        </div>
                    </div> -->

                    {{-- divider  --}}
                    <div class="inline-flex items-center w-full mt-4">
                        <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                        <span
                            class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">Upload
                            Documents</span>
                    </div>

                    {{-- add document  --}}
                    <div class="grid md:grid-cols-4 gap-2" id="documents-container">

                        <div class="mt-2">
                            <x-text-field id="title" name="document[0][title]" label="Title"
                                placeholder="Enter document title" class=""
                                value="{{ isset($customer) ? $customer->title : old('title') }}" />
                        </div>

                        <div class="md:col-span-1 mt-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                                for="file_input">Select Document</label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                id="doc_ref" name="document[0][doc_name]" type="file">
                        </div>

                        <div class="mt-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                                for="file_input">Document Expiry Date</label>
                            <input id="doc_exp_date" name="document[0][doc_exp_date]" {{--  datepicker  --}}
                                type="date" min="<?php echo date('Y-m-d'); ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date"
                                value="{{ old('doc_exp_date', $input['doc_exp_date'] ?? '') }}">
                        </div>

                        <div class="md:mt-8">
                            <button type="button" id='addDocBtn'
                                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm w-full md:w-1/2 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                Add New Document
                            </button>
                        </div>

                    </div>

                </div>

                {{-- customer documnets  --}}
                <div class="">

                    @if (@isset($customer['customerDocs']))

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                        <div class="max-w-full overflow-x-auto">
                            <table
                                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Title
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Document
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Expiry Date
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($customer['customerDocs']) && count($customer['customerDocs']) > 0)
                                    @foreach ($customer['customerDocs'] as $doc)
                                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                        <td class="px-6 py-4">
                                            {{ $doc['title'] }}
                                        </td>
                                        <th scope="row" class="px-6 py-4 font-medium text-blue-700 whitespace-nowrap dark:text-white">
                                            <a href="{{ asset('storage/' . $doc['doc']) }}" target="_blank">{{ $doc['doc'] }}</a>
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $doc['doc_expiry'] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="#" data-doc-id="{{ $doc['id'] }}" class="deleteDocBtn font-medium text-red-600 dark:text-red-500 hover:underline">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 border-t text-center text-gray-500 dark:text-gray-400">
                                            No documents available
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>

                            </table>
                        </div>
                    </div>

                    @endif

                    <hr class="dark:opacity-20 mt-4 mb-4" />

                    {{-- submit form  --}}
                    <div class="grid md:grid-cols-4 gap-2">

                        <div class="md:col-span-1  ">
                            <a href="{{ route('customer.index') }}">
                                <button id='cancelButton' type="button"
                                    class="text-gray-700 hover:text-white border border-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-center me-2 dark:border-gray-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 justify-center flex items-center w-full focus:ring-4font-medium rounded-lg text-sm py-2.5">
                                    Cancel
                                </button>
                            </a>
                        </div>
                        <div>
                            <button type="submit"
                                class="text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                {{ isset($customer) ? 'Update' : 'Add' }}
                            </button>
                        </div>
                    </div>

                </div>

            </form>
        </div>

    </div>

</div>



@endsection

@section('scripts')
<script>
    document.getElementById('cancelButton').addEventListener('click', function() {
        document.getElementById('myForm').reset();
        {{--window.location.href = '{{ route('customers.index ') }}';--}}
    })
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
{!! JsValidator::formRequest(App\Http\Requests\CustomerStoreRequest::class, "#myForm") !!}
<script src="{{ asset('js/jquery-cloneya.js') }}"></script>
<script>
    $('#simple-clone').cloneya({
        maximum: 10,
        minimum: 1
    }).on('maximum.cloneya', function (e, limit, toclone) {
        var limitmsg = 'No more than ' + limit + ' clones for you!'
        alert(limitmsg);
    })
    .on('minimum.cloneya', function (e, limit, toclone) {
        var limitmsg = 'No less than ' + limit + ' clones for you!'
        alert(limitmsg);
    });
    $('form').on('submit', function (e) {
        let valid = true;
        $('.cloneya').each(function () {
            let person = $(this).find('[name="auth_person[]"]').val();
            let email = $(this).find('[name="auth_person_email[]"]').val();
            let contact = $(this).find('[name="auth_person_contact[]"]').val();

            if($("input[name='customer_type']:checked").val() == 'company')
            {
                if (!person || !email || !contact) {
                    valid = false;
                }
            }else if($("input[name='customer_type']:checked").val() == 'freelancer')
            {
                if (!person || !contact) {
                    valid = false;
                }
            }
            
        });
        if (!valid) {
            e.preventDefault();
            showErrorToast('Authorised Person Details must be filled.');
        }
    });
    $('#simple-clone').on('after_clone.cloneya', function(event, toclone, newclone) {
        // var index = $('.clone').length + 1;

        newclone.find('span.invalid-feedback').remove();

        // var index = $('.clone').length + 1; // current index

        /*newclone.find('input, select, textarea').each(function() {
            var name = $(this).attr('name');
            if (name) {
                var newName = name.replace(/\[\d*\]/, '[' + index + ']');
                $(this).attr('name', newName);
            }
        });*/
        
    });
    $(document).ready(function() {

        //form fields base on customer type
        function handleCustomerTypeChange() {

            const selectedCustomer = $('input[name="customer_type"]:checked').val();

            if (selectedCustomer === 'company') {

                $('.company_reg_no, .vat_no, .auth_person_email').show();
                $('#auth_person_email').attr('required', true);
                // $('#company_reg_no').attr('required', true);
                // $('#vat_no').attr('required', true);

            } else if (selectedCustomer === 'freelancer') {

                $('.company_reg_no, .vat_no, .auth_person_email').hide();
                $('#company_reg_no').removeAttr('required');
                $('#vat_no').removeAttr('required');
                $('#auth_person_email').removeAttr('required');
            }

        }

        handleCustomerTypeChange();

        $('input[name="customer_type"]').change(() => {
            handleCustomerTypeChange()
        });

        //add documnet button             
        let docCount = 1;

        $('#addDocBtn').click(function() {
            let newField = `
                        <div class="document-wrapper grid md:grid-cols-4 gap-3 mt-0">


                             <div class="mt-2">
                                <x-text-field 
                                    id="title"
                                    name="document[${docCount}][title]"
                                    label="Title" 
                                    placeholder="Enter document title" 
                                    class="" 
                                    value="{{ isset($customer) ? $customer->title : old('title') }}"  
                                    required                      
                                />
                            </div>  

                            
                        
                            <div class="md:col-span-1 mt-2 document-group">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white" for="doc_ref">Select Document</label>
                                <input 
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                                    name="document[${docCount}][doc_name]" 
                                    type="file"
                                >
                            </div>                

                            <div class="mt-2">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Document Expiry Date</label>
                                <input 
                                    id="doc_exp_date"
                                    name="document[${docCount}][doc_exp_date]" 
                                    // datepicker                                                
                                    type="date" 
                                    min="<?php echo date('Y-m-d'); ?>"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                    placeholder="Select date"
                                    value="{{ old('doc_exp_date', $input['doc_exp_date'] ?? '') }}"
                                >
                            </div>

                            <div class='mt-8'>
                                <button type="button"  title="Delete" id='deleteBtn' class="remove-doc-btn text-white flex bg-gradient-to-r from-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700   font-medium rounded-lg text-sm px-5 py-2 text-center">
                                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                    </svg>      
                                    Remove
                                </button>
                            </div>          

                        </div>
                    `;

            $('#documents-container').after(newField);

            docCount++;
        });

        $(document).on('click', '.remove-doc-btn', function() {
            $(this).closest('.document-wrapper').remove();
        });

        // Handle Delete document
        $('.deleteDocBtn').click(function(event) {
            event.preventDefault(); // Prevent default anchor action

            let $this = $(this); // Store reference to the clicked button
            let docId = $this.data('doc-id');
            let deleteUrl = '/document-delete/';

            if (confirm('Are you sure you want to delete this document?')) {
                $.ajax({
                    url: deleteUrl,
                    method: 'DELETE',
                    data: {
                        doc_id: docId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $this.closest('tr').remove(); // Remove the row correctly
                            alert('Document deleted successfully!');
                        } else {
                            alert('Failed to delete the document!');
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the document!');
                    }
                });
            }
        });

    })

    const input = document.getElementById('vat_number');
    input.addEventListener('input', function () {
        this.value = this.value.replace(/[^\d]/g, '');
    });
</script>
@endsection