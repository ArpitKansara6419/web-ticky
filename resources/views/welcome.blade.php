<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Title</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .anton-regular {
            font-family: 'Anton', sans-serif;
        }
        .lime{
            color:#f9ef23
        }
        .bg-lime{
            background-color:#f9ef23
        }
    </style>
</head>
<body class="antialiased"
    style="background-image: url('user_profiles/general/welcome3.png'); 
           background-size: cover; 
           background-repeat: no-repeat; 
           background-position: center;"
        >
    
    <div class="min-h-screen flex flex-col items-center justify-center">
        
        <div class="flex justify-center w-full md:justify-end p-6">
            @if (Route::has('login'))
                <div class="">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                            class="font-semibold text-black p-2 text-sm px-6 rounded-sm bg-lime">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-black p-2 text-sm px-6 mr-1 rounded-sm bg-lime">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="font-semibold text-black p-2 text-sm px-6 rounded-sm bg-lime">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            @endif  
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full p-4 md:pl-12 md:pb-12 " style="height: 90vh;">
            <div class="col-span-1 p-4 flex flex-col justify-center items-center md:items-start md:pl-12">
                <p class="text-5xl md:text-8xl font-bold tracking-wider anton-regular italic text-white ">
                    PLAY<span class="lime anton-regular italic ">TIME</span>               
                </p>
                <p class="text-bold text-white text-base md:text-xl mt-10 text-center md:text-left">
                    Introducing an innovative sports facility management system designed to revolutionize how sports activities like box cricket are booked and managed. This cutting-edge platform empowers users to effortlessly reserve time slots for their favorite sports while providing administrators with powerful tools to optimize facility operations.
                </p>
                <p class="mt-14">
                    <a href="{{ route('register') }}">
                        <span class="p-2 md:p-4 px-4 md:px-8 font-bold uppercase" style="background-color: #f9ef23">
                            Learn More
                        </span>
                    </a>
                </p>
            </div>
            <div class="col-span-1 p-4">
                <!-- Additional content can go here -->
            </div>
        </div>
        
    </div>
</body>
</html>
