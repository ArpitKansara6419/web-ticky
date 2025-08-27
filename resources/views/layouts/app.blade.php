<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Latest Font Awesome CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css'])

    <!-- Dark mode toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('color-theme') === 'dark' ||
                (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
    @yield('styles')
</head>

<body
    class="font-sans antialiased bg-[#f5f4f7] dark:bg-gray-800"
    x-data="{ 
        expanded: JSON.parse(localStorage.getItem('sidebar_expanded')) ?? true,
        toggleSidebar() { 
            this.expanded = !this.expanded;
            localStorage.setItem('sidebar_expanded', JSON.stringify(this.expanded));
        }
    }">
    <!-- Page Loader -->
    <div id="page-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
        <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    @include('layouts.main-view')

    <div
        :class="expanded ? 'sm:ml-64 ' : 'sm:ml-16 '"
        class="transition-all duration-300 dark:bg-gray-800">
        <div class="pt-4 p-4 rounded-lg bg-[#f5f4f7] dark:bg-gray-800 dark:border-gray-700">
            <nav>
                <div class="grid md:col-span-12">
                    <div
                        class="p-4 mb-4 flex justify-between items-center text-primary shadow-sm  dark:bg-gray-700 dark:text-gray-300 bg-white rounded-xl ">
                        <p class="flex flex-col">
                            Welcome back & have a great day at work!
                            <span class="text-[.75rem]">
                                Last Login:
                                @if(auth()->user()->previous_login_at)
                                {{ utcToTimezone(auth()->user()->previous_login_at, auth()->user()->timezone)->format('d M Y, h:i A') }}
                                @else
                                Never
                                @endif
                                <strong>
                                    {{ auth()->user()->timezone }}
                                    {{ isset(fetchTimezone(auth()->user()->timezone)['gmtOffsetName']) ? "(". fetchTimezone(auth()->user()->timezone)['gmtOffsetName'].")" : '' }}
                                </strong>
                            </span>
                        </p>
                        <div class="flex items-center ms-3">

                            <div class="flex gap-2 items-center">

                                {{-- theme change button  --}}
                                <!-- <button id="theme-toggle" type="button"
                                    class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                    </svg>
                                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                            fill-rule="evenodd" clip-rule="evenodd"></path>
                                    </svg>
                                </button> -->

                                <div class="flex items-center space-x-4">
                                    <label for="theme-toggle" class="relative inline-flex items-center cursor-pointer">
                                        <!-- Hidden checkbox input -->
                                        <input type="checkbox" id="theme-toggle" class="sr-only" />
                                        <!-- Background of the switch -->
                                        <div class="w-14 h-8 bg-gray-200 dark:bg-gray-600 rounded-full"></div>
                                        <!-- Moving dot inside the switch -->
                                        <span class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-200 ease-in-out"></span>

                                        <!-- Dark Mode Icon -->
                                        <svg id="theme-toggle-dark-icon" class="absolute left-2 top-2 w-4 h-4 text-gray-700 dark:text-gray-300 transition-opacity duration-200 opacity-100" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                        </svg>

                                        <!-- Light Mode Icon -->
                                        <svg id="theme-toggle-light-icon" class="absolute right-2 top-2 w-4 h-4 text-gray-700 dark:text-gray-300 transition-opacity duration-200 opacity-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                                        </svg>
                                    </label>
                                </div>

                                {{-- user profile  --}}
                                <button type="button"
                                    class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                    aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-9 h-9 rounded-full"
                                        src="{{ auth()->user()->profile_image ? asset('storage/' .auth()->user()->profile_image) : 'https://flowbite.com/docs/images/people/profile-picture-5.jpg' }}"
                                        alt="user photo">
                                </button>
                                <div class="flex justify-center items-start  pt-2 flex-col leading-4">
                                    <span class="text-[1rem]"> {{ auth()->user()->name }}</span>
                                    <!-- <span class="text-[.75rem]">{{ Auth::user()->role }}</span> -->
                                </div>
                            </div>

                            {{-- dropdown  --}}
                            <div class="z-50 hidden my-5 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                                id="dropdown-user">
                                <div class="px-4 py-3" role="none">
                                    <p class="text-sm text-gray-900 dark:text-white" role="none">
                                        {{ Auth::user()->name }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                        {{ Auth::user()->email }}
                                    </p>
                                </div>
                                <ul class="py-1" role="none">
                                    <li>
                                        <a href="/dashboard"
                                            class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                            role="menuitem">Dashboard</a>
                                    </li>
                                    <!-- <li>
                      <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
                    </li> -->
                                    <li>
                                        <a href="/profile"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                            role="menuitem">Profile</a>
                                    </li>
                                    <li>
                                        <a href="/change-password"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                            role="menuitem">Change Password</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                                role="menuitem">Sign out</a>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                        </div>

                    </div>

                </div>
            </nav>
            <div class="">
                @if(isset($breadcrumbs))
                @include('components.bread-crumbs', ['items' => $breadcrumbs])
                @endif
            </div>
            {{-- below div is for index views           --}}
            <div class="relative mt-4 overflow-x-auto dark:bg-gray-800">
                @yield('content')
            </div>
        </div>
    </div>
    @include("toast")
    @vite(['resources/js/app.js'])
    @yield('scripts')
    <script>
    $(window).on('load', function () {
        setTimeout(function () {
            $('#page-loader').fadeOut(500);
        }, 200);
    });
    </script>
</body>

</html>

<script>
    var themeToggle = document.getElementById('theme-toggle');
    var dot = document.querySelector('.dot');
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    var themeToggleBtn = document.getElementById('theme-toggle');

    // Set initial state based on local storage or system preference
    if (localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggle.checked = true;
        dot.classList.add('translate-x-6'); // move the dot to the right
        themeToggleDarkIcon.classList.add('opacity-0');
        themeToggleLightIcon.classList.add('opacity-100');
    } else {
        themeToggle.checked = false;
        themeToggleDarkIcon.classList.add('opacity-100');
        themeToggleLightIcon.classList.add('opacity-0');
    }

    // Add event listener to handle theme toggle
    themeToggleBtn.addEventListener('change', function() {
        // Toggle the dot's position
        dot.classList.toggle('translate-x-6');

        // Toggle the icon opacity for light/dark mode
        themeToggleDarkIcon.classList.toggle('opacity-100');
        themeToggleDarkIcon.classList.toggle('opacity-0');
        themeToggleLightIcon.classList.toggle('opacity-100');
        themeToggleLightIcon.classList.toggle('opacity-0');

        // Toggle theme based on the checkbox
        if (themeToggle.checked) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }
    });
</script>