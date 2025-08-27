<div class="bg-white rounded-lg shadow-lg">
    <div class="  border-b   border-b-gray-400">
        <h3 class="p-4 text-xl text-black font-medium"> Attendance Details </h3>
    </div>
    <div class=" p-6 space-y-6">
        <div class="flex items-center gap-6">
            <img src="{{ $engineer?->profile_image ? asset('storage/' . $engineer?->profile_image ) : asset('user_profiles/user/user.png') }}" class="w-24 h-24 rounded-full" alt="">
            <div class="flex flex-col ">
                <span class="text-lg text-black">{{$engineer?->first_name}} {{$engineer?->last_name}}</span>
                <span class="text-sm text-gray-400"># {{$engineer?->engineer_code}}</span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-semibold text-gray-400">Job Type</span>
                <span class="text-lg text-black">{{ ucfirst($engineer?->job_type) }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-sm font-semibold text-gray-400">Total Hours</span>
                <span class="text-lg text-black">{{$totalWorkTime ?? '00:00'}}hr</span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-semibold text-gray-400">Total Present</span>
                <span class="text-lg text-black text-center">{{ $presentCount ?? 0 }}</span>
            </div>
            @if($engineer?->job_type == 'full_time')
            <div class="flex flex-col">
                <span class="text-sm font-semibold text-gray-400">Total Leave</span>
                <span class="text-lg text-black text-center">{{ $leaveCount ?? 0 }}</span>
            </div>
            @endif
        </div>

        <div class="">
            <div class="w-full text-center text-xl font-semibold mb-4"> {{$monthName}} - {{$year}}</div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 rounded-xl">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 rounded-t-xl">
                    <tr class="">
                        @php
                        for ($i = 1; $i <= $totalMonthDates; $i++) {
                            $roundedClass=$i==1 ? 'rounded-tl-xl' : ($i==$totalMonthDates ? 'rounded-tr-xl' : '' );
                            $borderClass=$i==1 ? 'border-l' : ($i==$totalMonthDates ? 'border-r' : 'border-x' ); // Ensures correct border application
                            echo "<th class='text-center bg-[#666766] {$borderClass} border-gray-200 text-white hover:bg-stone-700 hover:text-gray-200 text-lg px-1 py-1 {$roundedClass}'>{$i}</th>" ;
                            }
                            @endphp
                            </tr>
                </thead>
                <tbody class="">
                    <tr class="border-b dark:border-gray-700 group hover:bg-gray-200 dark:hover:bg-gray-600 last:rounded-b-xl">
                        @foreach ($attendanceData[$engineer->id] as $attendance)
                            @if ($attendance == "P")
                            <td class="text-center text-green-500 bg-white dark:bg-gray-700 font-bold px-2 py-1 text-[.78rem] border border-gray-200 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                                {{$attendance}}
                            </td>
                            @elseif ($attendance == "A")
                            <td class="text-center text-red-600 bg-red-50 dark:bg-gray-700 font-bold px-2 py-1 text-[.78rem] border border-gray-200 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                                {{$attendance}}
                            </td>
                            @elseif ($attendance == "L")
                            <td class="text-center text-yellow-500 bg-white dark:bg-gray-700 font-bold px-2 py-1 text-[.78rem] border border-gray-200 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                                {{$attendance}}
                            </td>
                            @elseif ($attendance == "W")
                            <td class="text-center text-neutral-400 bg-white dark:bg-gray-700 font-bold px-2 py-1 text-[.78rem] border border-gray-200 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                                {{$attendance}}
                            </td>
                            @elseif ($attendance == "H")
                            <td class="text-center text-neutral-400 bg-white dark:bg-gray-700 font-bold px-2 py-1 text-[.78rem] border border-gray-200 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                                {{$attendance}}
                            </td>
                            @elseif ($attendance == "NA")
                            <td class="text-center text-gray-600 bg-white dark:bg-gray-700 font-bold px-2 py-1 text-[.78rem] border border-gray-200 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                                {{$attendance}}
                            </td>
                            @endif
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-12">
            <div class="col-span-6">
                <div class="p-3 bg-white  shadow-sm rounded-xl">
                    <div class="w-full">
                        <h2 class="text-xl font-semibold mb-4">Leaves</h2>
                    </div>

                    <div class="rounded-xl border border-gray-200 shadow-sm">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-[1rem] font-medium">Sr. No</th>
                                    <th class="px-4 py-2 text-[1rem] font-medium">Start Date</th>
                                    <th class="px-4 py-2 text-[1rem] font-medium">End Date</th>
                                    <th class="px-4 py-2 text-[1rem] font-medium">Leave Type</th>
                                    <th class="px-4 py-2 text-[1rem] font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$leaves->isEmpty())
                                @foreach ($leaves as $leave)
                                <tr class="text-center border-b last:rounded-b-xl">
                                    <td class="px-2 py-1 text-xs">{{ $loop->iteration }}</td>
                                    <td class="px-2 py-1 text-xs">{{ \Carbon\Carbon::parse($leave->paid_from_date ?? $leave->unpaid_from_date)->format('Y M d')}}</td>
                                    <td class="px-2 py-1 text-xs">{{ \Carbon\Carbon::parse($leave->unpaid_to_date ?? $leave->paid_to_date)->format('Y M d') }}</td>
                                    <td class="px-2 py-1 text-xs text-center">
                                        <div class="">
                                            <div class="bg-green-100 text-green-500 border  w-fit py-1 px-2 rounded-lg inline-block">
                                                {{ $leave->paid_from_date ? 'Paid' : '' }}
                                            </div>
                                            <div class="mt-2 bg-red-100 text-red-500 border  w-fit py-1 px-2 rounded-lg inline-block">
                                                {{ $leave->unpaid_from_date ? 'Unpaid' : '' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 py-1 text-xs">
                                        <div class="bg-green-100 text-green-500 border border-green-500 w-fit py-1 px-2 rounded-lg inline-block">
                                            {{ ucfirst($leave->leave_approve_status) }}
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="text-center border-b last:rounded-b-xl">
                                    <td colspan="6">
                                        <p class="p-3">
                                            No record found.
                                        </p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-span-6">
                <div class="p-3 bg-white  shadow-sm rounded-xl">
                    <div class="w-full">
                        <h2 class="text-xl font-semibold mb-4">Holiday's</h2>
                    </div>
                    <div class="rounded-xl border border-gray-200 shadow-sm">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-[1rem] font-medium">Sr. No</th>
                                    <th class="px-4 py-2 text-[1rem] font-medium">Date</th>
                                    <th class="px-4 py-2 text-[1rem] font-medium">Holiday</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$holidays->isEmpty())
                                @foreach ($holidays as $holiday)
                                <tr class="text-center border-b last:rounded-b-xl">
                                    <td class="px-2 py-1 text-xs">{{ $loop->iteration }}</td>
                                    <td class="px-2 py-1 text-xs">{{ \Carbon\Carbon::parse($holiday->date ?? $holiday->date)->format('Y M d')}}</td>
                                    <td class="px-2 py-1 text-xs">
                                        <span>
                                            {{ $holiday->title  }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="text-center border-b last:rounded-b-xl">
                                    <td colspan="6">
                                        <p class="p-3">
                                            No record found.
                                        </p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class=" ">
            <h6 class="text-gray-400 text-sm">Leave Reason</h6>
            <p class="text-sm w-[70%]">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nisi impedit eos vel veritatis vitae, blanditiis vero quis, sunt mollitia eaque nam sint aliquam.</p>
        </div> --}}
    </div>
</div>