<!-- Success Toaster -->
<div id="success-toast" class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-green-500 bg-white rounded-lg shadow-lg dark:text-green-400 dark:bg-gray-800 z-50 hidden" role="alert">
    <svg class="w-6 h-6 me-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg>
    <div class="text-sm font-normal" id="success-toast-message">Success!</div>
</div>
<div id="error-toast" class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-red-500 bg-white rounded-lg shadow-lg dark:text-red-400 dark:bg-gray-800 z-50 hidden" role="alert">
    <!-- <svg class="w-6 h-6 me-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg> -->
    <svg class="w-6 h-6 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
    <div class="text-sm font-normal" id="error-toast-message">Success!</div>
</div>

<script>
    function showSuccessToast(message = "Operation successful!") {
        const toast = document.getElementById("success-toast");
        const toastMessage = document.getElementById("success-toast-message");

        if (toast && toastMessage) {
            toastMessage.textContent = message;
            toast.classList.remove("hidden");
            toast.classList.add("flex");

            // Auto-hide after 3 seconds
            setTimeout(() => {
                toast.classList.remove("flex");
                toast.classList.add("hidden");
            }, 3000);
        }
    }
    function showErrorToast(message = "Operation successful!") {
        const etoast = document.getElementById("error-toast");
        const etoastMessage = document.getElementById("error-toast-message");

        if (etoast && etoastMessage) {
            etoastMessage.textContent = message;
            etoast.classList.remove("hidden");
            etoast.classList.add("flex");

            // Auto-hide after 3 seconds
            setTimeout(() => {
                etoast.classList.remove("flex");
                etoast.classList.add("hidden");
            }, 3000);
        }
    }
</script>