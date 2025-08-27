   {{-- <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">  --}}

   <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
       <div class="px-3 py-3 lg:px-5 lg:pl-3">

           <div class="flex items-center justify-between">
               <div class="flex items-center justify-start rtl:justify-end">
                   <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                       type="button"
                       class="inline-flex items-center p-2 text-sm text-white rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                       <span class="sr-only">Open sidebar</span>
                       <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                           xmlns="http://www.w3.org/2000/svg">
                           <path clip-rule="evenodd" fill-rule="evenodd"
                               d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                           </path>
                       </svg>
                   </button>
                   <a href="/" class="flex ms-2 md:me-24">
                       <img src="/assets/ticky-logo-new.png" class="h-10 me-0" alt="FlowBite Logo" />
                       <span
                           class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white text-primary">Ticky</span>
                   </a>
               </div>

               <div class="flex items-center">
                   <div class="flex items-center ms-3">

                       <div class="flex gap-2 items-center">
                           {{-- theme change button  --}}
                           <button id="theme-toggle" type="button"
                               class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                               <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor"
                                   viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                               </svg>
                               <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                                   viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                   <path
                                       d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                       fill-rule="evenodd" clip-rule="evenodd"></path>
                               </svg>
                           </button>

                           {{-- user profile  --}}
                           <button type="button"
                               class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                               aria-expanded="false" data-dropdown-toggle="dropdown-user">
                               <span class="sr-only">Open user menu</span>
                               <img class="w-8 h-8 rounded-full"
                                   src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                           </button>
                       </div>

                       {{-- dropdown  --}}
                       <div class="z-50 hidden my-5 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                           id="dropdown-user">
                           <div class="px-4 py-3" role="none">
                               <p class="text-sm text-gray-900 dark:text-white" role="none">
                                   {{ Auth::user()->name }}
                               </p>
                               <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                   {{ Auth::user()->email }}
                               </p>
                           </div>
                           <ul class="py-1" role="none">
                               <li>
                                   <a href="/dashboard"
                                       class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                       role="menuitem">Dashboard</a>
                               </li>
                               <!-- <li>
                              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
                            </li> -->
                               <li>
                                   <a href="/profile"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                       role="menuitem">Profile</a>
                               </li>
                               <li>
                                   <a href="/change-password"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                       role="menuitem">Change Password</a>
                               </li>
                               <li>
                                   <form action="{{ route('logout') }}" method="POST">
                                       @csrf
                                       <button
                                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                           role="menuitem">Sign out</a>
                                   </form>
                               </li>
                           </ul>
                       </div>

                   </div>
               </div>
           </div>
       </div>
   </nav>

   <aside id="logo-sidebar"
       class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-primary-light-one border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
       aria-label="Sidebar">
       <div class="h-full px-3 pb-4 overflow-y-auto bg-primary-light-one dark:bg-gray-800 ">
           <ul class="space-y-2 font-medium">
               <li>
                   <a href="/dashboard"
                       class="flex items-center p-2 text-gray-900 rounded-lg 
                            dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                            {{ request()->routeIs('dashboard') ? ' bg-gray-400 dark:bg-gray-700 text-primary ' : ' text-white ' }}
                            ">
                       <svg class="w-5 h-5  
                            transition duration-75 
                            dark:text-gray-400 
                            group-hover:text-gray-900 
                            dark:group-hover:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                           viewBox="0 0 22 21">
                           <path
                               d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                           <path
                               d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                       </svg>
                       <span
                           class="ms-3 text-white group-hover:text-gray-900 group-hover:dark:text-white">Dashboard</span>
                   </a>
               </li>

               <!-- <p class=" p-2 pb-0 rounded-lg font-bold text-sm text-teal-400  dark:text-teal-500 ">
            Users
         </p> -->

               <li>
                   <a href="{{ route('engg.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 
                            dark:hover:bg-gray-700 group
                            {{ request()->routeIs('engg.index') ? ' bg-gray-400 dark:bg-gray-700 text-primary' : ' text-white ' }}
                            ">
                       <svg class="flex-shrink-0 w-5 h-5  transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                           viewBox="0 0 20 18">
                           <path
                               d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white  ">Engineers</span>
                   </a>
               </li>

               <li>
                   <a href="{{ route('customer.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                            {{ request()->routeIs('customer.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                        ">
                       <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                           fill="currentColor" viewBox="0 0 24 24">
                           <path fill-rule="evenodd"
                               d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z"
                               clip-rule="evenodd" />
                       </svg>
                       <span
                           class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white">Customers</span>
                   </a>
               </li>

               <li>
                   <a href="{{ route('lead.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                       {{ request()->routeIs('lead.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                       ">
                       <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                           fill="currentColor" viewBox="0 0 24 24">
                           <path fill-rule="evenodd"
                               d="M18 5.05h1a2 2 0 0 1 2 2v2H3v-2a2 2 0 0 1 2-2h1v-1a1 1 0 1 1 2 0v1h3v-1a1 1 0 1 1 2 0v1h3v-1a1 1 0 1 1 2 0v1Zm-15 6v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8H3ZM11 18a1 1 0 1 0 2 0v-1h1a1 1 0 1 0 0-2h-1v-1a1 1 0 1 0-2 0v1h-1a1 1 0 1 0 0 2h1v1Z"
                               clip-rule="evenodd" />
                       </svg>
                       <span
                           class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Leads</span>
                   </a>
               </li>

               <li>
                   <a href="{{ route('ticket.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                       {{ request()->routeIs('ticket.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                       ">
                       <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                           fill="none" viewBox="0 0 24 24">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                               stroke-width="2"
                               d="M18.5 12A2.5 2.5 0 0 1 21 9.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 1 0 5V17a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                       </svg>
                       <span
                           class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Tickets</span>
                   </a>
               </li>

               <li>
                   <button type="button"
                       class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                       aria-controls="dropdown-leave-attendance"
                       data-collapse-toggle="dropdown-leave-attendance"
                       <?php echo request()->routeIs('leaves.index') || request()->routeIs('attendance.index') ? ' aria-expanded="true" ' : ''; ?>>
                       <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                           fill="none" viewBox="0 0 24 24">
                           <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                               d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                       </svg>
                       <span
                           class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white"> Attendance</span>
                       <svg class="w-3 h-3 text-white  group-hover:text-gray-900 group-hover:dark:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                               stroke-width="2" d="m1 1 4 4 4-4" />
                       </svg>
                   </button>
                   <ul id="dropdown-leave-attendance" class="{{ request()->routeIs('leaves.index') || request()->routeIs('attendance.index') ? '' : ' hidden ' }} py-2  space-y-2">
                       <li class="ml-5">
                           <a href="{{ route('leaves.index') }}"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                                {{ request()->routeIs('leaves.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                               ">
                               <svg class="w-[20px] h-[20px] text-gray-200 dark:text-white" aria-hidden="true"
                                   xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                   fill="currentColor" viewBox="0 0 24 24">
                                   <path fill-rule="evenodd"
                                       d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                       clip-rule="evenodd" />
                               </svg>
                               <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Engineer Leaves</span>
                           </a>
                       </li>

                       <li class="ml-5">
                           <a href="{{ route('attendance.index') }}"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                                {{ request()->routeIs('attendance.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                               ">
                               <svg class="w-[20px] h-[20px] text-gray-200 dark:text-white" aria-hidden="true"
                                   xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                   fill="currentColor" viewBox="0 0 24 24">
                                   <path fill-rule="evenodd"
                                       d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                       clip-rule="evenodd" />
                               </svg>
                               <span
                                   class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Engineer Attendance</span>
                           </a>
                       </li>

                   </ul>
               </li>

               <!-- overtime request -->

               <li>
                   <a href="{{ route('overtime.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                        {{ request()->routeIs('overtime.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                       ">
                       <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                           fill="none" viewBox="0 0 24 24">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                               stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                       </svg>

                       <span
                           class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Overtimes</span>
                   </a>
               </li>

               <!-- Payouts -->

               <li>
                   <button type="button"
                       class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                       aria-controls="dropdown-payout"
                       data-collapse-toggle="dropdown-payout"
                       <?php echo request()->routeIs('payout.index') || request()->routeIs('payout.show') ? ' aria-expanded="true" ' : ''; ?>>
                       <svg class="w-6 h-6 text-white  group-hover:text-gray-900 group-hover:dark:text-white "
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                           width="24" height="24" fill="none"
                           viewBox="0 0 24 24">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m4 10v-2m3 2v-6m3 6v-3m4-11v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                       </svg>
                       <span
                           class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white">
                           Payouts </span>
                       <svg class="w-3 h-3 text-white  group-hover:text-gray-900 group-hover:dark:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                               stroke-width="2" d="m1 1 4 4 4-4" />
                       </svg>
                   </button>
                   <ul id="dropdown-payout" class="{{ request()->routeIs('payout.index') || request()->routeIs('payout.show') ? '' : ' hidden ' }} py-2  space-y-2">
                       <li class="ml-5">
                           <a href="{{ route('payout.index') }}"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                                {{ request()->routeIs('payout.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                               ">
                               <svg class="w-[20px] h-[20px] text-gray-200 dark:text-white" aria-hidden="true"
                                   xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                   fill="currentColor" viewBox="0 0 24 24">
                                   <path fill-rule="evenodd"
                                       d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                       clip-rule="evenodd" />
                               </svg>
                               <span
                                   class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Eng.Pay Slip</span>
                           </a>
                       </li>
                       <li class="ml-5">
                           <a href="{{ route('engineer-ticket-payout.redirect') }}"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                                    {{ request()->routeIs('payout.show') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                                ">
                               <svg class="w-[20px] h-[20px] text-gray-200 dark:text-white" aria-hidden="true"
                                   xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                   fill="currentColor" viewBox="0 0 24 24">
                                   <path fill-rule="evenodd"
                                       d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                       clip-rule="evenodd" />
                               </svg>
                               <span
                                   class="flex-1 ms-3 whitespace-nowrap text-white  
                                    group-hover:text-gray-900 group-hover:dark:text-white">Eng. Payout</span>
                           </a>
                       </li>
                   </ul>
               </li>

               <!-- Invoice  -->

               <li>
                   <button type="button"
                       class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                       aria-controls="dropdown-invoice"
                       data-collapse-toggle="dropdown-invoice"
                       <?php echo request()->routeIs('customer-invoice.index') || request()->routeIs('customer-invoice.show') ? ' aria-expanded="true" ' : ''; ?>>
                       <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                           fill="none" viewBox="0 0 24 24">
                           <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                               d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                       </svg>
                       <span
                           class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white">
                           Invoice </span>
                       <svg class="w-3 h-3 text-white  group-hover:text-gray-900 group-hover:dark:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                               stroke-width="2" d="m1 1 4 4 4-4" />
                       </svg>
                   </button>
                   <ul id="dropdown-invoice" class="{{ request()->routeIs('customer-invoice.index') || request()->routeIs('customer-invoice.show') ? '' : ' hidden ' }} py-2  space-y-2">
                       <li class="ml-5">
                           <a href="{{ route('customer-invoice.index') }}"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                                    {{ request()->routeIs('customer-invoice.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                                ">
                               <svg class="w-[20px] h-[20px] text-gray-200 dark:text-white" aria-hidden="true"
                                   xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                   fill="currentColor" viewBox="0 0 24 24">
                                   <path fill-rule="evenodd"
                                       d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                       clip-rule="evenodd" />
                               </svg>
                               <span
                                   class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Customer Pay Slips
                               </span>
                           </a>
                       </li>
                       <li class="ml-5">
                           <a href="{{ route('invoicing.redirect') }}"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                                    {{ request()->routeIs('customer-invoice.show') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                                ">
                               <svg class="w-[20px] h-[20px] text-gray-200 dark:text-white" aria-hidden="true"
                                   xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                   fill="currentColor" viewBox="0 0 24 24">
                                   <path fill-rule="evenodd"
                                       d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                       clip-rule="evenodd" />
                               </svg>
                               <span
                                   class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Client Invoices
                               </span>
                           </a>
                       </li>
                   </ul>
               </li>

               <!-- Settings  -->

               <li>
                   <button type="button"
                       class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                       aria-controls="dropdown-setting"
                       data-collapse-toggle="dropdown-setting"
                       <?php echo request()->routeIs('master.index') || request()->routeIs('holiday.index') ? ' aria-expanded="true" ' : ''; ?>>
                       <svg class="w-6 h-6 text-white  group-hover:text-gray-900 group-hover:dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                       </svg>

                       <span
                           class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white">Settings</span>
                       <svg class="w-3 h-3 text-white  group-hover:text-gray-900 group-hover:dark:text-white"
                           aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                               stroke-width="2" d="m1 1 4 4 4-4" />
                       </svg>
                   </button>
                   <ul id="dropdown-setting" class="{{ request()->routeIs('master.index') || request()->routeIs('holiday.index') ? '' : ' hidden ' }} py-2  space-y-2">
                       <li class="ml-5">
                           <a href="{{ route('master.index') }}"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                       {{ request()->routeIs('master.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                       ">
                               <svg class="w-[20px] h-[20px] text-gray-200 dark:text-white" aria-hidden="true"
                                   xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                   fill="currentColor" viewBox="0 0 24 24">
                                   <path fill-rule="evenodd"
                                       d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                       clip-rule="evenodd" />
                               </svg>
                               <span
                                   class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Master Data</span>
                           </a>
                       </li>

                       <li class="ml-5">
                           <a href="{{ route('holiday.index') }}"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                       {{ request()->routeIs('holiday.index') ? ' bg-gray-400 dark:bg-gray-700 ' : '' }}
                       ">
                               <svg class="w-[20px] h-[20px] text-gray-200 dark:text-white" aria-hidden="true"
                                   xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                   fill="currentColor" viewBox="0 0 24 24">
                                   <path fill-rule="evenodd"
                                       d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6l-8-6Z"
                                       clip-rule="evenodd" />
                               </svg>
                               <span
                                   class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Holiday</span>
                           </a>
                       </li>
                   </ul>
               </li>



               <!-- END ASIDE BAR  -->

               <!-- <li>
            <a href="{{ route('block.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white"  " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M19.003 3A2 2 0 0 1 21 5v2h-2V5.414L17.414 7h-2.828l2-2h-2.172l-2 2H9.586l2-2H9.414l-2 2H3V5a2 2 0 0 1 2-2h14.003ZM3 9v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9H3Zm2-2.414L6.586 5H5v1.586Zm4.553 4.52a1 1 0 0 1 1.047.094l4 3a1 1 0 0 1 0 1.6l-4 3A1 1 0 0 1 9 18v-6a1 1 0 0 1 .553-.894Z" clip-rule="evenodd"/>
                </svg>
               <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Event Blocker</span>
            </a>
         </li> -->

               {{-- <p class=" p-2 pb-0 rounded-lg font-bold text-sm text-teal-400  dark:text-teal-500 ">
            CLUB
         </p>
        
          <li>
             <a href="/club" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">          
                <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z" clip-rule="evenodd"/>
                </svg>                
                <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white  ">Clubs</span>
             </a>
         </li>

         <p class=" p-2 pb-0 rounded-lg font-bold text-sm text-teal-400  dark:text-teal-500 ">
            USERS
         </p>
        
          <li>
             <a href="/customer" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                   <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white  ">Customers</span>
             </a>
          </li>
          <li>
             <a href="/admin" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
               </svg>
                <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Admins</span>
             </a>
          </li>

         <p class=" p-2 pb-0 rounded-lg font-bold text-sm text-teal-400  dark:text-teal-500">
            SPORTS
         </p>

          <li>
             <a href="/sports" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M12 2a10 10 0 1 0 10 10A10.009 10.009 0 0 0 12 2Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.093 20.093 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM10 3.707a8.82 8.82 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.755 45.755 0 0 0 10 3.707Zm-6.358 6.555a8.57 8.57 0 0 1 4.73-5.981 53.99 53.99 0 0 1 3.168 4.941 32.078 32.078 0 0 1-7.9 1.04h.002Zm2.01 7.46a8.51 8.51 0 0 1-2.2-5.707v-.262a31.641 31.641 0 0 0 8.777-1.219c.243.477.477.964.692 1.449-.114.032-.227.067-.336.1a13.569 13.569 0 0 0-6.942 5.636l.009.003ZM12 20.556a8.508 8.508 0 0 1-5.243-1.8 11.717 11.717 0 0 1 6.7-5.332.509.509 0 0 1 .055-.02 35.65 35.65 0 0 1 1.819 6.476 8.476 8.476 0 0 1-3.331.676Zm4.772-1.462A37.232 37.232 0 0 0 15.113 13a12.513 12.513 0 0 1 5.321.364 8.56 8.56 0 0 1-3.66 5.73h-.002Z" clip-rule="evenodd"/>
                </svg>
                
                <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Sports</span>
             </a>
          </li>

          <li>
            <a href="{{route('session.create')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-white dark:text-white  group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900 group-hover:dark:text-white ">Sport Sessions</span>
               <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
               </a>
               </li> --}}

               {{-- <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900">Inbox</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-primary-light-one dark:text-blue-300">3</span>
            </a>
         </li>  --}}

               {{-- <li>
             <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                   <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                   <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"/>
                   <path d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap text-white  group-hover:text-gray-900">Sign Up</span>
             </a>
          </li>  --}}

           </ul>
           <!-- <div id="dropdown-cta" class="p-4 mt-6 rounded-lg bg-blue-300 dark:bg-primary-light-one" role="alert">
          <div class="flex items-center mb-3">
             <span class="bg-orange-100 text-orange-800 text-sm font-semibold me-2 px-2.5 py-0.5 rounded dark:bg-orange-200 dark:text-orange-900">Beta</span>
             <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 inline-flex justify-center items-center w-6 h-6 text-blue-900 rounded-lg focus:ring-2 focus:ring-blue-400 p-1 hover:bg-blue-200 dark:bg-primary-light-one dark:text-blue-400 dark:hover:bg-blue-800" data-dismiss-target="#dropdown-cta" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-2.5 h-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
             </button>
          </div>
          <p class="mb-3 text-sm text-blue-800 dark:text-blue-400">
             The admin user panel is in working stage and pending, for anykind  of query contact us !
          </p>
          <a class="text-sm text-blue-800 underline font-medium hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" href="#">info@aimbizit.com</a>
       </div> -->
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
           themeToggleLightIcon.classList.remove('hidden');
       } else {
           themeToggleDarkIcon.classList.remove('hidden');
       }

       var themeToggleBtn = document.getElementById('theme-toggle');

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
   </script>
   {{-- @endsection  --}}