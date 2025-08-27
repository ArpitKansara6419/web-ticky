<div id="holiday-sync-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full  max-w-[80%] max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Holiday Sync
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
                <form id="holiday_sync_form" action="#" method="POST">
                    @csrf
                    @method('POST')
                    <div class="flex justify-between">
                        <div>
                            <x-text-field id="county_search" name="search" label=""
                                placeholder="Search..." class="" />
                        </div>
                        <div>
                            @php
                                $years = $currentYear = \Carbon\Carbon::now()->format('Y');
                                $years = [
                                    ['year_lable' => $years - 1, 'year_value' => $years - 1],
                                    ['year_lable' => $years, 'year_value' => $years],
                                    ['year_lable' => $years + 1, 'year_value' => $years + 1],
                                    
                                ];
                            @endphp
                            <x-input-dropdown id="year" name="year" label=""
                                placeholder="Select Year" class="" :options="$years" optionalLabel="year_lable" optionalValue="year_value" value="{{ $currentYear }}" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 md:grid-cols-3 gap-4 p-4" id="country_list_load">
                        
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
