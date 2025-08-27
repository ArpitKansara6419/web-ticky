<div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl shadow-md">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th width="15%" rowspan="2"
                    class="sticky text-xl left-0 z-10 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white px-4 py-2">
                    Sr No.
                </th>
                <!-- Sticky Columns -->
                <th width="15%" rowspan="2"
                    class="sticky text-xl left-0 z-10 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white px-4 py-2">
                    Engineer
                </th>
                <th width="15%" rowspan="2"
                    class="sticky text-xl left-16 z-10 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white px-4 py-2">
                    Job Type
                </th>
                <!-- Scrollable Columns -->
                <th colspan="30"
                    class="text-center text-2xl tracking-[.3rem] bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white  px-4 py-2">
                    December
                </th>
            </tr>
            <tr>
                <!-- Dynamic Day Headers -->
                 <!-- @foreach ($attendanceData[$users[0]->id] as $date => $attendance)
                    echo "<th class='text-center border dark:border-gray-300   border-gray-900 dark:text-gray-300 text-gray-900  bg-gray-300  dark:bg-gray-900 px-2 py-1'>{$date}</th>" ;
                 @endforeach -->
                @php
                    for ($i = 1; $i <= 30; $i++) {
                        echo "<th class='text-center bg-gray-200 text-gray-900 px-2 py-1'>{$i}</th>" ;
                    }
                @endphp
                </tr>
        </thead>
        <tbody class="">
            @foreach ($users as $user)
                <tr class="border-b dark:border-gray-700 group hover:bg-gray-200 dark:hover:bg-gray-600">
                    <td
                        class="sticky left-0 z-10 bg-gray-100 dark:bg-gray-700  text-gray-900 dark:text-white px-4 py-2 group-hover:bg-gray-300 dark:group-hover:bg-gray-800 ">
                        1
                    </td>
                    <!-- Engineer Name -->
                    <td
                        class="sticky left-0 z-10 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        {{$user->first_name}}  {{$user->last_name}}
                    </td>
                    <!-- Job Type -->
                    <td
                        class="sticky left-16 z-10 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        Full Time
                    </td>
                    <!-- Attendance Status -->

                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-red-600   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        A</td>
                    <td
                        class="text-center text-red-600   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        A</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-red-600   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        A</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center dark:text-gray-200   text-gray-900 bg-gray-100 dark:bg-gray-700  font-bold-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        NA</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center dark:text-gray-200   text-gray-900 bg-gray-100 dark:bg-gray-700  font-bold-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        NA</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-green-500   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        P</td>
                    <td
                        class="text-center text-red-600   bg-gray-100 dark:bg-gray-700  font-bold px-2 py-1 group-hover:bg-gray-300 dark:group-hover:bg-gray-800">
                        A</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>