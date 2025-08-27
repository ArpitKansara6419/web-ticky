<div class="grid grid-cols-12 gap-4">
    <div class="col-span-6 space-y-7">

        @include('backend.modal_components.engineer_customer_payout-block', ['ticket_works' => $engineer_ticket_works, 'ticket_breaks' => $ticket_breaks])
        <div>
            <h5 class="text-lg">Ticket Rates</h5>
            <div class="flex justify-between">
                <div class="flex flex-row gap-12 mt-1 p-1">
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Hourly</span>
                        <strong class="font-semibold text-md">{{ $ticket_currency}} {{ $lead->hourly_rate ?? 0 }}</strong>
                    </div>
    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Half Day</span>
                        <strong class="font-semibold text-md">{{ $ticket_currency}} {{ $lead->half_day_rate ?? 0}}</strong>
                    </div>
    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Full Day</span>
                        <strong class="font-semibold text-md">{{ $ticket_currency}} {{ $lead->full_day_rate ?? 0 }}</strong>
                    </div>
    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Monthly</span>
                        <strong class="font-semibold text-md">{{ $ticket_currency}} {{ $lead->monthly_rate ?? 0}}</strong>
                    </div>
                </div>
                <div class="flex flex-row gap-12 mt-1 p-1">
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Standard Rate</span>
                        <strong class="font-semibold text-md">{{ ucfirst($ticket->rate_type ?? '-') }}</strong>
                    </div>
                </div>
            </div>
        </div>


        <div id="attachment-container">
            <h5 class="text-lg">File Attachments</h5>
            <div class="p-4 border shadow-gray-400 border-gray-300 rounded-lg  rounded-b-lg flex gap-4 ">
                @if (!$ticketNotesAttachments->isEmpty())
                    @foreach ( $ticketNotesAttachments as $key => $noteAttachment )
                       @if (!empty($noteAttachment->documents))
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
            <h4 class="text-lg">Payment Summary </h4>
            <input type="hidden" class="payout-id-hidden" value="{{ $ticketWorkId }}">

            <div class="overflow-hidden rounded-lg border border-gray-300 mt-2" id="paymentTable">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-200 text-md font-semibold">
                        <tr>
                            <th class="py-2 px-4 text-left">Item / Service</th>
                            <th class="py-2 px-4 text-right border">Amount</th>
                            @if($ticket_works->payment_status == 'pending')
                                <th class="py-2 px-4 text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach (['hourly_payable' => 'Total Receivable', 'ot_payable' => 'Overtime', 'ooh_payable' => 'Out of Office Hour', 'ww_payable' => 'Weekend Work','hw_payable' => 'Holiday Work', 'tool_cost' => 'Tool Cost', 'travel_cost' => 'Travel Cost'] as $key => $label)
                        <tr class="text-md">
                            @if($key == 'ot_payable')
                            <td class="py-2 px-4 text-left">{{ $label }} ({{$ticket_works->overtime_hour}})</td>
                            @else
                            <td class="py-2 px-4 text-left">{{ $label }}</td>
                            @endif

                            <td class="py-2 px-4 text-right amount-cell" data-key="{{ $key }}">
                                <div class="flex justify-end items-center">
                                    <div>{{ $ticket_currency }}</div>
                                    <div class="amount-text"> {{$ticket_works->$key ?? 0}}</div>
                                    <div> <input type="text" class="amount-input-customer hidden w-20 text-right border rounded px-2 py-1 ml-2" value="{{$ticket_works->$key}}"></div>
                                </div>

                            </td>

                            @if($ticket_works->payment_status == 'pending')
                                <td class="py-2 px-4 text-center">
                                    <div class="w-full text-center">
                                        <button class="customer-edit-btn bg-blue-100 text-[#19147D] rounded-lg px-2 py-2 flex justify-center items-center">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>
                                        </button>
                                        <button class="customer-save-btn hidden bg-green-100 rounded-lg px-2 py-2 flex justify-center  items-center">
                                            <svg class="w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 0 1 1-1h11.586a1 1 0 0 1 .707.293l2.414 2.414a1 1 0 0 1 .293.707V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5Z" />
                                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 4h8v4H8V4Zm7 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            @endif

                        </tr>
                        @endforeach
                        <tr class="text-lg font-semibold bg-primary">
                            @php
                                // $total_payable = $ticket_works->client_payable +  $ticket_works->tool_cost + $ticket_works->travel_cost ;

                                 $total_payable = $ticket_works->hourly_payable + $ticket_works->ot_payable + $ticket_works->ww_payable + $ticket_works->hw_payable + $ticket_works->ooh_payable +$ticket_works->tool_cost + $ticket_works->travel_cost   ;

                            @endphp
                            <td class="py-2 px-4 text-white" <?php echo $ticket_works->engineer_payout_status == 'pending' ? ' ' : ' '  ; ?> >Total</td>
                            <td class="py-2 px-4 text-right text-white">{{$ticket_currency}} {{$total_payable}} </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>