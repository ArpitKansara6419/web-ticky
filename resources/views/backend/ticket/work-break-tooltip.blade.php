@if ($ticket_breaks->count() > 0)
    @php
        $rand = rand(1000, 9999);
    @endphp
    <button data-tooltip-target="tooltip-default{{ $rand }}" type="button"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <i class="fa fa-info"></i>
    </button>

    <div id="tooltip-default{{ $rand }}" role="tooltip"
        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700 datatable-container"
        >

        <table id="" class="table-striped w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 dataTable">
            <thead>
                <tr class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th class="dt-orderable-none bg-blue-100 dark:bg-gray-900 px-6 py-3">#</th>
                    <th class="dt-orderable-none bg-blue-100 dark:bg-gray-900 px-6 py-3">Break Start</th>
                    <th class="dt-orderable-none bg-blue-100 dark:bg-gray-900 px-6 py-3">Break End</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $count = $ticket_breaks->count();
                @endphp
                @foreach ($ticket_breaks as $break)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <th class="px-6 py-3">
                            {{ $count-- }}
                        </th>
                        <td class="px-6 py-3">
                            {{ $break['break_start_date'] ? $break['break_start_date_timezone']->format('Y-m-d') : '' }}
                            {{ $break['start_time'] ? $break['start_time_timezone']->format('H:i:s') : '' }}
                        </td>
                        <td class="px-6 py-3">
                            {{ $break['break_end_date'] ? $break['break_end_date_timezone']->format('Y-m-d') : '' }}
                            {{ $break['end_time'] ? $break['end_time_timezone']->format('H:i:s') : '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
