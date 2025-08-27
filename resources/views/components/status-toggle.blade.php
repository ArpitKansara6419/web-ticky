@php
    $isRequired = isset($attributes['required']) !== false;
@endphp

<div class="w-full">
    <label for={{$id}} class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
        Status
        @if($isRequired)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <div class="inline-flex w-full rounded-md shadow-sm" role="group">
        <!-- Hidden input to store the selected status, default to 1 (active) -->
        <input type="hidden" id="status" name="status" value="{{ $value ?? 1 }}"> <!-- Default to 1 if not set -->

        <!-- Active Button -->
        <button 
            type="button"
            id="activeBtn"
            class="w-1/2 px-4 py-2 h-full text-sm font-medium bg-white border border-gray-200 rounded-s-lg 
                   hover:bg-blue-800 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-700 
                   dark:bg-gray-800 dark:border-gray-700 dark:text-white 
                   dark:hover:bg-blue-800 dark:hover:text-white dark:focus:ring-blue-500"
            onclick="setStatus(1)"
            aria-pressed="true"  
        >{{ $activeLabel }}</button>                             
        
        <!-- Inactive Button -->
        <button 
            type="button" 
            id="inactiveBtn"
            class="w-1/2 px-4 py-2 text-sm font-medium bg-white border border-gray-200 rounded-e-lg 
                   hover:bg-blue-800 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-700 
                   dark:bg-gray-800 dark:border-gray-700 dark:text-white 
                   dark:hover:bg-blue-800 dark:hover:text-white dark:focus:ring-blue-500"
            onclick="setStatus(0)"
            aria-pressed="false"
        >{{ $inactiveLabel }}</button>
    </div>

    <!-- Error message for validation -->
    <x-input-error :messages="$errors->get('status')" class='mt-1'/>
</div>

<script>
    // Function to set the status and update button styles
    function setStatus(value) {
        // Update the hidden input with the selected status value
        document.getElementById('status').value = value;

        // Get buttons
        const activeBtn = document.getElementById('activeBtn');
        const inactiveBtn = document.getElementById('inactiveBtn');

        // Reset both buttons to default styling
        activeBtn.classList.remove('bg-blue-600', 'text-white', 'dark:bg-blue-500');
        inactiveBtn.classList.remove('bg-blue-600', 'text-white', 'dark:bg-blue-500');
        activeBtn.classList.add('bg-white', 'dark:bg-gray-800');
        inactiveBtn.classList.add('bg-white', 'dark:bg-gray-800');

        // Toggle button classes based on value
        if (value == 1) {
            // Apply active button styling
            activeBtn.classList.remove('bg-white', 'dark:bg-gray-800');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'dark:bg-blue-500');
            activeBtn.setAttribute('aria-pressed', 'true');
            inactiveBtn.setAttribute('aria-pressed', 'false');
        } else {
            // Apply inactive button styling
            inactiveBtn.classList.remove('bg-white', 'dark:bg-gray-800');
            inactiveBtn.classList.add('bg-blue-600', 'text-white', 'dark:bg-blue-500');
            inactiveBtn.setAttribute('aria-pressed', 'true');
            activeBtn.setAttribute('aria-pressed', 'false');
        }
    }

    // On page load, set the button based on the current status value (edit form scenario)
    window.onload = function() {
        console.log('document.getElementById(status) = ', document.getElementById('status'));
        
        const currentStatus = parseInt(document.getElementById('status').value);
        setStatus(currentStatus);
    };
</script>
