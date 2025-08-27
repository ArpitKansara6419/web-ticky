<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">

    <!-- <div class="min-h-screen flex items-center justify-center sm:pt-0 bg-blue-900" style="background-color: #206487;"> -->
    <div class="min-h-screen min-w-screen flex items-center justify-center sm:pt-0 bg-no-repeat bg-cover" style="background-image: url(assets/steptodown.com969544.jpg);">

        <!-- <div class="w-1/2 h-screen hidden lg:block">
            <img src="assets/new-ticky-auth-bg.png" alt="Placeholder Image" class="object-cover w-full h-full">
        </div> -->

        <div class="w-full  px-6 lg:px-36 md:px-20 sm:px-12 py-8  flex justify-center" >
            <div class="w-full sm:max-w-md mt-6 shadow-xl bg-white overflow-hidden sm:rounded-3xl rounded-3xl px-9 pb-8 pt-4">
                <div class="mb-0 flex justify-end">
                    @if (Str::contains(request()->url(), 'change-password'))
                    <a href="{{ route('dashboard') }}">
                        <button
                            type="button"
                            class="text-white flex items-center justify-center bg-gradient-to-r from-slate-400 via-slate-500 to-slate-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-slate-300 dark:focus:ring-slate-800 shadow-lg shadow-slate-500/50 dark:shadow-lg dark:shadow-teal-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6v12m8-12v12l-8-6 8-6Z" />
                            </svg>
                            Back
                        </button>
                    </a>
                    @endif
                </div>
                <div class="mb-1.5">
                    <a href="/" class="flex gap-3  justify-center">
                        <img src="/assets/logo_02.png" class="w-48" alt="Ticky Logo">
                    </a>

                    <hr class="mt-4 border border-gray-300 ">
                    @if (Str::contains(request()->url(), 'change-password'))
                    <div class="w-full  pt-4 font-serif  font-semibold text-gray-700 text-2xl"> Change Password</div>
                    @else
                    <div class="w-full  pt-4 font-serif  font-semibold text-gray-700 text-2xl"> Log in</div>
                    @endif
                </div>
                {{ $slot }}
            </div>
        </div>

    </div>

</body>

</html>