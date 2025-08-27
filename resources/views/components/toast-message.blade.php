
@props(['type' => 'danger', 'message', 'icon' => '', 'position' => 'top-right', 'error' => ""])

@php
    // Define colors and icons based on type
    $colorClasses = [
        'danger' => [
            'bg'        => 'bg-red-100', 
            'text'      => 'text-red-800', 
            'darkText'  => 'dark:text-red-200', 
            'darkBg'    => 'dark:bg-red-800', 
            'icon'      => '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                        </svg>'
        ], 
        'success' => [
            'bg'        => 'bg-green-100', 
            'text'      => 'text-green-600', 
            'darkText'  => 'dark:text-green-300', 
            'darkBg'    => 'dark:bg-green-700', 
            'icon'      => '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                        </svg>'
        ],
        'warning' => [
            'bg'        => 'bg-orange-100', 
            'text'      => 'text-orange-800', 
            'darkText'  => 'dark:text-orange-200', 
            'darkBg'    => 'dark:bg-orange-800', 
            'icon'      => '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                        </svg>'
        ],
        'info' => [
            'bg'        => 'bg-blue-100', 
            'text'      => 'text-blue-800', 
            'darkText'  => 'dark:text-blue-200', 
            'darkBg'    => 'dark:bg-blue-800', 
            'icon'      => '<svg class="w-5 h-5 text-blue-600 dark:text-blue-500 rotate-45" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 17 8 2L9 1 1 19l8-2Zm0 0V9"/>
                        </svg>'
        ],
    ];

    $bgColor = $colorClasses[$type]['bg'];
    $textColor = $colorClasses[$type]['text'];
    $darkTextColor = $colorClasses[$type]['darkText'];
    $darkBgColor = $colorClasses[$type]['darkBg'];
    $iconSvg = $icon ?: $colorClasses[$type]['icon'];

    // Define position classes
    $positionClasses = match ($position) {
        'top-left'      => 'top-5 left-5',
        'top-right'     => 'top-5 right-5',
        'bottom-left'   => 'bottom-5 left-5',
        'bottom-right'  => 'bottom-5 right-5',
        default         => 'top-5 right-5', 
    };  

   

@endphp


<div id="toast-{{ $type }}" class="fixed mt-14 items-center w-full max-w-xs p-4 space-x-4 {{ $bgColor }} {{ $darkBgColor }} rounded-lg shadow {{ $positionClasses }}" role="alert">
    <div class="flex items-center">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 {{ $textColor }} {{ $darkTextColor }} bg-gray-300 dark:bg-slate-700 rounded-lg">
            {!! $iconSvg !!}
            <span class="sr-only">{{ ucfirst($type) }} icon</span>
        </div>
        <div class="ms-3 text-sm font-normal {{ $darkTextColor }} {{ $textColor }}">{{ $message }}</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 text-gray-400 hover:text-gray-100 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-400 inline-flex items-center justify-center h-8 w-8 dark:text-gray-400 dark:hover:text-gray-200 {{ $darkBgColor }} dark:hover:bg-gray-600" data-dismiss-target="#toast-{{$type}}" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
    @if($error) <!-- Check if error message exists -->
        <div class="ms-3 text-sm font-normal {{ $darkTextColor }} {{ $textColor }} mt-1">
            {{ $error ?? '' }} <!-- Display the error message -->
        </div>
    @endif
</div>


<script>
    // Automatically dismiss the toast after a few seconds
    setTimeout(() => {
        const toast = document.getElementById("toast-{{ $type }}");
        if (toast) {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 2000); // Remove the toast after fade-out
        }
    }, 5000);
</script>

{{--  examples  --}}
{{--  <x-toast-message type="danger" message="Item has been deleted." position="top-left" />  --}}
{{--  <x-toast-message type="success" message="Item has been successfully saved." position="top-right" />  --}}
{{--  <x-toast-message type="warning" message="This is a warning message." position="bottom-left" />  --}}
{{--  <x-toast-message type="info" message="This is an informational message." position="bottom-right" />  --}}

