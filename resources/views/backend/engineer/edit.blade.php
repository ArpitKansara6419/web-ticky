@extends('layouts.app')

@section('title', 'customers Add')

@section('content')

    <div class="">

        <div class="card">

            {{--  card-header  --}}
            <div class="card-header flex justify-between items-center mb-2">
                <h3 class="font-extrabold">
                    Edit Engineer
                </h3>
                <div class="mb-0">
                    <a href="#">
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

            {{--  card-body  --}}
            <div class="card-body relative ">

                {{-- toast-message component --}}
                @if (session('toast'))
                    <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}"
                        @if (isset(session('toast')['error'])) error="{{ session('toast')['error'] }}" @endif />
                @elseif($errors->any())
                    <x-toast-message type="danger" message="Oops, Something went wrong, Check the form again!" />
                @endif
                {{--  {{$errors}}  --}}

                <form id='myForm' action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="inline-flex items-center w-full mt-4">
                        <hr class="w-full h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                        <span
                            class="text-sm absolute pr-3 font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800 left-0">
                            Costing & Rates
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="mt-2 vat_no">
                            <x-input-number id="hourly_charge" name='hourly_charge' label="Hourly Charge" placeholder="Enter hourly Charge" class="" />
                        </div>

                        <div class="mt-2 vat_no">
                            <x-input-number id="half_day_charge" name='half_day_charge' label="Half Day Charge" placeholder="Enter half day Charge" class="" />
                        </div>

                        <div class="mt-2 vat_no">
                            <x-input-number id="full_day_charge" name='full_day_charge' label="Full Day Charge" placeholder="Enter full day Charge" class="" />
                        </div>

                        <div class="mt-2 vat_no">
                            <x-input-number id="monthly_charge" name='monthly_charge' label="Monthly Charge" placeholder="Enter monthly Charge" class="" />
                        </div>
                    </div>

                    <div class="">

                        <hr class="dark:opacity-20 mt-4 mb-4" />

                        {{--  submit form  --}}
                        <div class="grid md:grid-cols-4 gap-2">

                            <div class="md:col-span-1  ">
                                <button type="button" id='cancelButton'
                                    class="text-gray-700 hover:text-white border border-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-center me-2 dark:border-gray-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 justify-center flex items-center w-full focus:ring-4font-medium rounded-lg text-sm py-2.5">
                                    Cancel
                                </button>
                            </div>
                            <div>
                                <button type="submit"
                                    class="text-white justify-center flex items-center bg-blue-700 hover:bg-blue-800 w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    {{ isset($ticket) ? 'Update' : 'Add' }}
                                </button>
                            </div>
                        </div>

                    </div>

                </form>
            </div>

        </div>

    </div>



@section('scripts')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>



@endsection

@endsection
