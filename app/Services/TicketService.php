<?php

namespace App\Services;

use App\Models\CustomerPayable;
use App\Models\Engineer;
use App\Models\EngineerCharge;
use App\Models\EngineerExtraPay;
use App\Models\Holiday;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\TicketNotifications;
use App\Models\TicketWork;
use App\Models\WorkBreak;
use Carbon\Carbon;

class TicketService
{
    public function holdAndBreakAndPassEndDateAction($ticket)
    {
        $get_ticket_break_ = WorkBreak::where('ticket_id', $ticket->id)
            ->where('engineer_id', $ticket->engineer_id)
            ->orderBy('id', 'DESC')
            ->first();



        if (isset($get_ticket_break_->id)) {
            $request_params = [
                'work_break_id' => $get_ticket_break_->id,
                'end_time' => $get_ticket_break_->start_time
            ];

            // Calculate the total break time in minutes
            $startTime = strtotime($get_ticket_break_->start_time); // Convert start_time to timestamp
            $endTime = strtotime($request_params['end_time']); // Convert end_time to timestamp

            // Calculate the total break time in seconds
            $totalBreakTimeInSeconds = $endTime - $startTime;

            // Format the total break time as H:i:s
            $hours = floor($totalBreakTimeInSeconds / 3600);
            $minutes = floor(($totalBreakTimeInSeconds % 3600) / 60);
            $seconds = $totalBreakTimeInSeconds % 60;

            $totalBreakTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            // dd($totalBreakTime);

            $get_ticket_break_->update([
                'break_end_date' => $get_ticket_break_->break_start_date,
                'end_time' => $request_params['end_time'],
                'total_break_time' => $totalBreakTime,
            ]);

            Ticket::where('id', $get_ticket_break_->ticket_id)->update(['status' => 'inprogress']);

            $ticket = Ticket::with('engineer')->find($get_ticket_break_->ticket_id);
            if ($ticket && $ticket->engineer) {
                $engineerName = $ticket->engineer->first_name . ' ' . $ticket->engineer->last_name;
                $engineerCode = $ticket->engineer->engineer_code;

                // Create notification
                Notification::create([
                    'user_id' => $ticket->engineer_id ?? null,
                    'type' => 'ticket',
                    'title' => '⏹️ Break Ended on Ticket',
                    'message' => "$engineerName ($engineerCode) ended the break on Ticket #{$ticket->ticket_code}.",
                    'is_read' => false,
                    'url' => null,
                    'meta' => json_encode([
                        'ticket_id' => $ticket->id,
                    ]),
                ]);
            }

            return true;
        }

        return false;
    }

    public function closeTicket($ticket, $updatedStatus, $updateTicketWork = null)
    {
        if(!$updateTicketWork){
            $get_ticket_work_id = TicketWork::where('ticket_id', $ticket->id)
                    ->where('user_id', $ticket->engineer_id)
                    ->orderBy('id', 'DESC')
                    ->first();
        }else{
            $get_ticket_work_id = $updateTicketWork;
        }
        
        
        if (isset($get_ticket_work_id->id)) {
            
            $validatedData = $request = [
                'ticket_work_id' => $get_ticket_work_id->id,
                'end_time' => $get_ticket_work_id->end_time,
                'note' => "Auto close by cron, engineer forget to close",
                'travel_cost' => 0,
                'tool_cost' => 0,
                'status' => $updatedStatus,
            ];

            if($updateTicketWork && isset($updateTicketWork->id))
            {
                $validatedData['work_end_date'] = $get_ticket_work_id->work_start_date;
            }else if($ticket->status == 'inprogress'){
                $engineerCharges = EngineerCharge::where('engineer_id', $ticket->engineer_id)->first();
                if($get_ticket_work_id->start_time > $engineerCharges->check_out_time)
                {
                    $validatedData['end_time'] = Carbon::now()->format("H:i:s");
                }else{
                    $validatedData['end_time'] = $engineerCharges->check_out_time;
                }
                // $validatedData['end_time'] = "18:15:09";
                $validatedData['work_end_date'] = $get_ticket_work_id->work_start_date;

            }else if($ticket->status == 'hold')
            {
                $validatedData['work_end_date'] = $get_ticket_work_id->work_end_date;

            }else if($ticket->status == 'break' && empty($validatedData['end_time'])){

                $workBreak = WorkBreak::where('ticket_id', $ticket->id)
                            ->where('engineer_id', $ticket->engineer_id)
                            ->orderBy('id', 'DESC')
                            ->first();
                if($workBreak)
                {
                    $validatedData['end_time'] = $workBreak->start_time;
                    $validatedData['work_end_date'] = $workBreak->break_start_date;
                }
                
            }else{
                $validatedData['work_end_date'] = Carbon::now()->format("Y-m-d");
            }

            // dd($validatedData);
            

            $ticketWork = TicketWork::findOrFail($validatedData['ticket_work_id']);
            $ticket = Ticket::findOrFail($ticket->id);

            $start = Carbon::parse($ticketWork->work_start_date . ' ' . $ticketWork->start_time);
            $end = Carbon::parse($validatedData['work_end_date'] . ' ' . $validatedData['end_time']);

            $breaks = WorkBreak::where('ticket_work_id', $validatedData['ticket_work_id'])->get();

            $workDate = $ticketWork->work_start_date;

            $isHoliday = Holiday::where('date', $workDate)->exists();
            $isWeekend = Carbon::parse($workDate)->isWeekend();


            // Calculate Engineer Payout
            $this->calculateEngineerPayout(
                $start,
                $end,
                $ticket,
                $breaks,
                $ticketWork,
                $validatedData,
                $request,
                $isHoliday,
                $isWeekend
            );

            // Calculate Customer Payable
            $this->calculateCustomerPayable(
                $start,
                $end,
                $ticket,
                $breaks,
                $ticketWork,
                $validatedData,
                $isHoliday,
                $isWeekend
            );

            // dd($validatedData['status']);
            // update ticket status
            $ticket->update(['status' => $validatedData['status']]);

            $ticket = Ticket::with('engineer')->find($ticket->id);
            if ($ticket && $ticket->engineer) {
                $engineerName = $ticket->engineer->first_name . ' ' . $ticket->engineer->last_name;
                $engineerCode = $ticket->engineer->engineer_code;

                Notification::create([
                    'user_id' => $ticket->engineer_id ?? null,
                    'type' => 'ticket',
                    'title' => '🎉 Work Completed on Ticket',
                    'message' => "$engineerName ($engineerCode) completed work on Ticket #{$ticket->ticket_code}.",
                    'is_read' => false,
                    'url' => null,
                    'meta' => json_encode([
                        'ticket_id' => $ticket->id,
                    ]),
                ]);
            }


            TicketNotifications::where([
                'ticket_id' => $ticket->id,
                'date'  => now()->toDateString(), // Current date
                'engineer_id'  => $ticket->engineer_id, // Handle possible null values
                'notification_type' => 'in_progress_ticket', // Example notification type
                'is_repeat' => true, // Assuming default value is false
                'status' => 'pending', // Assuming default status
            ])->update([
                'is_repeat' => false,
                'status' => 'sent',
            ]);


            return true;
        }

        return false;
    }

    public function calculateCustomerPayable($start, $end, $ticket,$breaks, $ticketWork, $validatedData,  $isHoliday, $isWeekend)
    {
        $engineerCharges = EngineerCharge::where('engineer_id', $ticket->engineer_id)->first();

        $lead = Lead::where([
            'id' => $ticket->lead_id
        ])->first();

        $rateType = $ticket->rate_type;
        $ticketHourlyRate = $lead->hourly_rate;
        $ticketHalfDayRate = $lead->half_day_rate;
        $ticketFullDayRate = $lead->full_day_rate;
        $isFullTimeRate = $rateType == "monthly";
        $monthlyRate = $lead->monthly_rate;

        // Define Check-In and Check-Out Time (Only Time, Date is Not Required)
        $checkInTime = Carbon::createFromFormat('H:i:s', $engineerCharges->check_in_time);
        $checkOutTime = Carbon::createFromFormat('H:i:s', $engineerCharges->check_out_time);

        // Define Start and End Time with Date
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $end);


        $ovetimeAlues = $this->getOvertimeOrOutOfOfficeData($checkInTime, $checkOutTime, $start, $end,$breaks);

        if ($isFullTimeRate) {
            $engineer = Engineer::where('id', $ticket->engineer_id)->first();
            $workingDays = getWorkingDaysOfCurrentMonth($start->copy()->year, $start->copy()->month, $engineer);
            $ticketHourlyRate = round(($monthlyRate / $workingDays) / 8, 2);
            $rateType = "hourly";
        }

        $hourMinutes = $this->convertMinutesToHoursAndMinutes($ovetimeAlues['regular_work_minutes']);

        $payableData = $this->customerCalculateRate($hourMinutes['hours'], $hourMinutes['minutes'], $rateType, $ticketHourlyRate, $ticketHalfDayRate, $ticketFullDayRate, $isWeekend, $isHoliday, $ovetimeAlues);

        if($ticket->rate_type === 'agreed') {
            $total_cost = (float)$ticket->standard_rate; 
        }else{
            $total_cost = $isFullTimeRate ? 0 : $payableData['totalCost'];
        }      
        
        $tool_cost = $ticket->tool_cost;
        if(TicketWork::where('ticket_id', $ticket->id)->count() > 1){
            $tool_cost = 0;
        }

        // dd($payableData);
        $updatedData = [
            'total_work_time' => $payableData['totalWorkingTime'],
            'hourly_rate' => $lead->hourly_rate,
            'halfday_rate' => $lead->half_day_rate,
            'fullday_rate' => $lead->full_day_rate,
            'monthly_rate' => $lead->monthly_rate,
            'travel_cost' => $ticket->travel_cost,
            'tool_cost' => $tool_cost,
            'hourly_payable' => $total_cost,
            'overtime_payable' => 0, // Remove field
            'is_overtime' => $payableData['isOverTime'],
            'ot_payable' => $payableData['overtimeAmount'], // Overtime payable X 1.5
            'overtime_hour' => $payableData['formattedOverTime'],
            'is_out_of_office_hours' => $payableData["isOOH"] ? 1 : 0,
            'ooh_payable' =>  $payableData['OOHAmount'], // out if office hour payable X 1.5s
            'is_weekend' => $payableData['isWeekend'],
            'ww_payable' => $payableData['weekendFinalAmount'], // weekend work X 2
            'is_holiday' => $payableData['isHoliday'],
            'hw_payable' => $payableData['holidayFinalAmount'], // holiday work X 2
            'client_payable' => $total_cost + $payableData['overtimeAmount'] +  $payableData['weekendFinalAmount'] +  $payableData['OOHAmount'] +  $payableData['holidayFinalAmount'],
            'work_end_time' => $validatedData['end_time'],
            'work_end_date' => $validatedData['work_end_date'],
            'currency' => $ticket->currency_type,
        ];
        
        // dd($updatedData);

        CustomerPayable::where([
            'ticket_work_id' => $validatedData['ticket_work_id']
        ])->update($updatedData);
    }

    public function getOvertimeOrOutOfOfficeData($checkInTime, $checkOutTime, $start, $end, $breaks = [])
    {
        
        $overtimeMinutes = 0;
        $regularMinutes = 0;
        $outOfOfficeMinutes = 0;

        $regularSegments = [];
        $overtimeSegments = [];
        $outOfOfficeSegments = [];

        $checkInTime = $checkInTime->copy();
        $checkOutTime = $checkOutTime->copy();
        $current = $start->copy();

        while ($current->lt($end)) {
            $businessStart = $checkInTime->copy()->setDate($current->year, $current->month, $current->day);
            $businessEnd = $checkOutTime->copy()->setDate($current->year, $current->month, $current->day);

            $dayEnd = $current->copy()->endOfDay();
            $segmentEnd = $end->lt($dayEnd) ? $end->copy() : $dayEnd->copy();

            if ($segmentEnd->lte($businessStart)) {
                $minutes = $segmentEnd->diffInMinutes($current);
                $outOfOfficeMinutes += $minutes;
                $outOfOfficeSegments[] = ['start' => $current->copy(), 'end' => $segmentEnd->copy()];
            } elseif ($current->gte($businessEnd)) {
                $minutes = $segmentEnd->diffInMinutes($current);
                $outOfOfficeMinutes += $minutes;
                $outOfOfficeSegments[] = ['start' => $current->copy(), 'end' => $segmentEnd->copy()];
            } elseif ($current->gte($businessStart) && $segmentEnd->lte($businessEnd)) {
                $minutes = $segmentEnd->diffInMinutes($current);
                $regularMinutes += $minutes;
                $regularSegments[] = ['start' => $current->copy(), 'end' => $segmentEnd->copy()];
            } else {
                if ($current->lt($businessStart)) {
                    $beforeBusiness = $businessStart->diffInMinutes($current);
                    $outOfOfficeMinutes += $beforeBusiness;
                    $outOfOfficeSegments[] = ['start' => $current->copy(), 'end' => $businessStart->copy()];
                    $current = $businessStart;
                }

                $businessSegmentEnd = $segmentEnd->lt($businessEnd) ? $segmentEnd : $businessEnd;
                if ($current->lt($businessSegmentEnd)) {
                    $withinBusiness = $businessSegmentEnd->diffInMinutes($current);
                    $regularMinutes += $withinBusiness;
                    $regularSegments[] = ['start' => $current->copy(), 'end' => $businessSegmentEnd->copy()];
                    $current = $businessSegmentEnd;
                }

                if ($segmentEnd->gt($businessEnd)) {
                    $afterBusiness = $segmentEnd->diffInMinutes(max($businessEnd, $current));
                    $overtimeMinutes += $afterBusiness;
                    $overtimeSegments[] = ['start' => max($businessEnd, $current)->copy(), 'end' => $segmentEnd->copy()];
                }
            }

            $current = $segmentEnd->copy()->addSecond();
        }


        // ✅ Subtract break time
        if (!empty($breaks)) {
            $breakImpact = $this->calculateBreakImpactOnSegments($breaks, $regularSegments, $overtimeSegments, $outOfOfficeSegments);

            $regularMinutes -= $breakImpact['regular'];
            $overtimeMinutes -= $breakImpact['overtime'];
            $outOfOfficeMinutes -= $breakImpact['out_of_office'];

            // Make sure minutes are not negative
            $regularMinutes = max(0, $regularMinutes);
            $overtimeMinutes = max(0, $overtimeMinutes);
            $outOfOfficeMinutes = max(0, $outOfOfficeMinutes);
        }

        $isOvertime = $overtimeMinutes > 0 && $regularMinutes > 0;
        if($regularMinutes > 0 &&  $outOfOfficeMinutes > 0){
            $isOvertime = true;
            $overtimeMinutes += $outOfOfficeMinutes;
            $outOfOfficeMinutes = 0;
        }
        $isOutOfOfficeHour = $regularMinutes == 0 && $overtimeMinutes == 0 && $outOfOfficeMinutes > 0;
        
    
        return [
            'start_time' => $start->toDateTimeString(),
            'end_time' => $end->toDateTimeString(),
            'check_in_time' => $checkInTime->toDateTimeString(),
            'check_out_time' => $checkOutTime->toDateTimeString(),
            'regular_work_minutes' => $regularMinutes,
            'out_of_office_minutes' => $outOfOfficeMinutes,
            'overtime_minutes' => $overtimeMinutes,
            'is_out_of_office_hour' => $isOutOfOfficeHour,
            'is_overtime' => $isOvertime,
            'total_work_minutes' => $regularMinutes + $overtimeMinutes + $outOfOfficeMinutes,
        ];
    }

    function convertMinutesToHoursAndMinutes($totalMinutes)
    {
        $hours = intdiv($totalMinutes, 60); // Get whole hours
        $minutes = $totalMinutes % 60; // Get remaining minutes

        return [
            'hours' => $hours,
            'minutes' => $minutes
        ];
    }

    function customerCalculateRate($totalHours, $totalMinutes, $rateType, $hourlyRate, $halfDayRate, $fullDayRate, $isWeekend, $isHoliday, $overtimeAndOutOfValue)
    {

        list($roundedHours, $roundedMinutes) = $this->customerRoundTime($totalHours, $totalMinutes);


        // Calculate total time in hours
        $totalHours = $roundedHours + ($roundedMinutes / 60);
        

        $regularCost = 0;
        $totalOvetTimeHours = 0;
        $overTimeFinalAmount = 0;
        $weekendFinalAmount = 0;
        $holidayFinalAmount = 0;

        $totalWorkingTime = 0;
        $formattedOverTime = 0;
        $formattedOOHTime = 0;
        $formattedHolidayTime = 0;
        $formattedWeekendTime = 0;

        $outOfOfficeHoursFinalAmount = 0;

        $isOverTimeValue = $overtimeAndOutOfValue['is_overtime'];

        $isOutOfOfficeHourValue = $overtimeAndOutOfValue['is_out_of_office_hour'];

     

        if ($isOutOfOfficeHourValue) {
            $ohhHourMinutes = $this->convertMinutesToHoursAndMinutes($overtimeAndOutOfValue['out_of_office_minutes']);

            list($roundedHours, $roundedMinutes) = $this->customerRoundTime($ohhHourMinutes['hours'], $ohhHourMinutes["minutes"]);

            // Calculate total time in hours
            $totalOOHHours = $roundedHours + ($roundedMinutes / 60);
            $regularOOHCost = 0;
            switch ($rateType) {
                case 'hourly':
                    if ($totalOOHHours >= 2) {

                        $regularOOHCost = $totalOOHHours * $hourlyRate;
                    } else {
                        $regularOOHCost = 2 * $hourlyRate;
                    }


                    break;
                case 'halfday':
                    if ($totalOOHHours <= 4) {
                        $regularOOHCost = $halfDayRate;
                    } elseif ($totalOOHHours > 4) {
                        $extraHours = $totalOOHHours - 4;
                        $regularOOHCost = $halfDayRate + ($extraHours * $hourlyRate);
                    }
                    break;
                case 'fullday':
                    $regularOOHCost = $fullDayRate;
                    break;
            }

            $formattedOOHTime = sprintf('%02d:%02d:00', $roundedHours, $roundedMinutes);

            $outOfOfficeHoursFinalAmount  = $regularOOHCost * 1.5;
        } else if ($isHoliday) {

            $totalMinutes =  $overtimeAndOutOfValue['out_of_office_minutes'] + $overtimeAndOutOfValue['overtime_minutes'] + $overtimeAndOutOfValue['regular_work_minutes'];

            $holidayMinutes = $this->convertMinutesToHoursAndMinutes($totalMinutes);

            list($roundedHolidayHours, $roundedHolidayMinutes) = $this->customerRoundTime($holidayMinutes['hours'], $holidayMinutes["minutes"]);

            $totalHolidayHours = $roundedHolidayHours + ($roundedHolidayMinutes / 60);
            $regularHolidayCost = 0;
            switch ($rateType) {
                case 'hourly':
                    if ($totalHolidayHours >= 2) {

                        $regularHolidayCost = $totalHolidayHours * $hourlyRate;
                    } else {
                        $regularHolidayCost = 2 * $hourlyRate;
                    }

                    break;
                case 'halfday':
                    if ($totalHolidayHours <= 4) {
                        $regularHolidayCost = $halfDayRate;
                    } elseif ($totalHolidayHours > 4) {
                        $extraHours = $totalHolidayHours - 4;
                        $regularHolidayCost = $halfDayRate + ($extraHours * $hourlyRate);
                    }
                    break;
                case 'fullday':
                    $regularHolidayCost = $fullDayRate;
                    break;
            }

            $formattedHolidayTime = sprintf('%02d:%02d:00', $roundedHolidayHours, $roundedHolidayMinutes);

            $holidayFinalAmount  = $regularHolidayCost * 2;
        } else if ($isWeekend) {
            $totalMinutes =  $overtimeAndOutOfValue['out_of_office_minutes'] + $overtimeAndOutOfValue['overtime_minutes'] + $overtimeAndOutOfValue['regular_work_minutes'];

            $weekendMinutes = $this->convertMinutesToHoursAndMinutes($totalMinutes);

            list($roundedWeekendHours, $roundedWeekendMinutes) = $this->customerRoundTime($weekendMinutes['hours'], $weekendMinutes["minutes"]);

            $totalWeekendHours = $roundedWeekendHours + ($roundedWeekendMinutes / 60);
            $regularWeekednCost = 0;
            switch ($rateType) {
                case 'hourly':
                    if ($totalWeekendHours >= 2) {

                        $regularWeekednCost = $totalWeekendHours * $hourlyRate;
                    } else {
                        $regularWeekednCost = 2 * $hourlyRate;
                    }


                    break;
                case 'halfday':
                    if ($totalWeekendHours <= 4) {
                        $regularWeekednCost = $halfDayRate;
                    } elseif ($totalWeekendHours > 4) {
                        $extraHours = $totalWeekendHours - 4;
                        $regularWeekednCost = $halfDayRate + ($extraHours * $hourlyRate);
                    }
                    break;
                case 'fullday':
                    $regularWeekednCost = $fullDayRate;
                    break;
            }

            $formattedWeekendTime = sprintf('%02d:%02d:00', $roundedWeekendHours, $roundedWeekendMinutes);

            $weekendFinalAmount  = $regularWeekednCost * 2;
        } else {
            switch ($rateType) {
                case 'hourly':
                    if ($totalHours >= 2) {

                        $regularCost = $totalHours * $hourlyRate;
                    } else {
                        $regularCost = 2 * $hourlyRate;
                    }

                    break;
                case 'halfday':
                    if ($totalHours <= 4) {
                        $regularCost = $halfDayRate;
                    } elseif ($totalHours > 4 && $totalHours <= 8) {
                        $extraHours = $totalHours - 4;
                        $regularCost = $halfDayRate + ($extraHours * $hourlyRate);
                    }
                    break;
                case 'fullday':
                    $regularCost = $fullDayRate;
                    break;
            }
            
            if ($isOverTimeValue) {

                $ovetTimeHourMinutes = $this->convertMinutesToHoursAndMinutes($overtimeAndOutOfValue['overtime_minutes']);
             
                list($roundedOvertimeHours, $roundedOvertimeMinutes) = $this->customerRoundTime($ovetTimeHourMinutes['hours'], $ovetTimeHourMinutes["minutes"]);

                $totalOvetTimeHours = $roundedOvertimeHours + ($roundedOvertimeMinutes / 60);
                
                $formattedOverTime = sprintf('%02d:%02d:00',$ovetTimeHourMinutes['hours'], $ovetTimeHourMinutes["minutes"]);

                $overTimeFinalAmount = $totalOvetTimeHours * ($hourlyRate * 1.5);

                // dd($hourlyRate);
            }

            if($rateType === 'agreed'){
                $overTimeFinalAmount = 0;
            }
        }
        
        $totalWorkingMinutes =  $overtimeAndOutOfValue['out_of_office_minutes'] + $overtimeAndOutOfValue['overtime_minutes'] + $overtimeAndOutOfValue['regular_work_minutes'];

        $totalWorkingMinutes = $this->convertMinutesToHoursAndMinutes($totalWorkingMinutes);

        list($roundedWorkingHours, $roundedWorkingMinutes) = $this->customerRoundTime($totalWorkingMinutes['hours'], $totalWorkingMinutes["minutes"]);

        $totalWorkingTime = sprintf('%02d:%02d:00', $totalWorkingMinutes['hours'], $totalWorkingMinutes["minutes"]);

        $returnValue = [
            // 'testing' =>[
            // 'overtimeAndOutOfValue'=> $overtimeAndOutOfValue,
            // 'rateType'=> $rateType,
            // 'fullDayRate'=>$fullDayRate,
            // 'halfDayRate'=>$halfDayRate,
            // 'hourlyRate'=>$hourlyRate,
            // ],
            'roundedTime' => sprintf('%02d:%02d', $roundedWorkingHours, $roundedWorkingMinutes),
            'totalWorkingTime' => $totalWorkingTime,
            'totalCost' => $regularCost,
            'subTotal' => $regularCost - $overTimeFinalAmount,

            /// Out Of Office Hours
            'isOOH' => $isOutOfOfficeHourValue,
            'formattedOOHTime' => $formattedOOHTime,
            'OOHAmount' => $outOfOfficeHoursFinalAmount,

            /// Overtime Values
            'isOverTime' => $isOverTimeValue,
            'formattedOverTime' => $formattedOverTime,
            'overtimeAmount' => $overTimeFinalAmount,

            // Holiday Values
            'isHoliday' => $isHoliday,
            'formattedHolidayTime' => $formattedHolidayTime,
            'holidayFinalAmount' => $holidayFinalAmount,

            // Weekend Values
            'isWeekend' => $isWeekend,
            'formattedWeekendTime' => $formattedWeekendTime,
            'weekendFinalAmount' => $weekendFinalAmount,

        ];


        return $returnValue;
    }

    public function calculateEngineerPayout($start, $end, $ticket,$breaks, $ticketWork, $validatedData, $request, $isHoliday, $isWeekend)
    {

        $engineer = Engineer::find($ticket->engineer_id);
        $engineerCharges = EngineerCharge::where('engineer_id', $ticket->engineer_id)->first();
        $engineerExtraPayRates = EngineerExtraPay::where('engineer_id', $ticket->engineer_id)->first();

        if (empty($engineerCharges) || empty($engineerExtraPayRates)) {
            return response()->json([
                'success' => false,
                'message' => 'Engineer rates / charges not updated.',
            ], 400);
        }

        if ($end->lessThanOrEqualTo($start)) {
            return response()->json([
                'success' => false,
                'message' => 'End time must be after start time.',
            ], 400);
        }

        // Define Check-In and Check-Out Time (Only Time, Date is Not Required)
        $checkInTime = Carbon::createFromFormat('H:i:s', $engineerCharges->check_in_time);
        $checkOutTime = Carbon::createFromFormat('H:i:s', $engineerCharges->check_out_time);

        // Define Start and End Time with Date
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $end);
        
       

        $ovetimeAlues = $this->getOvertimeOrOutOfOfficeData($checkInTime, $checkOutTime, $start, $end,$breaks);
    
        if ($isWeekend || $isHoliday) {
            $isOutOfOfficeHour = false;
        } else {
            $isOutOfOfficeHour = $ovetimeAlues['is_out_of_office_hour'];
        }

        $hourlyRate = 0;
        $halfDayRate = null;
        $fullDayRate = null;
        $monthlyRate = null;
        $overtimeRate = null;
        $isFullTimeEngineer = $engineer->job_type == "full_time";

        if ($isFullTimeEngineer) {
            $monthlyRate = $engineerCharges->monthly_charge;
        }

        if ($isHoliday) {
            $hourlyRate = $engineerExtraPayRates->public_holiday;
        } else if ($isWeekend) {
            $hourlyRate = $engineerExtraPayRates->weekend;
        } else if ($isOutOfOfficeHour) {
            $hourlyRate = $engineerExtraPayRates->out_of_office_hour;
        } else {
            if ($isFullTimeEngineer) {
                $hourlyRate = 0;
                $halfDayRate = 0;
                $fullDayRate = 0;
            } else {
                $hourlyRate = $engineerCharges->hourly_charge;
                $halfDayRate = $engineerCharges->half_day_charge;
                $fullDayRate = $engineerCharges->full_day_charge;
            }

            $overtimeRate = $engineerExtraPayRates->overtime;
        }

        if(!empty($ticket->engineer_agreed_rate)) {
            $hourlyRate = 0;
            $halfDayRate = 0;
            $fullDayRate = 0;
            $overtimeRate = 0;
        }

        $hourMinutes = $this->convertMinutesToHoursAndMinutes($ovetimeAlues['regular_work_minutes']);

        $engineerPayoutCalculation = $this->engineerCalculateRate($hourMinutes['hours'], $hourMinutes['minutes'], $hourlyRate, $halfDayRate, $fullDayRate, $overtimeRate, $ovetimeAlues, $isWeekend, $isHoliday);

        if(!empty($ticket->engineer_agreed_rate)) {
            $total_cost = (float)$ticket->engineer_agreed_rate; 
        }else{
            $total_cost = $isFullTimeEngineer ? 0 : (float)$engineerPayoutCalculation['totalCost'];
        }

        $updateFields = [
            'end_time' => $validatedData['end_time'],
            'note' => $validatedData['note'] ?? $ticketWork->note,

            'total_work_time' => $engineerPayoutCalculation['totalWorkingTime'],
            'work_end_date' => $validatedData['work_end_date'],

            'hourly_rate' => $hourlyRate,
            'hourly_payable' => $total_cost,

            'is_overtime' =>  $engineerPayoutCalculation['isOverTime'] ? 1 : 0,
            'overtime_payable' => (float)$engineerPayoutCalculation['overtimeAmount'],
            'overtime_hour' => $engineerPayoutCalculation['formattedOverTime'],


            'is_holiday' => $engineerPayoutCalculation['isHoliday'] ? 1 : 0,
            'holiday_payable' => $engineerPayoutCalculation['isHoliday'] ? (float)$engineerPayoutCalculation['holidayFinalAmount'] : 0,

            'is_weekend' => $engineerPayoutCalculation['isWeekend']  ? 1 : 0,
            'weekend_payable' => $engineerPayoutCalculation['isWeekend'] ? (float)$engineerPayoutCalculation['weekendFinalAmount'] : 0,

            'monthly_rate' => $monthlyRate,

            'is_out_of_office_hours' =>  $engineerPayoutCalculation['isOOH'] ? 1 : 0,
            'out_of_office_duration' => $engineerPayoutCalculation['formattedOOHTime'],
            'out_of_office_payable' =>  $engineerPayoutCalculation['isOOH'] ? (float)$engineerPayoutCalculation['OOHAmount'] : 0,

            'daily_gross_pay' => $total_cost + (float)$engineerPayoutCalculation['weekendFinalAmount'] + (float)$engineerPayoutCalculation['holidayFinalAmount'] + (float)$engineerPayoutCalculation['OOHAmount'] + (float)$engineerPayoutCalculation['overtimeAmount'] ?? 0,
            'currency' => $engineerCharges->currency_type,
        ];
        // dd($updateFields);
        // Log::info($updateFields);

        // **Update Ticket Work**
        $ticketWork->update($updateFields);
    }

    function engineerCalculateRate($hours, $minutes, $hourlyRate, $halfDayRate, $fullDayRate, $overtimeRate, $overtimeAndOutOfValue, $isWeekend, $isHoliday)
    {

        list($roundedHours, $roundedMinutes) = $this->engineerRoundTime($hours, $minutes);

        // Calculate total time in hours
        $totalHours = $roundedHours + ($roundedMinutes / 60);

        $regularCost = 0;
        $totalOvetTimeHours = 0;
        $overTimeFinalAmount = 0;
        $weekendFinalAmount = 0;
        $holidayFinalAmount = 0;

        $totalWorkingTime = 0;
        $formattedOverTime = 0;
        $formattedOOHTime = 0;
        $formattedHolidayTime = 0;
        $formattedWeekendTime = 0;

        $outOfOfficeHoursFinalAmount = 0;

        $isOverTimeValue = $overtimeAndOutOfValue['is_overtime'];

        $isOutOfOfficeHourValue = $overtimeAndOutOfValue['is_out_of_office_hour'];

        $totalWorkingMinutes =  $overtimeAndOutOfValue['out_of_office_minutes'] + $overtimeAndOutOfValue['overtime_minutes'] + $overtimeAndOutOfValue['regular_work_minutes'];

        $totalWorkingMinutes = $this->convertMinutesToHoursAndMinutes($totalWorkingMinutes);

        list($roundedWorkingHours, $roundedWorkingMinutes) = $this->engineerRoundTime($totalWorkingMinutes['hours'], $totalWorkingMinutes["minutes"]);

        $totalWorkingTime = sprintf('%02d:%02d:00', $roundedWorkingHours, $roundedWorkingMinutes);

        $remainingHours = $totalHours;
        $regularCost = 0;

        while ($remainingHours > 0) {
            $hoursThisCycle = min($remainingHours, 9);

            if ($hoursThisCycle <= 2.99) {
                $cost = $hoursThisCycle * $hourlyRate;
                if ($cost >= $halfDayRate) {
                    $cost = $halfDayRate;
                }
            } elseif ($hoursThisCycle <= 5.99) {
                $cost = $halfDayRate;

                if ($hoursThisCycle >= 4.5) {
                    $extraHours = $hoursThisCycle - 4;
                    $cost += ($extraHours * $hourlyRate);
                }

                if ($cost >= $fullDayRate) {
                    $cost = $fullDayRate;
                }
            } else {
                $cost = $fullDayRate;
            }

            $regularCost += $cost;
            $remainingHours -= $hoursThisCycle;
        }

        if ($isOutOfOfficeHourValue) {
            $ovetTimeHourMinutes = $this->convertMinutesToHoursAndMinutes($overtimeAndOutOfValue['out_of_office_minutes']);

            list($roundedHours, $roundedMinutes) = $this->engineerRoundTime($ovetTimeHourMinutes['hours'], $ovetTimeHourMinutes["minutes"]);

            // Calculate total time in hours
            $totalHours = $roundedHours + ($roundedMinutes / 60);

            $formattedOOHTime = sprintf('%02d:%02d:00', $roundedHours, $roundedMinutes);

            $outOfOfficeHoursFinalAmount  = $totalHours * $hourlyRate;
        }


        if ($isOverTimeValue) {
            $ovetTimeHourMinutes = $this->convertMinutesToHoursAndMinutes($overtimeAndOutOfValue['overtime_minutes']);

            list($roundedOvertimeHours, $roundedOvertimeMinutes) = $this->engineerRoundTime($ovetTimeHourMinutes['hours'], $ovetTimeHourMinutes["minutes"]);

            $totalOvetTimeHours = $roundedOvertimeHours + ($roundedOvertimeMinutes / 60);

            $formattedOverTime = sprintf('%02d:%02d:00', $ovetTimeHourMinutes['hours'], $ovetTimeHourMinutes["minutes"]);

            $overTimeFinalAmount = $totalOvetTimeHours * $overtimeRate;
        }

        if ($isWeekend) {
            $totalMinutes =  $overtimeAndOutOfValue['out_of_office_minutes'] + $overtimeAndOutOfValue['overtime_minutes'] + $overtimeAndOutOfValue['regular_work_minutes'];

            $weekendMinutes = $this->convertMinutesToHoursAndMinutes($totalMinutes);

            list($roundedWeekendHours, $roundedWeekendMinutes) = $this->engineerRoundTime($weekendMinutes['hours'], $weekendMinutes["minutes"]);

            $totalWeekendHours = $roundedWeekendHours + ($roundedWeekendMinutes / 60);

            $formattedWeekendTime = sprintf('%02d:%02d:00', $roundedWeekendHours, $roundedWeekendMinutes);

            $weekendFinalAmount = $totalWeekendHours * $hourlyRate;
        }


        if ($isHoliday) {
            $totalMinutes =  $overtimeAndOutOfValue['out_of_office_minutes'] + $overtimeAndOutOfValue['overtime_minutes'] + $overtimeAndOutOfValue['regular_work_minutes'];

            $holidayMinutes = $this->convertMinutesToHoursAndMinutes($totalMinutes);

            list($roundedHolidayHours, $roundedHolidayMinutes) = $this->engineerRoundTime($holidayMinutes['hours'], $holidayMinutes["minutes"]);

            $totalHolidayHours = $roundedHolidayHours + ($roundedHolidayMinutes / 60);

            $formattedHolidayTime = sprintf('%02d:%02d:00', $roundedHolidayHours, $roundedHolidayMinutes);

            $holidayFinalAmount = $totalHolidayHours * $hourlyRate;
        }


        $returnValue = [
            'roundedTime' => sprintf('%02d:%02d', $roundedHours, $roundedMinutes),
            'totalWorkingTime' => $totalWorkingTime,
            'totalCost' => $regularCost,
            'subTotal' => $regularCost - $overTimeFinalAmount,

            /// Out Of Office Hours
            'isOOH' => $isOutOfOfficeHourValue,
            'formattedOOHTime' => $formattedOOHTime,
            'OOHAmount' => $outOfOfficeHoursFinalAmount,

            /// Overtime Values
            'isOverTime' => $isOverTimeValue,
            'formattedOverTime' => $formattedOverTime,
            'overtimeAmount' => $overTimeFinalAmount,

            // Holiday Values
            'isHoliday' => $isHoliday,
            'formattedHolidayTime' => $formattedHolidayTime,
            'holidayFinalAmount' => $holidayFinalAmount,

            // Weekend Values
            'isWeekend' => $isWeekend,
            'formattedWeekendTime' => $formattedWeekendTime,
            'weekendFinalAmount' => $weekendFinalAmount,

        ];



        return $returnValue;
    }

    // Function for rounding time
    function engineerRoundTime($hours, $minutes)
    {
        if ($minutes <= 14) {
            $roundedMinutes = 0;
        } elseif ($minutes >= 15 && $minutes <= 35) {
            $roundedMinutes = 30;
        } elseif ($minutes >= 36) {
            $roundedMinutes = 0;
            $hours += 1;
        }

        return [$hours, $roundedMinutes];
    }

    function customerRoundTime($hours, $minutes)
    {
        if ($minutes > 15) {
            $hours += 1;
            $roundedMinutes = 0;
        } else {
            $roundedMinutes = 0;
        }
        return [$hours, $roundedMinutes];
    }

    public function calculateBreakImpactOnSegments($breaks, $regularSegments, $overtimeSegments, $outOfOfficeSegments)
    {
        
        $breakImpact = [
            'regular' => 0,
            'overtime' => 0,
            'out_of_office' => 0,
        ];

        foreach ($breaks as $break) {
            $breakStart = Carbon::parse($break->break_start_date . ' ' . $break->start_time);
            $breakEnd   = Carbon::parse($break->break_end_date . ' ' . $break->end_time);


            // Regular
            foreach ($regularSegments as $segment) {
                $segmentStart = $segment['start'];
                $segmentEnd = $segment['end'];

                $overlapStart = $breakStart->greaterThan($segmentStart) ? $breakStart : $segmentStart;
                $overlapEnd = $breakEnd->lessThan($segmentEnd) ? $breakEnd : $segmentEnd;

                if ($overlapEnd->greaterThan($overlapStart)) {
                    $breakImpact['regular'] += $overlapEnd->diffInMinutes($overlapStart);
                }
            }

            // Overtime
            foreach ($overtimeSegments as $segment) {
                $segmentStart = $segment['start'];
                $segmentEnd = $segment['end'];

                $overlapStart = $breakStart->greaterThan($segmentStart) ? $breakStart : $segmentStart;
                $overlapEnd = $breakEnd->lessThan($segmentEnd) ? $breakEnd : $segmentEnd;

                if ($overlapEnd->greaterThan($overlapStart)) {
                    $breakImpact['overtime'] += $overlapEnd->diffInMinutes($overlapStart);
                }
            }

            // Out of Office
            foreach ($outOfOfficeSegments as $segment) {
                $segmentStart = $segment['start'];
                $segmentEnd = $segment['end'];

                $overlapStart = $breakStart->greaterThan($segmentStart) ? $breakStart : $segmentStart;
                $overlapEnd = $breakEnd->lessThan($segmentEnd) ? $breakEnd : $segmentEnd;

                if ($overlapEnd->greaterThan($overlapStart)) {
                    $breakImpact['out_of_office'] += $overlapEnd->diffInMinutes($overlapStart);
                }
            }
        }
        
    

        return $breakImpact;
    }
}
