@extends('layouts.app')
@section('title', 'Customers Index')
@section('content')

<div class="grid">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                Customers
            </h3>
            <div class="text-center">
                <a href="{{ route('customer.create') }}">
                    <button id="drawerBtn"
                        class="text-white bg-primary-light-one focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                        type="button">
                        Add Customer
                    </button>
                </a>
            </div>
        </div>

        {{-- card-body  --}}
        <div class="card-body relative ">

            {{-- data-table  --}}
            <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <table id="search-table">
                    <thead>
                        <tr>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Code
                                </span>
                            </th>
                            <th class="bg-blue-100  dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Customer
                                </span>
                            </th>
                            <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Customer type
                                </span>
                            </th>
                            <!-- <th class="bg-blue-100 dark:bg-gray-900 px-6 py-3">
                                <span class="flex items-center">
                                    Authorise Person
                                </span>
                            </th> -->
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
                        @foreach ($customers as $customer)
                        <tr>
                            <td class="capitalize  text-nowrap px-6 py-4">
                                #{{ $customer['customer_code'] }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <img class="w-10 h-10 rounded-full border"
                                        src="{{ $customer['profile_image'] ? asset('storage/' . $customer['profile_image']) : asset('user_profiles/user/user.png') }}"
                                        alt="Rounded avatar">
                                    <div class="">
                                        <p class="capitalize leading-4">{{ $customer['name'] }}</p>
                                        <p class="text-gray-400 text-xs">{{ $customer['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="capitalize px-6 py-4">
                                <p class="text-gray-800 dark:text-white">{{ $customer['customer_type'] }}</p>
                                @if (isset($customer['company_reg_no']))
                                <p class="text-xs">REG. NO : {{ $customer['company_reg_no'] }}</p>
                                @endif
                            </td>
                            <!-- <td class="capitalize px-6 py-4">
                                <p class="leading-4">{{ $customer['auth_person'] }}</p>
                                <p class="">P : {{ $customer['auth_person_contact'] }}</p>
                            </td> -->
                            <td class="px-8 py-4 text-nowrap">
                                @if ($customer['status'] == 0)
                                <x-badge type="danger" label="In Active" class="" />
                                @else
                                <x-badge type="success" label="Active" class="" />
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex gap-1 ">
                                    @can($ModuleEnum::CUSTOMER_DETAIL->value)
                                    <button type="button" title="View" data-customer-id="{{ $customer->id }}"
                                        id='notesViewBtn'
                                        data-modal-target="static-modal" data-modal-toggle="static-modal"
                                        class="customer-viewBtn text-white bg-gradient-to-r from-indigo-400 via-indigo-400 to-indigo-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-indigo-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                        <svg class="w-5 h-5 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>


                                        <span class="sr-only">Icon description</span>
                                    </button>
                                    @endcan

                                    @can($ModuleEnum::CUSTOMER_LEAD->value)
                                    <a href="{{ route('customer.lead', $customer->id) }}">
                                        <button type="button" title="Lead"
                                            data-customer-id="{{ $customer->id }}"
                                            class="editBtn  text-white bg-gradient-to-r from-blue-400 via-blue-400 to-blue-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-blue-600 font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M5 14v7M5 4.971v9.541c5.6-5.538 8.4 2.64 14-.086v-9.54C13.4 7.61 10.6-.568 5 4.97Z" />
                                            </svg>

                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </a>
                                    @endcan

                                    {{-- Payout Button --}}
                                    @can($ModuleEnum::CUSTOMER_EDIT->value)
                                    <a href="{{ route('customer.edit', $customer->id) }}">
                                        <button type="button" title="Edit"
                                            data-customer-id="{{ $customer->id }}"
                                            class="editBtn  text-white bg-green-400 bg-gradient-to-r from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600 font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </a>
                                    @endcan

                                    @can($ModuleEnum::CUSTOMER_DELETE->value)
                                    <form id="deleteForm_{{$customer->id}}" action="{{ route('customer.destroy', $customer->id) }}"
                                        method="Post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="Delete" id='deleteBtn'
                                            data-customer-id="{{$customer->id}}"
                                            class="deleteBtn text-white flex bg-red-400  from-red-400 via-red-500 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700   font-medium rounded-lg text-sm px-5 py-2 text-center">
                                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endcan

                                </div>

                            </td>

                        </tr>
                        @endforeach
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
            @endif

        </div>

        <!-- Main modal -->
        <div id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-5xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Customer Details
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-6 space-y-6">
                        <!-- Profile Image -->
                        <div class="flex items-center space-x-4">
                            <img class="w-[6rem] h-[6rem] rounded-full object-cover customerImage"
                                src=""
                                onerror="this.onerror=null;this.src='/user_profiles/user/user.png';"
                                alt="Profile Image">
                        </div>

                        <!-- Basic Customer Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 space-y-3">
                            <div class="flex flex-col">
                                <span class="text-md text-gray-500">Code</span>
                                <strong class="font-medium text-md text-primary customerCode"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-md text-gray-500">Type</span>
                                <strong class="font-medium text-md customerType"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-md text-gray-500">Name</span>
                                <strong class="font-medium text-md customerName"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-md text-gray-500">Email</span>
                                <strong class="font-medium text-md customerEmail"></strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-md text-gray-500">Company Reg No</span>
                                <strong class="font-medium text-md companyRegNo">-</strong>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-md text-gray-500">VAT No</span>
                                <strong class="font-medium text-md vatNo"></strong>
                            </div>
                        </div>

                        <!-- Authorized Person Section -->
                        <div class="space-y-3">
                            <div class="relative flex items-center w-full">
                                <hr class="w-full h-px bg-gray-200 border-0 dark:bg-gray-700">
                                <span class="absolute left-1/2 transform -translate-x-1/2 px-3 text-md font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800">
                                    Authorised Person Details
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="authPersonDetails">
                                
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="space-y-3">
                            <div class="relative flex items-center w-full mt-4">
                                <hr class="w-full h-px bg-gray-200 border-0 dark:bg-gray-700">
                                <span class="absolute left-1/2 transform -translate-x-1/2 px-3 text-md font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800">
                                    Document
                                </span>
                            </div>
                            <div id="customerDocuments" class="grid grid-cols-1 md:grid-cols-3 space-y-3">
                                <!-- Filled dynamically -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


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
                    var customerId = $(this).data('customer-id');
                    const confirmation = confirm(
                        'Are you sure you want to delete this customer & related data ?')

                    if (confirmation) {
                        $(`#deleteForm_${customerId}`).submit();
                    }

                })
            })

            function renderCustomerDocuments(documents) {
                const container = document.getElementById('customerDocuments');
                container.innerHTML = ''; // Clear previous content

                // Add each document
                if (documents.length <= 0) {
                    container.innerHTML += `
                        <div class="w-full ">
                            <strong class="font-medium text-md">No Documents</strong>
                        </div>`;
                    return;
                }
                documents.forEach(doc => {
                    container.innerHTML += `
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Name</span>
                                    <strong class="font-medium text-md">${doc.title ?? '-'}</strong>
                                </div>
                              
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Expiry</span>
                                    <strong class="font-medium text-md">${doc.doc_expiry ?? '-'}</strong>
                                </div>

                                  <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Doc Link</span>
                                    <a href="${window.location.origin}/storage/${doc.doc}" target="_blank" class="text-blue-600 hover:underline break-all">
                                        <img src="/assets/pdf-icon.png" class="w-8 h-8" alt=""> 
                                    </a>
                                </div>
                            `;
                });
            }

            function setText(selector, value) {
                $(selector).text(value || '-');
            }

            function setAuthorisedPersonDetails(authorisedPersons) {
                const container = document.getElementById('authPersonDetails');
                container.innerHTML = ''; // Clear previous content

                // Add each document
                if (authorisedPersons.length <= 0) {
                    container.innerHTML += `
                        <div class="w-full ">
                            <strong class="font-medium text-md">No Details are available.</strong>
                        </div>`;
                    return;
                }
                authorisedPersons.forEach(person => {
                    var personEmail = person.person_email ? person.person_email : "-";
                    container.innerHTML += `
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Name</span>
                                    <strong class="font-medium text-md authPerson">${person.person_name}</strong>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Email</span>
                                    <strong class="font-medium text-md authPersonEmail">${personEmail}</strong>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Contact</span>
                                    <strong class="font-medium text-md authPersonContact">${person.person_contact_number}</strong>
                                </div>
                            `;
                });
            }

            $('.customer-viewBtn').on('click', function() {
                const customerId = $(this).data('customer-id');
                console.log('customerId', customerId);

                $.ajax({
                    url: `/get-customer-details/${customerId}`,
                    type: 'GET',
                    success: function(response) {

                        const c = response.customer;
                        console.log('customerData:', response);

                        setText('.customerCode', '#' + c.customer_code);
                        setText('.customerName', c.name);
                        setText('.customerType', c.customer_type);
                        setText('.companyRegNo', c.company_reg_no);
                        setText('.vatNo', c.vat_no);
                        setText('.customerEmail', c.email);
                        // setText('.authPerson', c.auth_person);
                        // setText('.authPersonEmail', c.auth_person_email);
                        // setText('.authPersonContact', c.auth_person_contact);
                        setText('.customerAddress', c.address);
                        setText('.customerStatus', c.status);
                        renderCustomerDocuments(c.customer_docs);

                        setAuthorisedPersonDetails(c.authorised_persons);

                        // Profile Image
                        if (c.profile_image) {
                            // Ensure you're using the correct URL format
                            $('.customerImage').attr('src', `/storage/${c.profile_image}`);
                        } else {
                            $('.customerImage').attr('src', '/user_profiles/user/user.png');
                        }


                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        </script>

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


    </div>

</div>
@endsection