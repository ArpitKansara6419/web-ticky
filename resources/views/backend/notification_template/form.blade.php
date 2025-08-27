@extends('layouts.app')

@section('title', 'Notification Tempalte')

@section('content')

<div class="">

    <div class="card">

        {{-- card-header  --}}
        <div class="card-header flex justify-between items-center mb-2">
            <h3 class="font-extrabold">
                Notification Template
            </h3>
            <!-- <div class="mb-0">
                <a
                    href="{{ route('lead.index') }}">
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
            </div> -->
        </div>

        <hr class="dark:opacity-20 mt-6 mb-2" />

        {{-- card-body  --}}
        <div class="card-body relative">
            {{-- Toast Message --}}
            @if(session('toast'))
            <x-toast-message
                type="{{ session('toast')['type'] }}"
                message="{{ session('toast')['message'] }}"
                @if(isset(session('toast')['error']))
                error="{{ session('toast')['error'] }}"
                @endif />
            @elseif($errors->any())
            <x-toast-message
                type="danger"
                message="Oops, Something went wrong, Check the form again!" />
            @endif

            {{-- Form --}}
            <form id="myForm" action="{{ route('notification_template.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow-xl shadow-gray-300 dark:shadow-gray-900 rounded-xl p-10 max-w-4xl mx-auto">
                @csrf

                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6 text-center">Create Notification Template</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Template Name --}}

                    <input type="hidden" name="id" value="{{ $notification->id ?? '' }}">
                    
                    <div>
                        <x-text-field
                            id="template_name"
                            name="template_name"
                            label="Template Name"
                            placeholder="Enter Template Name"
                            value="{{ old('template_name', $notification->template_name ?? '') }}"
                            required />
                        @error('template_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Title Name --}}
                    <div>
                        <x-text-field
                            id="title"
                            name="title"
                            label="Title"
                            placeholder="Enter Title"
                            value="{{ old('title', $notification->title ?? '') }}"
                            required />
                        @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Subtitle Name --}}
                    <div>
                        <x-text-field
                            id="sub_title"
                            name="sub_title"
                            label="Sub Title"
                            placeholder="Enter Sub Title"
                            value="{{ old('sub_title', $notification->sub_title ?? '') }}"
                            required />
                        @error('sub_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Slug --}}
                    <div>
                        <x-text-field
                            id="slug"
                            name="slug"
                            label="Slug"
                            placeholder="Enter Slug"
                            value="{{ old('slug', $notification->slug ?? '') }}"
                            required />
                        @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description (Full Width) --}}
                    <div class="md:col-span-2">
                        <x-text-area
                            id="description"
                            name="description"
                            label="Description"
                            placeholder="Enter Description"
                            value="{{ old('description', $notification->description ?? '') }}"
                            required />
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status Toggle --}}
                    <div>
                        <x-status-toggle
                            name="status"
                            value="{{ old('status', $notification->status ?? 1) }}"
                            required />
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('notification_template.create') }}"
                        class="px-6 py-2 text-gray-900 dark:text-gray-100 border border-gray-400 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition text-center">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition">
                        {{ isset($notification) ? 'Update' : 'Add' }}
                    </button>
                </div>
            </form>


        </div>
    </div>

</div>

</div>

@section('scripts')
<script>
    document.getElementById('cancelButton').addEventListener('click', function() {
        document.getElementById('myForm').reset();
        // {{--  window.location.href = '{{ route('customers.index') }}';   --}}
    })
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {



    })
</script>
@endsection

@endsection