<div id="customer-detail-static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Customer Details
                </h3>
                <button type="button" class="close-modal text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="customer-detail-static-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-6 space-y-6">
                <!-- Profile Image -->
                <div class="flex items-center space-x-4">
                    <img class="w-[6rem] h-[6rem] rounded-full object-cover customerImage"
                        src=""
                        onerror="this.onerror=null;this.src='/user_profiles/user/user.png';"
                        alt="Profile Image">
                </div>

                <!-- Basic Customer Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 space-y-3">
                    <div class="flex flex-col">
                        <span class="text-md text-gray-500">Code</span>
                        <strong class="font-medium text-md text-primary customerCode"></strong>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-md text-gray-500">Type</span>
                        <strong class="font-medium text-md customerType"></strong>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-md text-gray-500">Name</span>
                        <strong class="font-medium text-md customerName"></strong>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-md text-gray-500">Email</span>
                        <strong class="font-medium text-md customerEmail"></strong>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-md text-gray-500">Company Reg No</span>
                        <strong class="font-medium text-md companyRegNo">-</strong>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-md text-gray-500">VAT No</span>
                        <strong class="font-medium text-md vatNo"></strong>
                    </div>
                </div>

                <!-- Authorized Person Section -->
                <div class="space-y-3">
                    <div class="relative flex items-center w-full">
                        <hr class="w-full h-px bg-gray-200 border-0 dark:bg-gray-700">
                        <span class="absolute left-1/2 transform -translate-x-1/2 px-3 text-md font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800">
                            Authorised Person Details
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="authPersonDetails">

                    </div>
                </div>

                <!-- Documents Section -->
                <div class="space-y-3">
                    <div class="relative flex items-center w-full mt-4">
                        <hr class="w-full h-px bg-gray-200 border-0 dark:bg-gray-700">
                        <span class="absolute left-1/2 transform -translate-x-1/2 px-3 text-md font-medium text-blue-800 bg-white dark:text-blue-700 dark:bg-gray-800">
                            Document
                        </span>
                    </div>
                    <div id="customerDocuments" class="grid grid-cols-1 md:grid-cols-3 space-y-3">
                        <!-- Filled dynamically -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>