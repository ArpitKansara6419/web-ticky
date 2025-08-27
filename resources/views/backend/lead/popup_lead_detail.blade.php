<div id="static-lead-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="false" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-[60vw] max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Lead Details
                </h3>
                <button type="button" class="  close-lead-detail text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-lead-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="grid grid-cols-4 p-4 gap-4">
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Lead Code</span>
                    <strong class="font-medium text-md text-primary leadCode"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">End Client Name</span>
                    <strong class="font-medium text-md endClientName"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Task Start Time</span>
                    <strong class="font-medium text-md taskTime"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Currency Type</span>
                    <strong class="font-medium text-md currencyType"></strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Lead Type</span>
                    <strong class="font-medium text-md leadType"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Client Ticket No</span>
                    <strong class="font-medium text-md clientTicketNo"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Task Start Date</span>
                    <strong class="font-medium text-md taskStartDate"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Rate Type</span>
                    <strong class="font-medium text-md rateType"></strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Task Name</span>
                    <strong class="font-medium text-md taskName"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Task Location</span>
                    <strong class="font-medium text-md taskLocation"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Task End Date</span>
                    <strong class="font-medium text-md taskEndDate"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Monthly Rate</span>
                    <strong class="font-medium text-md monthlyRate"></strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Scope of Work</span>
                    <strong class="font-medium text-md scopeOfWork"></strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Travel Cost</span>
                    <strong class="font-medium text-md travelCost"></strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Tool Cost</span>
                    <strong class="font-medium text-md toolCost"></strong>
                </div>
            </div>
        </div>
    </div>
</div>