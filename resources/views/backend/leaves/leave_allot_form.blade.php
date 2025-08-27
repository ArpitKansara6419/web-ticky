@extends('layouts.app')

@section('title', 'Leave Allot ')

@section('content')

<div class="">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header mb-3">
            <h3 class="font-extrabold">
                Leave Allot 
            </h3>
            <h4 class="text-[1rem] font-semi text-gray-600 dark:text-gray-400">Allot employee yearly leaves</h4>
        </div>

        <hr class="dark:opacity-20 mt-6 mb-2" />
         

        {{-- card-body  --}}
        <div class="card-body relative">
            {{-- toast-message component --}}
            @if (session('toast'))
            <x-toast-message
                type="{{ session('toast')['type'] }}"
                message="{{ session('toast')['message'] }}" />
            @endif

            {{-- Form --}}
            <form id='myForm' action="{{ route('leave-allot.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="id" name="id" value="{{ $holiday->id ?? old('id') }}" />

                <div class="grid md:grid-cols-2 md:gap-4">

                    <div class="mt-2 ">
                        <x-input-number id="year" name='year' label="Year"
                            placeholder="Enter Year" class=""
                            value="{{ isset($lead) ? $lead->year : old('year') }}"
                            {{--  required  --}} />
                    </div>

                    <div class="mt-2 ">
                        <x-input-number id="leave" name='leave' label="Leave"
                            placeholder="Enter number of leavs" class=""
                            value="{{ isset($lead) ? $lead->leave : old('leave') }}"
                            {{--  required  --}} />
                    </div>
                </div>

                {{-- Submit Button --}}
                <hr class="dark:opacity-20  my-4" />
                <div class="grid md:grid-cols-4 gap-2 mt-6">
                    <div>
                        <button
                            type="reset"
                            class="text-gray-700 hover:text-white border border-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-center me-2 dark:border-gray-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 justify-center flex items-center w-full focus:ring-4font-medium rounded-lg text-sm py-2.5">
                            Cancel
                        </button>
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        // alert("Welcome");


    })
</script>
@endsection

@endsection