<div class="border border-gray-200 dark:border-gray-700 rounded-xl shadow-md overflow-hidden">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 rounded-xl">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 rounded-t-xl">
            <tr>
                <th rowspan="2"
                    class="sticky text-sm left-0 z-10  px-1.5  text-nowrap py-2 bg-blue-100">
                    Sr No.
                </th>
                <!-- Sticky Columns -->
                <th rowspan="2"
                    class="sticky text-sm left-0 z-10  px-10 py-2 bg-blue-100">
                    

                    <div class="flex items-center space-x-1">
                        <span>Engineer</span>

                        <!-- Ascending Icon -->
                        <a data-dir_field="first_name" data-dir="ASC" class="text-sm text-gray-600 hover:text-black change_direction">
                            <i class="fa fa-angle-up"></i>
                        </a>

                        <!-- Descending Icon -->
                        <a data-dir_field="first_name" data-dir="DESC" class="text-sm text-gray-600 hover:text-black change_direction">
                            <i class="fa fa-angle-down"></i>
                        </a>
                    </div>
                </th>
                <th rowspan="2"
                    class="sticky text-sm left-16 z-10  px-6 py-2 bg-blue-100">
                    <div class="flex items-center space-x-1">
                        <span>Job Type</span>

                        <!-- Ascending Icon -->
                        <a data-dir_field="job_type" data-dir="ASC" class="text-sm text-gray-600 hover:text-black change_direction">
                            <i class="fa fa-angle-up"></i>
                        </a>

                        <!-- Descending Icon -->
                        <a data-dir_field="job_type" data-dir="DESC" class="text-sm text-gray-600 hover:text-black change_direction">
                            <i class="fa fa-angle-down"></i>
                        </a>
                    </div>
                </th>
                <!-- <th rowspan="2"
                    class="sticky text-sm left-16 z-10  px-2 py-2">
                    Total Hours
                </th> -->
                <!-- Scrollable Columns -->
                <th colspan="{{$totalMonthDates}}" class="text-center bg-blue-100 text-[.78rem]  px-2 py-2">
                    @php
                    // Get current year and month, or use the selected filters if available
                    $currentYear = request('year', Carbon\Carbon::now()->year); // Default to current year if not selected
                    $currentMonth = request('month', Carbon\Carbon::now()->month); // Default to current month if not selected

                    // Get the name of the selected month or current month in text form
                    $monthName = Carbon\Carbon::createFromFormat('m', $currentMonth)->format('F');
                    @endphp
                    {{ $monthName }} {{ $currentYear }}
                </th>
                <th rowspan="2" class="text-sm left-16 z-10 px-2 py-2 bg-blue-100">
                    Action
                </th>
            </tr>
            <tr class="border border-gray-200">
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
            @if (!empty($users))
            @foreach ($users as $key => $user)
            <tr class="border-b dark:border-gray-700 group hover:bg-gray-200 dark:hover:bg-gray-600 last:rounded-b-xl">
                <td
                    class="sticky left-0 z-10 bg-white dark:bg-gray-700  text-gray-900 dark:text-white px-2 text-center   group-hover:bg-gray-300 dark:group-hover:bg-gray-800 ">
                    {{$key+1}}
                </td>
                <!-- Engineer Name -->
                <td
                    class="sticky left-0 z-10 bg-white dark:bg-gray-700 text-gray-900 dark:text-white  py-0 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                    <div class="flex gap-2 items-center"> <img class="w-8 h-8 rounded-full border"
                            src="{{ $user['profile_image'] ? asset('storage/' . $user['profile_image']) : asset('user_profiles/user/user.png') }}"
                            alt="Rounded avatar">
                        <div class="flex flex-col leading-4"> <span
                                class="text-[.9rem] dark:text-gray-200 font-medium text-gray-800">{{ $user->first_name }}
                                {{ $user->last_name }}</span> <span
                                class="text-[.7rem] font-medium dark:text-gray-200
                                        text-gray-500">{{ $user->engineer_code }}</span>
                                @php
                                    $workingDays = getWorkingDaysOfCurrentMonth($year, $month, $user);
                                @endphp
                                <span class="text-[.7rem] font-medium dark:text-gray-200
                                text-gray-500">{{ $workingDays }}</span>
                        </div>
                    </div>
                </td>
                <!-- Job Type -->
                <td
                    class="sticky left-16 z-10 px-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white  py-0 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                    <span style="white-space: nowrap;">
                        {{ $user['job_type'] ? ucfirst(str_replace('_', ' ', $user['job_type'])) : '-' }}
                    </span>
                </td>

                <!-- <td
                        class="sticky  z-10 px-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white  py-0 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        <span style="white-space: nowrap;">
                            124 Hours
                        </span>
                    </td> -->
                <!-- Attendance Status -->

                @foreach ($attendanceData[$user->id] as $attendance)
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

                <td class="bg-white text-center dark:bg-gray-700  text-gray-900 dark:text-white  flex  justify-center py-2  group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                    <button type="button"
                        title="View"
                        data-engineer-id="{{$user->id}}"
                        class="bg-[#e5e5f5] open-detail-model font-medium rounded-lg text-sm px-2.5 py-1.5 text-center  flex">
                        <svg class="w-5 h-5 text-primary" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-width="2"
                                d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                            <path stroke="currentColor" stroke-width="2"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="sr-only">Icon description</span>
                    </button>
                </td>
            </tr>
            @endforeach
            @else
            <td colspan="30">
                <div class="text-center mx-4 my-4 ">
                    <span class="text-xl ">
                        No record found.
                    </span>
                </div>
            </td>
            @endif

        </tbody>
    </table>
</div>