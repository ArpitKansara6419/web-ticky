<aside
    id="logo-sidebar"
    class="fixed top-0 left-0 z-40  h-screen pt-5 bg-primary-light-one border-r border-gray-200 transition-all duration-300 ease-in-out sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar"
    :class="expanded ? 'w-64' : 'w-16 -translate-x-full sm:translate-x-0'">

    <!-- when not expanded -->
    <div class="flex justify-center items-center mb-3">
        <button type="button" @click="toggleSidebar()">
            <svg
                :class="expanded ? 'hidden' : 'rotate-90 transition-transform duration-300 ease-in-out'"
                class="w-8 h-8 text-[#aaaaaa] dark:text-white"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="1.2" d="M5 7h14M5 12h14M5 17h14" />
            </svg>
        </button>
    </div>

    <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
            type="button"
            class="inline-flex items-center p-2 text-sm text-white rounded-lg sm:hidden hover:bg-[#f1f1fc] focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <span class="sr-only">Open sidebar</span>
            <svg :class="expanded ? 'rotate-180 transition-transform duration-300 ease-in-out' : ''"
                class="w-8 h-8 text-[#aaaaaa]" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                </path>
            </svg>
        </button>

        <a href="/"
            :class="expanded ? 'md:me-24 transition-all duration-300 ease-in-out' : ' md:me-0 hidden '"
            class="flex ms-2">
            <img src="/assets/logos/icon-with-name.png" class="h-10 me-0" alt="FlowBite Logo" />
            {{-- <span  :class="expanded ? '' : 'hidden transition-opacity duration-300 ease-in-out'"
                class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap font-serif text-white">Ticky</span> --}}
        </a>

        <a href="/"
            :class="expanded ? ' hidden ' : '  transition-opacity duration-300 ease-in-out ' ">
            <img src="/assets/logos/only-icon.png" style="max-height: 50px" class="h-13 ml-2 max-h-15 me-0" alt="FlowBite Logo" />
            {{-- <span  :class="expanded ? ' hidden transition-opacity duration-300 ease-in-out ' : ''"
                class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap font-serif text-white">Ticky</span> --}}
        </a>

        <button type="button" @click="toggleSidebar()">
            <svg
                :class="expanded ? 'rotate-180 transition-transform duration-300 ease-in-out' : 'hidden'"
                class="w-8 h-8 mr-2 text-[#aaaaaa] dark:text-white"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="1.2" d="M5 7h14M5 12h14M5 17h14" />
            </svg>
        </button>
    </div>

    <div :class="expanded ? ' pt-4 ' : '' " class="h-full px-3  overflow-y-auto bg-primary-light-one dark:bg-gray-800 ">
        <ul class="space-y-2 font-medium">
            <!-- Dashboard -->
            @can($ModuleEnum::DASHBOARD_ACCESS->value)
            <li>
                <a href="/dashboard"
                    class="flex items-center p-2  rounded-lg 
                            dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                            {{ request()->routeIs('dashboard') ? ' bg-[#f1f1fc] dark:bg-gray-700 text-primary ' : ' text-[#aaaaaa] ' }}
                            ">
                    <svg class="w-6 h-6
                            transition duration-75 
                            dark:text-white 
                            group-hover:text-primary 
                            dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13.5 2c-.178 0-.356.013-.492.022l-.074.005a1 1 0 0 0-.934.998V11a1 1 0 0 0 1 1h7.975a1 1 0 0 0 .998-.934l.005-.074A7.04 7.04 0 0 0 22 10.5 8.5 8.5 0 0 0 13.5 2Z" />
                        <path d="M11 6.025a1 1 0 0 0-1.065-.998 8.5 8.5 0 1 0 9.038 9.039A1 1 0 0 0 17.975 13H11V6.025Z" />
                    </svg>



                    <span
                        x-show="expanded"
                        class="ms-3 {{ request()->routeIs('dashboard') ? ' text-primary ' : ' text-[#aaaaaa] ' }} group-hover:text-primary group-hover:dark:text-white dark:text-white">Dashboard</span>
                </a>
            </li>
            @endcan



            <!-- Engineer -->
            @can($ModuleEnum::ENGINEER_LIST->value)
            <li>
                <a href="{{ route('engg.index') }}"
                    class="flex items-center p-2  rounded-lg dark:text-white hover:bg-[#f1f1fc] 
                            dark:hover:bg-gray-700 group
                            {{ request()->routeIs('engg.index') ? ' bg-[#f1f1fc] dark:bg-gray-700 text-primary' : ' text-[#aaaaaa] ' }}
                            ">
                    <svg class="flex-shrink-0 w-5 h-5  transition duration-75 dark:text-white group-hover:text-primary dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 18">
                        <path
                            d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                    </svg>
                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 whitespace-nowrap  {{ request()->routeIs('engg.index') ? ' text-primary' : ' text-[#aaaaaa] ' }} group-hover:text-primary group-hover:dark:text-white  dark:text-white">Engineers</span>
                </a>
            </li>
            @endcan

            <!-- Customer -->
            @can('customer_list')
            <li>
                <a href="{{ route('customer.index') }}"
                    class="flex items-center p-2  rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                            {{ request()->routeIs('customer.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                        ">
                    <svg class="w-6 h-6  dark:text-white  group-hover:text-primary dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 whitespace-nowrap {{ request()->routeIs('customer.index') ? '  text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white dark:text-white">Customers</span>
                </a>
            </li>
            @endcan

            <!-- Leads -->
            @can($ModuleEnum::LEAD_LIST->value)
            <li>
                <a href="{{ route('lead.index') }}"
                    class="flex items-center p-2 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                       {{ request()->routeIs('lead.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">

                    <svg class="w-6 h-6  dark:text-white  group-hover:text-primary dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>

                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 whitespace-nowrap  {{ request()->routeIs('lead.index') ? ' text-primary' : 'text-[#aaaaaa]' }} group-hover:text-primary group-hover:dark:text-white dark:text-white ">Leads</span>
                </a>
            </li>
            @endcan

            <!-- Ticket -->
            @can($ModuleEnum::TICKET_LIST->value)
            <li>
                <a href="{{ route('ticket.index') }}"
                    class="flex items-center p-2  rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                       {{ request()->routeIs('ticket.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">
                    <svg class="w-6 h-6  dark:text-white  group-hover:text-primary dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M18.5 12A2.5 2.5 0 0 1 21 9.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 1 0 5V17a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                    </svg>
                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 whitespace-nowrap   {{ request()->routeIs('ticket.index') ? 'text-primary' : 'text-[#aaaaaa]' }} group-hover:text-primary group-hover:dark:text-white dark:text-white ">Tickets</span>
                </a>
            </li>
            @endcan


            <!-- Attendance -->
            @can($ModuleEnum::ATTENDANCE_LIST->value)
            <li>
                <a href="{{ route('attendance.index') }}"
                    class="flex items-center p-2  rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                        {{ request()->routeIs('attendance.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">

                    {{-- <svg class="w-6 h-6  dark:text-white  group-hover:text-primary dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg> --}}

                    <svg class="w-6 h-6 dark:text-white  group-hover:text-primary dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                    </svg>

                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  group-hover:text-primary group-hover:dark:text-white ">Attendance</span>
                </a>
            </li>
            @endcan

            <!-- Leave -->
            @can($ModuleEnum::LEAVES_LIST->value)
            <li>
                <a href="{{ route('leave.dashboard') }}"
                    class="flex items-center p-2  rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                        {{ request()->routeIs('leave.dashboard') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">

                    <svg class="w-6 h-6 dark:text-white  group-hover:text-primary dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12.512 8.72a2.46 2.46 0 0 1 3.479 0 2.461 2.461 0 0 1 0 3.479l-.004.005-1.094 1.08a.998.998 0 0 0-.194-.272l-3-3a1 1 0 0 0-.272-.193l1.085-1.1Zm-2.415 2.445L7.28 14.017a1 1 0 0 0-.289.702v2a1 1 0 0 0 1 1h2a1 1 0 0 0 .703-.288l2.851-2.816a.995.995 0 0 1-.26-.189l-3-3a.998.998 0 0 1-.19-.26Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M7 3a1 1 0 0 1 1 1v1h3V4a1 1 0 1 1 2 0v1h3V4a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h1V4a1 1 0 0 1 1-1Zm10.67 8H19v8H5v-8h3.855l.53-.537a1 1 0 0 1 .87-.285c.097.015.233.13.277.087.045-.043-.073-.18-.09-.276a1 1 0 0 1 .274-.873l1.09-1.104a3.46 3.46 0 0 1 4.892 0l.001.002A3.461 3.461 0 0 1 17.67 11Z" clip-rule="evenodd" />
                    </svg>

                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  group-hover:text-primary group-hover:dark:text-white ">Leaves</span>
                </a>
            </li>
            @endcan

            <!-- overtime request -->

            <!-- <li>
                <a href="{{ route('overtime.index') }}"
                    class="flex items-center p-2  rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                        {{ request()->routeIs('overtime.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">
                    <svg class="w-6 h-6  dark:text-white  group-hover:text-primary dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>

                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  group-hover:text-primary group-hover:dark:text-white ">Overtimes</span>
                </a>
            </li> -->

            <!-- Payouts -->

            <!-- <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-[#f1f1fc] dark:text-white dark:hover:bg-gray-700"
                    aria-controls="dropdown-payout"
                    data-collapse-toggle="dropdown-payout"
                    <?php echo request()->routeIs('payout.index') || request()->routeIs('payout.show') ? ' aria-expanded="true" ' : ''; ?>>
                    <svg class="w-6 h-6 text-[#aaaaaa]  text-[#aaaaaa] group-hover:text-primary group-hover:dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                    </svg>
                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 text-left rtl:text-right w-6 h-6 whitespace-nowrap text-[#aaaaaa]  group-hover:text-primary group-hover:dark:text-white">
                        Payouts </span>
                    <svg x-show="expanded" class="w-3 h-3 text-white  group-hover:text-primary group-hover:dark:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-payout" class="{{ request()->routeIs('payout.index') || request()->routeIs('payout.show') ? '' : ' hidden ' }} py-2  space-y-2">
                    <li class="ml-5">
                        <a href="{{ route('payout.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                                {{ request()->routeIs('payout.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                               ">
                            <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span
                                class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  {{ request()->routeIs('payout.index') ? ' text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white ">Eng.Pay Slip</span>
                        </a>
                    </li>
                    <li class="ml-5">
                        <a href="{{ route('alleng.payout') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                                    {{ request()->routeIs('alleng.payout') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                                ">
                            <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span
                                class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  
                                    group-hover:text-primary {{ request()->routeIs('alleng.payout') ? ' text-primary' : 'text-[#aaaaaa]' }}  group-hover:dark:text-white">Eng. Payout</span>
                        </a>
                    </li>
                </ul>
            </li> -->

            @can($ModuleEnum::EngineerPayouts_LIST->value)
            <li>
                <a href="{{ route('alleng.payout') }}"
                    class="flex items-center p-2  rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                        {{ request()->routeIs('alleng.payout') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">

                    <svg class="w-6 h-6  group-hover:text-primary group-hover:dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                    </svg>

                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  group-hover:text-primary group-hover:dark:text-white ">Engineer Payouts</span>
                </a>
            </li>
            @endcan



            <!-- customer payout -->
            @can($ModuleEnum::CUSTOMER_RECEIVABLE_LIST->value)
            <li>
                <a href="{{ route('all-customer.payout') }}"
                    class="flex items-center p-2  rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                        {{ request()->routeIs('all-customer.payout') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">

                    <svg class="w-6 h-6  group-hover:text-primary group-hover:dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                    </svg>

                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  group-hover:text-primary group-hover:dark:text-white ">Customer Receivable</span>
                </a>
            </li>
            @endcan


            <!-- Invoice  -->
            {{--
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-[#f1f1fc] dark:text-white dark:hover:bg-gray-700"
                    aria-controls="dropdown-invoice"
                    data-collapse-toggle="dropdown-invoice"
                    <?php echo request()->routeIs('customer-invoice.index') || request()->routeIs('customer-invoice.show') ? ' aria-expanded="true" ' : ''; ?>>

                    <svg class="w-6 h-6 text-[#aaaaaa] dark:text-white  group-hover:text-primary dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M10 3v4a1 1 0 0 1-1 1H5m8-2h3m-3 3h3m-4 3v6m4-3H8M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM8 12v6h8v-6H8Z" />
                    </svg>

                    <span
                        x-show="expanded"
                        class="flex-1 ms-3 text-left rtl:text-right w-6 h-6 whitespace-nowrap text-[#aaaaaa]  group-hover:text-primary group-hover:dark:text-white">
                        Client Invoice </span>

                    <svg x-show="expanded" class="w-3 h-3 text-white  group-hover:text-primary group-hover:dark:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-invoice" class="{{ request()->routeIs('customer-invoice.index') || request()->routeIs('customer-invoice.show') ? '' : ' hidden ' }} py-2 space-y-2">
            <li class="ml-5">
                <a href="{{ route('customer-invoice.index') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                                    {{ request()->routeIs('customer-invoice.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                                ">
                    <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span
                        class="flex-1 ms-3 w-6 h-6 whitespace-nowrap {{ request()->routeIs('customer-invoice.index') ? '  text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white ">Invoices
                    </span>
                </a>
            </li>
            <li class="ml-5">
                <a href="{{ route('invoicing.redirect') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                                    {{ request()->routeIs('customer-invoice.show') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                                ">
                    <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span
                        class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  {{ request()->routeIs('customer-invoice.show') ? ' text-primary' : 'text-[#aaaaaa]' }} group-hover:text-primary group-hover:dark:text-white ">Receivables
                    </span>
                </a>
            </li>
        </ul>
        </li> --}}

        <!-- Notification  -->

        <!-- <li>
            <button type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-[#f1f1fc] dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-notification"
                data-collapse-toggle="dropdown-notification"
                <?php echo request()->routeIs('master.index') || request()->routeIs('holiday.index') ? ' aria-expanded="true" ' : ''; ?>>

                <svg class="w-6 h-6 text-[#aaaaaa] dark:text-white group-hover:text-primary group-hover:dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.556 8.5h8m-8 3.5H12m7.111-7H4.89a.896.896 0 0 0-.629.256.868.868 0 0 0-.26.619v9.25c0 .232.094.455.26.619A.896.896 0 0 0 4.89 16H9l3 4 3-4h4.111a.896.896 0 0 0 .629-.256.868.868 0 0 0 .26-.619v-9.25a.868.868 0 0 0-.26-.619.896.896 0 0 0-.63-.256Z" />
                </svg>


                {{-- <svg class="w-6 h-6 text-[#aaaaaa]  group-hover:text-primary group-hover:dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                    </svg> --}}

                <span
                    x-show="expanded"
                    class="flex-1 ms-3 text-left rtl:text-right w-6 h-6 whitespace-nowrap text-[#aaaaaa]  group-hover:text-primary group-hover:dark:text-white dark:text-white">Notification</span>
                <svg x-show="expanded" class="w-3 h-3 text-white  group-hover:text-primary group-hover:dark:text-white"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="m1 1 4 4 4-4" />
                </svg>

            </button>
            <ul id="dropdown-notification" class="{{ request()->routeIs('notification_template.index') || request()->routeIs('custom_template.create') ? '' : ' hidden ' }} py-2  space-y-2">
                @can($ModuleEnum::CUSTOM_NOTIFICATION_CREATE->value)
                <li class="ml-5">
                    <a href="{{ route('custom_template.create') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                       {{ request()->routeIs('master.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">
                        <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span
                            class="flex-1 ms-3 w-6 h-6 whitespace-nowrap {{ request()->routeIs('master.index') ? '   text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white ">Create Notification</span>
                    </a>
                </li>
                @endcan
                
                @can($ModuleEnum::NOTIFICATION_TEMPLATE_LIST->value)
                <li class="ml-5">
                    <a href="{{ route('notification_template.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                       {{ request()->routeIs('notification_template.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">
                        <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span
                            class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  {{ request()->routeIs('holiday.index') ? ' text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white ">Templates</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>-->

        <!-- Settings  -->

        <li>
            <button type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-[#f1f1fc] dark:text-white dark:hover:bg-gray-700"
                aria-controls="dropdown-setting"
                data-collapse-toggle="dropdown-setting"
                <?php echo request()->routeIs('master.index') || request()->routeIs('holiday.index') ? ' aria-expanded="true" ' : ''; ?>>
                <svg class="w-6 h-6 text-[#aaaaaa] dark:text-white  group-hover:text-primary group-hover:dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                </svg>

                <span
                    x-show="expanded"
                    class="flex-1 ms-3 text-left rtl:text-right w-6 h-6 whitespace-nowrap text-[#aaaaaa]  group-hover:text-primary group-hover:dark:text-white dark:text-white">Settings</span>
                <svg x-show="expanded" class="w-3 h-3 text-white  group-hover:text-primary group-hover:dark:text-white"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="m1 1 4 4 4-4" />
                </svg>
            </button>
            <ul id="dropdown-setting" class="{{ request()->routeIs('bank_list') || request()->routeIs('user_list') || request()->routeIs('role_list') || request()->routeIs('master.index') || request()->routeIs('holiday.index') ? '' : ' hidden ' }} py-2  space-y-2">
                <!-- <li class="ml-5">
                        <a href="{{ route('master.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                       {{ request()->routeIs('master.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">
                            <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span
                                class="flex-1 ms-3 w-6 h-6 whitespace-nowrap {{ request()->routeIs('master.index') ? '   text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white ">Master Data</span>
                        </a>
                    </li> -->
                @can($ModuleEnum::SETTING_HOLIDAY_LIST->value)
                <li class="ml-5">
                    <a href="{{ route('holiday.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                       {{ request()->routeIs('holiday.index') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">
                        <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span
                            class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  {{ request()->routeIs('holiday.index') ? ' text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white ">Holiday</span>
                    </a>
                </li>
                @endcan

                @can($ModuleEnum::SETTING_ROLE_LIST->value)
                <li class="ml-5">
                    <a href="{{ route('role_list') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                       {{ request()->routeIs('role_list') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">
                        <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span
                            class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  {{ request()->routeIs('role_list') ? ' text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white ">Roles</span>
                    </a>
                </li>
                @endcan

                @can($ModuleEnum::SETTING_SYSTEM_USERS_LIST->value)
                <li class="ml-5">
                    <a href="{{ route('user_list') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                       {{ request()->routeIs('user_list') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">
                        <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span
                            class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  {{ request()->routeIs('user_list') ? ' text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white ">System Users</span>
                    </a>
                </li>
                @endcan

                @can($ModuleEnum::SETTING_BANK_LIST->value)
                <li class="ml-5">
                    <a href="{{ route('bank_list') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-[#f1f1fc] dark:hover:bg-gray-700 group
                       {{ request()->routeIs('bank_list') ? ' bg-[#f1f1fc] dark:bg-gray-700  text-primary' : 'text-[#aaaaaa]' }}
                       ">
                        <svg class="w-[20px] h-[20px]  dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span
                            class="flex-1 ms-3 w-6 h-6 whitespace-nowrap  {{ request()->routeIs('bank_list') ? ' text-primary' : 'text-[#aaaaaa]' }}  group-hover:text-primary group-hover:dark:text-white ">Banks</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>

        <!-- END ASIDE BAR  -->
    </div>
</aside>

{{-- darkmode script --}}
{{-- @section('script')  --}}
<script>
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Change the icons inside the button based on previous settings
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
            '(prefers-color-scheme: dark)').matches)) {
        if (themeToggleLightIcon) {
            themeToggleLightIcon.classList.remove('hidden');
        }

    } else {
        if (themeToggleDarkIcon) {
            themeToggleDarkIcon.classList.remove('hidden');
        }

    }

    var themeToggleBtn = document.getElementById('theme-toggle');

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {

            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }

                // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }

        });
    }
</script>
{{-- @endsection  --}}