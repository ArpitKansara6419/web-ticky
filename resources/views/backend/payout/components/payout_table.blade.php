<table id="search-table" class="w-full border rounded-xl " style="border-radius: 12px">
    <thead>
        <tr class="">
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[.85rem] ">
                    Sr.
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-start text-[1rem] ">
                    Engineer Name
                </span>
            </th>

            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    Ticket(s)
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    Hour(s) | Day(s)
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    Payable
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    OT
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    OOH
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    WW
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    HW
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    Exp
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    Monthly
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    Total
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    Paid
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    Unpaid
                </span>
            </th>
            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-2 py-6">
                <span class="flex items-center justify-center text-[1rem] ">
                    Action
                </span>
            </th>
        </tr>
    </thead>
    <tbody class="px-2">

        @if (!$monthlySummary->isEmpty())
        @foreach ($monthlySummary as $key => $engineerPayout)

        @php
        $totalPay = 0;

        if ($engineerPayout?->engineer->job_type == "full_time") {
        $totalPay = ($engineerPayout?->engineer?->enggCharge?->monthly_charge ?? 0) + ($engineerPayout?->extra_pay_sum ?? 0);
        } else {
        $totalPay = ($engineerPayout?->total_gross_pay ?? 0) + ($engineerPayout?->total_other_pay ?? 0);
        }

        @endphp

        <tr class="font-bold border-b border-gray-300 p-4">
            <td class="dark:text-gray-200  text-gray-900 px-4 py-1 whitespace-nowrap">
                <div class="flex items-center justify-center">
                    {{$key + 1}}
                </div>
            </td>
            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                <div
                    class="font-medium flex items-center justify-start gap-2 p-1 text-gray-900 whitespace-nowrap dark:text-white ">
                    <img class="w-10 h-10 rounded-full" src="{{ $engineerPayout?->engineer?->profile_image ? asset('storage/' . $engineerPayout?->engineer?->profile_image ) : asset('user_profiles/user/user.png') }}"
                        alt="Rounded avatar">
                    <div class="">
                        <p class="capitalize"> <span class="bg-blue-100 text-blue-800 text-sm font-medium  px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300"> {{ $engineerPayout?->engineer?->job_type ? substr(ucfirst(str_replace('_', ' ', $engineerPayout?->engineer->job_type )), 0, 1) : '-' }}</span>
                            <span class="">
                                {{ $engineerPayout?->engineer?->first_name . ' ' . $engineerPayout?->engineer?->last_name }}
                            </span>
                        </p>
                        <p class="text-gray-400">{{ $engineerPayout?->engineer?->email }}</p>
                        <p class="text-gray-400">
                            {{ $engineerPayout?->engineer?->timezone }}
                            ({{ fetchTimezone($engineerPayout?->engineer?->timezone)['gmtOffsetName'] ?? '' }})
                        </p>
                    </div>
                </div>
            </td>
            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap">
                <span class="flex items-center justify-center">
                    {{ $engineerPayout?->total_tickets ?? 0  }}
                </span>
            </td>
            <td>
                <div class="flex items-center justify-center">
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300"> {{ $monthlySummary[$key]['total_work_time'] ?? '-'  }} hrs</span> <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">{{ $engineerPayout?->unique_days ?? 0  }} day</span>
                </div>
            </td>

            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                <div class="flex items-center justify-center">
                    {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] }} {{ $engineerPayout?->total_hourly_payable ?? 0  }}
                </div>
            </td>
            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                <div class="flex items-center justify-center">
                    {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] }} {{ $engineerPayout?->total_overtime_payable ?? 0  }}
                </div>
            </td>
            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                <div class="flex items-center justify-center">
                    {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] }} {{ $engineerPayout?->total_out_of_office_payable ?? 0  }}
                </div>
            </td>
            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                <div class="flex items-center justify-center">
                    {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] }} {{ $engineerPayout?->total_weekend_payable ?? 0  }}
                </div>
            </td>
            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                <div class="flex items-center justify-center">
                    {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] }} {{ $engineerPayout?->total_holiday_payable ?? 0 }}
                </div>
            </td>

            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                <div class="flex items-center justify-center">
                    {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] }} {{ $engineerPayout?->total_other_pay ?? 0 }}
                </div>
            </td>

            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                @php
                    $currentMonth=now()->format('Y-m');
                    $monthlyPayoutData = json_decode($engineerPayout?->engineer?->monthly_payout, true);
                    @endphp

                    <div class="flex items-center justify-center gap-2">
                        @if ($engineerPayout?->engineer->job_type == "full_time")

                        @if (!empty($monthlyPayoutData[$currentMonth]))
                        <span>&#10004;</span>
                        @endif

                        {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] ?? '' }}
                        {{ $engineerPayout?->engineer?->enggCharge?->monthly_charge ?? 0 }}

                        @else
                        --
                        @endif
                    </div>

            </td>


            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                <div class="flex items-center justify-center">
                    {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] }} {{$totalPay }}
                </div>
            </td>


            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                <div class="flex items-center justify-center">
                    @php
                        $monthly_charge = 0;
                        if ($engineerPayout?->engineer->job_type == "full_time"){
                            if (!empty($monthlyPayoutData[$currentMonth])){
                                $monthly_charge = $engineerPayout?->engineer?->enggCharge?->monthly_charge;
                            }
                        }
                    @endphp
                    {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] }} {{ $engineerPayout?->total_paid + $monthly_charge ?? 0  }}
                </div>
            </td>
            <td class="dark:text-gray-200 text-gray-900 px-4 py-1 whitespace-nowrap font-medium">
                <div class="flex items-center justify-center">
                    {{ $currencySymbols[$engineerPayout?->engineer?->enggCharge?->currency_type] }}
                    {{$totalPay - $engineerPayout?->total_paid - $monthly_charge }}
                </div>
            </td>

            <td class="px-auto py-4 whitespace-nowrap">
                <div class="flex items-center justify-center gap-2">
                    @can($ModuleEnum::ENGINEER_PAYOUT->value)
                    <a href="{{ route('payout.show', [ $engineerPayout?->engineer?->id, 'filter' => 'all', 'month'=>$selected_month]) }}">
                        <button type="button"
                            title="Payout Details"
                            class="editBtn bg-blue-100  font-medium rounded-lg text-sm px-[.6rem] py-[.4rem] text-center  flex"
                            data-drawer-target="drawer-right-example"
                            data-drawer-show="drawer-right-example"
                            data-drawer-placement="right"
                            aria-controls="drawer-right-example">
                            <svg version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="w-5 h-5 text-blue-500" viewBox="0 0 32 32" xml:space="preserve">
                                <path class="puchipuchi_een" stroke="currentColor" d="M29,2H3C1.9,2,1,2.9,1,4v19c0,1.1,0.9,2,2,2h26c1.1,0,2-0.9,2-2V4C31,2.9,30.1,2,29,2z M29,20
                                    c0,0.55-0.45,1-1,1H4c-0.55,0-1-0.45-1-1V5c0-0.55,0.45-1,1-1h24c0.55,0,1,0.45,1,1V20z M22,29c0,0.552-0.447,1-1,1H11
                                    c-0.553,0-1-0.448-1-1s0.447-1,1-1h1v-2h8v2h1C21.553,28,22,28.448,22,29z M22,14c0,0.552-0.447,1-1,1H11c-0.553,0-1-0.448-1-1
                                    s0.447-1,1-1h10C21.553,13,22,13.448,22,14z M22,10c0,0.552-0.447,1-1,1H11c-0.553,0-1-0.448-1-1s0.447-1,1-1h10
                                    C21.553,9,22,9.448,22,10z" />
                            </svg>
                            <span class="sr-only">Icon description</span>
                        </button>
                    </a>
                    @endcan

                    @can($ModuleEnum::EngineerPayouts_PAYSLIPS->value)
                    <a href="{{ route('payout.index', [ 'engineer' => $engineerPayout?->engineer?->id, 'filter' => 'all']) }}">
                        <button type="button"
                            title="Pay Slips"
                            class="editBtn bg-fuchsia-100  font-medium rounded-lg text-sm px-[.6rem] py-[.4rem] text-center  flex"
                            data-drawer-target="drawer-right-example"
                            data-drawer-show="drawer-right-example"
                            data-drawer-placement="right"
                            aria-controls="drawer-right-example">
                            <!-- <svg class="w-6 h-6 text-fuchsia-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg> -->
                            <svg viewBox="0 0 1024 1024" class="w-5 h-5 icon text-fuchsia-500" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="currentColor" d="M731.15 585.97c-100.99 0-182.86 81.87-182.86 182.86s81.87 182.86 182.86 182.86 182.86-81.87 182.86-182.86-81.87-182.86-182.86-182.86z m0 292.57c-60.5 0-109.71-49.22-109.71-109.71s49.22-109.71 109.71-109.71c60.5 0 109.71 49.22 109.71 109.71s-49.21 109.71-109.71 109.71z" />
                                <path stroke="currentColor" d="M718.01 778.55l-42.56-38.12-36.6 40.86 84.02 75.26 102.98-118.46-41.4-36zM219.51 474.96h219.43v73.14H219.51z" />
                                <path stroke="currentColor" d="M182.61 365.86h585.62v179.48h73.14V145.21c0-39.96-32.5-72.48-72.46-72.48h-27.36c-29.18 0-55.04 16.73-65.88 42.59-5.71 13.64-27.82 13.66-33.57-0.02-10.86-25.86-36.71-42.57-65.88-42.57h-18.16c-29.18 0-55.04 16.73-65.88 42.59-5.71 13.64-27.82 13.66-33.57-0.02-10.86-25.86-36.71-42.57-65.88-42.57H375.3c-29.18 0-55.04 16.73-65.88 42.59-5.71 13.64-27.82 13.66-33.57-0.02-10.86-25.86-36.71-42.57-65.88-42.57H182.4c-39.96 0-72.48 32.52-72.48 72.48v805.14h401.21v-73.14H183.04l-0.43-511.35z m25.81-222.29c14.25 34.09 47.32 56.11 84.23 56.11 36.89 0 69.96-22.02 82.66-53.8l15.86-2.3c14.25 34.09 47.32 56.11 84.23 56.11 36.89 0 69.96-22.02 82.66-53.8l16.59-2.3c14.25 34.09 47.32 56.11 84.23 56.11 36.89 0 69.96-22.02 82.66-53.8l26.68-0.66v147.5H182.54l-0.13-146.84 26.01-2.33z" />
                            </svg>
                            <span class="sr-only">Icon description</span>
                        </button>
                    </a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach
        @else
        <tr class="font-bold border-b border-gray-300 p-4">
            <td colspan="13">
                <p class="text-center p-3 text-md"> No record found. </p>
            </td>
        </tr>
        @endif
    </tbody>
</table>