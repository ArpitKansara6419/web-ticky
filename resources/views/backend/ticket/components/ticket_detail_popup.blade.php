<div class="grid grid-cols-12 gap-4">
    <div class="col-span-6 space-y-7">

        <div class="text-gray-700 shadow-md border border-gray-300 rounded-lg p-6 bg-white">
            <!-- Top Row -->
            <div class="grid grid-cols-4 items-start  mb-6 gap-2">
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Ticket</span>
                    <strong class="font-semibold text-md text-primary">
                        #{{ $ticket_works['ticket']['ticket_code'] }}
                    </strong>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Task Name</span>
                    <strong class="font-semibold text-nowrap text-md">
                        {{ $ticket_works['ticket']->task_name ?? '-' }}
                    </strong>
                </div>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                @php
                    $work_start_dt = "";
                    $work_start_time = "";
                    if($ticket_works['work_start_date'])
                    {
                        $work_start_dt = utcToTimezone($ticket_works['work_start_date'], $ticket['timezone'])->format('Y-m-d');  
                        if($ticket_works['start_time'])
                        {
                            $work_start_time = utcToTimezone($ticket_works['work_start_date'].' '.$ticket_works['start_time'], $ticket['timezone'])->format('H:i:s');  
                        }  
                    }

                    $work_end_dt = "";
                    $work_end_time = "";
                    if($ticket_works['work_end_date'])
                    {
                        $work_end_dt = utcToTimezone($ticket_works['work_end_date'], $ticket['timezone'])->format('Y-m-d');    
                        if($ticket_works['end_time'])
                        {
                            $work_end_time = utcToTimezone($ticket_works['work_end_date'].' '.$ticket_works['end_time'], $ticket['timezone'])->format('H:i:s');  
                        } 
                    }
                @endphp
                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Task Start Date</span>
                    <!-- <strong class="font-semibold text-md">
                        {{ \Carbon\Carbon::parse($ticket_works->work_start_date)->format('Y-m-d') }}
                          
                    </strong> -->
                    <strong class="font-semibold text-md">
                        {{ $work_start_dt }}
                    </strong>
                </div>

               

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Check In</span>
                    <!-- <strong class="font-semibold text-md">{{$ticket_works->start_time }}</strong> -->
                    <strong class="font-semibold text-md">{{ $work_start_time }}</strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Task End Date</span>
                    <!-- <strong class="font-semibold text-md">
                        {{ \Carbon\Carbon::parse($ticket_works->work_end_date)->format('Y-m-d') }}
                    </strong> -->
                    <strong class="font-semibold text-md">
                        {{ $work_end_dt }}
                    </strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Check Out</span>
                    <!-- <strong class="font-semibold text-md">{{$ticket_works->end_time }}</strong> -->
                     <strong class="font-semibold text-md">{{$work_end_time }}</strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Break Time</span>
                    <strong class="font-semibold text-md">{{ $totalBreakTime == "00:00:00" || empty($totalBreakTime) ? '--' : $totalBreakTime.'hr' }}</strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Total Time</span>
                    <strong class="font-semibold text-md">{{$ticket_works->total_work_time ?? "00:00"}}hr</strong>
                </div>

                <!-- <div class="flex flex-col">
                    <span class="text-sm text-gray-500">OOH</span>
                    <strong class="font-semibold text-md">
                        {{ $ticket_works->is_out_of_office_hours ? 'Yes' : 'No' }}
                    </strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">WW</span>
                    <strong class="font-semibold text-md">
                        {{ $ticket_works->is_weekend ? 'Yes' : 'No' }}
                    </strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">OT</span>
                    <strong class="font-semibold text-md">
                        {{ $ticket_works->is_overtime ? 'Yes' : 'No' }}
                    </strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">HW</span>
                    <strong class="font-semibold text-md">
                        {{ $ticket_works->is_holiday ? 'Yes' : 'No' }}
                    </strong>
                </div> -->
            </div>
        </div>


        <div>
            <div class="flex items-center justify-between">
                <h5 class="text-lg">Engineer Rates</h5>
                <div class="text-[.9rem] bg-green-100 font-semibold capitalize px-[1rem] py-[.3rem] rounded-lg text-green-600">
                    {{ $engineer->job_type == 'full_time' ? 'Full Time' : "" }}
                    {{ $engineer->job_type == 'dispatch' ? 'Dispatch' : "" }}
                    {{ $engineer->job_type == 'part_time' ? 'Part Time' : "" }}
                </div>
            </div>
            <div class="flex flex-row gap-12 mt-1 p-1">

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Hourly</span>
                    <strong class="font-semibold text-md">{{ $engineerCurrency}} {{ $engineerRates->hourly_charge ?? 0 }}</strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Half Day</span>
                    <strong class="font-semibold text-md">{{ $engineerCurrency}} {{ $engineerRates->half_day_charge ?? 0}}</strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Full Day</span>
                    <strong class="font-semibold text-md">{{ $engineerCurrency}} {{ $engineerRates->full_day_charge ?? 0 }}</strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Monthly</span>
                    <strong class="font-semibold text-md">{{ $engineerCurrency}} {{ $engineerRates->monthly_charge ?? 0}}</strong>
                </div>

            </div>
            <div class="flex flex-row gap-12 mt-1 p-1">

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Overtime</span>
                    <strong class="font-semibold text-md">{{ $engineerCurrency}} {{ $engineerExtraPay->overtime ?? 0}}</strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Out Of Office HR</span>
                    <strong class="font-semibold text-md">{{ $engineerCurrency}} {{ $engineerExtraPay->out_of_office_hour ?? 0}}</strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Weekend</span>
                    <strong class="font-semibold text-md">{{ $engineerCurrency}} {{ $engineerExtraPay->weekend ?? 0}}</strong>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-500">Holiday</span>
                    <strong class="font-semibold text-md">{{ $engineerCurrency}} {{ $engineerExtraPay->public_holiday ?? 0}}</strong>
                </div>

            </div>
        </div>

        <div class="border shadow-gray-300 border-gray-300 rounded-lg p-4">
            <h5 class="text-lg">Other Expense</h5>
            <div class="mt-1 p-1">
                <ul class="w-1/2 flex flex-col gap-4 " id="otherExp">
                    @if (!isset($otherExpenses) || count($otherExpenses) === 0)
                    <li class="flex w-full justify-between">
                        <span class="text-gray-800 dark:text-gray-300"> No other expense. </span>
                    </li>
                    @else
                    @foreach ($otherExpenses as $key => $expense)
                    <li class="flex w-full justify-between">
                        <span>{{$key + 1}}. {{$expense->name}}</span>
                        <span class="bg-blue-100 text-blue-800 text-sm font-bold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                            {{$engineerCurrency}} {{$expense->expense}}
                        </span>
                    </li>
                    @endforeach
                    @endif

                </ul>
            </div>
        </div>

        <div id="attachment-container">
            <h5 class="text-lg">File Attachments</h5>
            <div class="p-4 border shadow-gray-400 border-gray-300 rounded-lg  rounded-b-lg flex gap-4 ">
                @if (!$ticketNotesAttachments->isEmpty())
                @foreach ( $ticketNotesAttachments as $key => $noteAttachment )
                @if (!empty($noteAttachment->documents && $noteAttachment->documents !== "[]" ))
                <a href="{{asset('storage')}}/{{$noteAttachment->documents}}" target="_blank">
                    <div class="flex items-center gap-3">
                        <img src="/assets/pdf-icon.png" class="w-10 h-10" alt="">
                        <span class="text-gray-500 text-sm">document</span>
                    </div>
                </a>
                @endif
                @endforeach
                @else
                <div class='text-center'> No attachments found. </div>
                @endif
            </div>

        </div>

        <div id="notes-container mt-3">
            <h5 class="text-lg">Notes</h5>
            <div class="p-4 border shadow-gray-400 border-gray-300 rounded-lg  rounded-b-lg flex gap-4 ">
                @if (!$ticketNotesAttachments->isEmpty())
                @foreach ( $ticketNotesAttachments as $key => $noteAttachment )
                @if (!empty($noteAttachment->note))
                <div class="flex items-center gap-3">
                    <span class="text-gray-500 text-sm">{{$key+1}}. {{$noteAttachment->note}}</span>
                </div>
                @endif
                @endforeach
                @else
                <div class='text-center'> No notes found. </div>
                @endif
            </div>

        </div>

    </div>

    <div class="col-span-6">
        <div>
            <h4 class="text-lg">Payment Summary</h4>
            <input type="hidden" class="payout-id-hidden" value="{{ $ticketWorkId }}">
            <div class="overflow-hidden rounded-lg border border-gray-300 mt-2" id="paymentTable">
                <table class="w-full border-collapse border border-gray-300 rounded-lg shadow-sm">
                    <thead class="bg-gray-200 text-md font-semibold">
                        <tr class="border-b border-gray-300">
                            <th class="py-3 px-4 text-left">Item / Service</th>
                            <th class="py-3 px-4 text-right">Amount</th>
                            @if($ticket_works->engineer_payout_status != 'paid')
                            <th class="py-3 px-4 text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 bg-white">
                        @foreach (['hourly_payable' => 'Hourly Payable', 'overtime_payable' => 'Overtime', 'out_of_office_payable' => 'Out of Office Hour', 'weekend_payable' => 'Weekend Work','holiday_payable' => 'Holiday Work', 'other_pay' => 'Other Pay'] as $key => $label)
                        <tr class="text-md hover:bg-gray-50">

                            @if($key == 'overtime_payable')
                            <td class="py-2 px-4 text-left">{{ $label }} ({{$ticket_works->overtime_hour}})</td>
                            @else
                            <td class="py-2 px-4 text-left">{{ $label }}</td>
                            @endif

                            <td class="py-3 px-4 text-right font-semibold amount-cell" data-key="{{ $key }}">
                                <span class="flex justify-end items-center">
                                    <span>{{ $engineerCurrency }}</span>
                                    <span class="ml-1 amount-text">{{ $ticket_works->$key ?? 0 }}</span>
                                    <input type="text" class="hidden amount-input w-20 text-right border rounded px-2 py-1 ml-2" value="{{ $ticket_works->$key }}">
                                </span>
                            </td>
                            @if($ticket_works->engineer_payout_status != 'paid')
                            <td class="py-3 px-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="edit-btn bg-blue-100 text-primary rounded-lg px-2 py-2 flex items-center hover:bg-blue-200">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.304 4.844l2.852 2.852M7 7H4a1 1 0 00-1 1v10a1 1 0 001 1h11a1 1 0 001-1v-4.5m2.409-9.91a2.017 2.017 0 010 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 012.852 0z" />
                                        </svg>
                                    </button>

                                    <button class="save-btn hidden bg-green-100 text-green-700 rounded-lg px-2 py-2 flex items-center hover:bg-green-200">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h11.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V19a1 1 0 01-1 1H5a1 1 0 01-1-1V5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4h8v4H8V4zm7 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach

                        <!-- Total Row -->

                        <tr class="text-lg font-semibold bg-primary">
                            @php
                            //$total_payable = $ticket_works->hourly_payable + $ticket_works->overtime_payable + $ticket_works->weekend_payable + $ticket_works->holiday_payable + $ticket_works->out_of_office_payable + $ticket_works->other_pay ;

                            $total_payable = (float)$ticket_works['daily_gross_pay'] + (float)$ticket_works['other_pay'];

                            @endphp
                            <td class="py-2 px-4 text-white" <?php echo $ticket_works->engineer_payout_status != 'paid' ? ' ' : ' '; ?>>Total</td>
                            <td class="py-2 px-4 text-right text-white">{{$engineerCurrency}} {{$total_payable}} </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>