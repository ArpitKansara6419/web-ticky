@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Update Profile</h1>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Section: Basic Information -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Basic Information</h2>

                    <!-- Username -->
                    <div class="mt-4">
                        <x-input-label for="name" :value="__('Username')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <!-- Section: Profile Picture -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Profile Picture</h2>

                    <!-- Profile Image -->
                    <div class="mt-4">
                        <x-input-label for="profile_image" :value="__('Profile Image')" />
                        <input id="profile_image" class="block mt-1 w-full" type="file" name="profile_image" accept="image/*" />
                        <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />

                        <!-- Display existing profile image -->
                        @if ($user->profile_image)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="w-20 h-20 rounded-full shadow-md">
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section: Personal Details -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Personal Details</h2>

                    <!-- Phone -->
                    <div class="mt-4">
                        <x-input-label for="contact" :value="__('Phone Number')" />
                        <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" value="{{ old('contact', $user->contact) }}" />
                        <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                    </div>

                    <!-- Address -->
                    <div class="mt-4">
                        <x-input-label for="address" :value="__('Address')" />
                        <textarea id="address" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" name="address">{{ old('address', $user->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Date of Birth -->
                    <div class="mt-4">
                        <x-input-label for="dob" :value="__('Date of Birth')" />
                        <x-text-input id="dob" class="block mt-1 w-full" type="date" name="dob" value="{{ old('dob', $user->dob) }}" />
                        <x-input-error :messages="$errors->get('dob')" class="mt-2" />
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end mt-6">
                    <x-primary-button>
                        {{ __('Update Profile') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection