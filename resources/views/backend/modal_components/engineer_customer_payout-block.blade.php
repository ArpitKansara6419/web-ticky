<div class="text-gray-700 shadow-md border border-gray-300 rounded-lg p-6 bg-white">

    <div class="grid grid-cols-4 items-start  mb-6 gap-2">
        <div class="flex flex-col">
            <span class="text-sm text-gray-500">Ticket</span>
            <strong class="font-semibold text-md text-primary">
                #{{ $ticket_works['ticket']['ticket_code'] }}
            </strong>
        </div>
        <div class="flex flex-col">
            <span class="text-sm text-gray-500">Task Name</span>
            <strong class="font-semibold text-nowrap text-md cursor-pointer"
                title="{{ $ticket_works['ticket']->task_name }}">
                {{ $ticket_works['ticket']->task_name ? Str::words($ticket_works['ticket']->task_name, 2, '...') : '-' }}
            </strong>
        </div>
        <div class="flex flex-col">
            <span class="text-sm text-gray-500">Timezone</span>
            <strong class="font-semibold text-nowrap text-md">
                {{ $ticket_works['ticket']->timezone ?? '-' }}
                ({{ fetchTimezone($ticket_works['ticket']->timezone)['gmtOffsetName'] ?? '' }})
            </strong>
        </div>
    </div>


    <div class="grid grid-cols-5     gap-x-12 mt-5 gap-y-4">
        @php
            $work_start_dt = '';
            $work_start_time = '';
            if ($ticket_works['work_start_date']) {
                $work_start_dt = utcToTimezone($ticket_works['work_start_date'], $ticket['timezone'])->format('Y-m-d');
                if ($ticket_works['start_time']) {
                    $work_start_time = utcToTimezone(
                        $ticket_works['work_start_date'] . ' ' . $ticket_works['start_time'],
                        $ticket['timezone'],
                    )->format('H:i:s');
                }
            }

            $work_end_dt = '';
            $work_end_time = '';
            if ($ticket_works['work_end_date']) {
                $work_end_dt = utcToTimezone($ticket_works['work_end_date'], $ticket['timezone'])->format('Y-m-d');
                if ($ticket_works['end_time']) {
                    $work_end_time = utcToTimezone(
                        $ticket_works['work_end_date'] . ' ' . $ticket_works['end_time'],
                        $ticket['timezone'],
                    )->format('H:i:s');
                }
            }
        @endphp
        <div class="flex flex-col">
            <span class="text-sm text-gray-500 whitespace-nowrap">Task Start Date</span>

            <strong class="font-semibold text-md whitespace-nowrap">
                {{ $work_start_dt }}
            </strong>
        </div>



        <div class="flex flex-col">
            <span class="text-sm text-gray-500 whitespace-nowrap">Check In</span>

            <strong class="font-semibold text-md whitespace-nowrap">{{ $work_start_time }}</strong>
        </div>

        <div class="flex flex-col">
            <span class="text-sm text-gray-500 whitespace-nowrap">Task End Date</span>

            <strong class="font-semibold text-md whitespace-nowrap">
                {{ $work_end_dt }}
            </strong>
        </div>

        <div class="flex flex-col">
            <span class="text-sm text-gray-500 whitespace-nowrap">Check Out</span>

            <strong class="font-semibold text-md whitespace-nowrap">{{ $work_end_time }}</strong>
        </div>

        <div class="flex flex-col">
            <span class="text-sm text-gray-500 whitespace-nowrap">
                Break Time @include('backend.ticket.work-break-tooltip', ['ticket_breaks' => $ticket_breaks])
            </span>
            <strong class="font-semibold text-md whitespace-nowrap">
                {{ $totalBreakTime == '00:00:00' || empty($totalBreakTime) ? '--' : $totalBreakTime . 'hr' }}
            </strong>
        </div>

        <div class="flex flex-col">
            <span class="text-sm text-gray-500 whitespace-nowrap">Total Time</span>
            <strong class="font-semibold text-md">{{ $ticket_works->total_work_time ?? '00:00' }}hr</strong>
        </div>


    </div>
</div>
