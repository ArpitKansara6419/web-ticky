<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="">
        @csrf

        <h5 class="mt-0 mb-4 font-bold text-gray-700 text-[1rem]">
            Welcome Back! Please log in to continue
        </h5>

        <!-- Email Address -->
        <div>
            <!-- <x-input-label   for="email" :value="__('Email')" /> -->
            <Label class="block font-medium text-lg text-gray-700" for="email"> Email </Label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="Enter your email " :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <!-- <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div> -->

        <div class="mt-4 relative">
            <!-- <x-input-label for="password" :value="__('Password')" /> -->
            <Label class="block font-medium text-lg text-gray-700" for="email"> Password </Label>

            <x-text-input id="password" class="block mt-1 w-full pr-10"
                type="password"
                name="password"
                placeholder="Enter your password"
                required autocomplete="current-password" />

            <!-- Eye Toggle Button -->
            <button type="button" id="togglePassword"
                class="mt-8 absolute inset-y-0 top-1/3 right-3 flex items-center text-gray-500">
                <!-- Default: Closed Eye Icon -->
                <svg id="eyeIcon" class="w-5 h-5 text-gray-600 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="m4 15.6 3.055-3.056A4.913 4.913 0 0 1 7 12.012a5.006 5.006 0 0 1 5-5c.178.009.356.027.532.054l1.744-1.744A8.973 8.973 0 0 0 12 5.012c-5.388 0-10 5.336-10 7A6.49 6.49 0 0 0 4 15.6Z" />
                    <path d="m14.7 10.726 4.995-5.007A.998.998 0 0 0 18.99 4a1 1 0 0 0-.71.305l-4.995 5.007a2.98 2.98 0 0 0-.588-.21l-.035-.01a2.981 2.981 0 0 0-3.584 3.583c0 .012.008.022.01.033.05.204.12.402.211.59l-4.995 4.983a1 1 0 1 0 1.414 1.414l4.995-4.983c.189.091.386.162.59.211.011 0 .021.007.033.01a2.982 2.982 0 0 0 3.584-3.584c0-.012-.008-.023-.011-.035a3.05 3.05 0 0 0-.21-.588Z" />
                    <path d="m19.821 8.605-2.857 2.857a4.952 4.952 0 0 1-5.514 5.514l-1.785 1.785c.767.166 1.55.25 2.335.251 6.453 0 10-5.258 10-7 0-1.166-1.637-2.874-2.179-3.407Z" />
                </svg>
            </button>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between ">

            <!-- Remember Me -->
            <div class="flex mt-2 justify-end">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
            <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary-800">
                Forgot password?
            </a>
        </div>



        <div class="w-full">
            <x-primary-button class="w-full mt-8 flex  justify-center">
                {{ __('Log in') }}
            </x-primary-button>
            <!-- <p class="text-sm font-light text-gray-500 dark:text-gray-400 mt-8">
                Don’t have an account yet? <a href="{{ route('register') }}" class="font-medium text-primary-600 text-blue-800 hover:underline dark:text-primary-500">Sign Up</a>
            </p> -->
        </div>

        <!-- New UI Section (After Login Button) -->
        <div class="mt-6 text-center">
            <!-- <p class="text-sm text-gray-600 mb-4">
                New to our platform?
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">
                    Request a Demo today!
                </a>
            </p> -->

            <div class="border-t border-gray-200 pt-4">
                <p class="text-sm font-medium text-gray-700 mb-3">
                    Are you an engineer?
                </p>
                <p class="text-xs text-gray-500 mb-4">
                    Download our app from your app store!
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="#" class="inline-block">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-10">
                    </a>
                    <a href="#" class="inline-block">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-10">
                    </a>
                </div>
            </div>
        </div>

    </form>


    <!-- JavaScript Code -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordField = document.getElementById("password");
            const togglePassword = document.getElementById("togglePassword");
            const eyeIcon = document.getElementById("eyeIcon");

            // Define Open and Closed Eye Icons
            const eyeOpen = `<path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                         <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />`;

            const eyeClosed = `<path d="m4 15.6 3.055-3.056A4.913 4.913 0 0 1 7 12.012a5.006 5.006 0 0 1 5-5c.178.009.356.027.532.054l1.744-1.744A8.973 8.973 0 0 0 12 5.012c-5.388 0-10 5.336-10 7A6.49 6.49 0 0 0 4 15.6Z"/>
                          <path d="m14.7 10.726 4.995-5.007A.998.998 0 0 0 18.99 4a1 1 0 0 0-.71.305l-4.995 5.007a2.98 2.98 0 0 0-.588-.21l-.035-.01a2.981 2.981 0 0 0-3.584 3.583c0 .012.008.022.01.033.05.204.12.402.211.59l-4.995 4.983a1 1 0 1 0 1.414 1.414l4.995-4.983c.189.091.386.162.59.211.011 0 .021.007.033.01a2.982 2.982 0 0 0 3.584-3.584c0-.012-.008-.023-.011-.035a3.05 3.05 0 0 0-.21-.588Z"/>
                          <path d="m19.821 8.605-2.857 2.857a4.952 4.952 0 0 1-5.514 5.514l-1.785 1.785c.767.166 1.55.25 2.335.251 6.453 0 10-5.258 10-7 0-1.166-1.637-2.874-2.179-3.407Z"/>`;

            togglePassword.addEventListener("click", function() {
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    eyeIcon.innerHTML = eyeOpen; // Show Open Eye
                } else {
                    passwordField.type = "password";
                    eyeIcon.innerHTML = eyeClosed; // Show Closed Eye
                }
            });
        });
    </script>
</x-guest-layout>