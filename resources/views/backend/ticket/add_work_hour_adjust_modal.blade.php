<div id="work_hours_add_adjust_modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full  max-w-[80%] max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    NEW Work Hours ADJUST
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white toggleModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div id="" class="p-6 space-y-4 ">
                <form id="form_add_adjust" action="{{ route('storeNewWorkTicket', $ticket->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                        <div>
                            <x-input-label for="start_date" :value="__('Date *')" />
                            <x-text-input id="start_date" class="block w-full" type="date" name="start_date"
                                required />
                        </div>
                        <div>
                            <x-input-label for="add_start_time" :value="__('Start Time *')" />
                            <x-text-input id="add_start_time" class="block w-full" type="time" name="start_time"
                                required />
                        </div>
                        <div>
                            <x-input-label for="add_end_time" :value="__('End Time *')" />
                            <x-text-input id="add_end_time" class="block w-full" type="time" name="end_time"
                                required />
                        </div>
                        <div>
                            <x-ticket-status-dropdown name="ticket_status" id="ticket_status"
                                value="{{ $ticket->status }}" />
                        </div>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <hr class="my-2 dark:opacity-20" />
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
