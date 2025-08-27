@foreach ($engineers as $engineer)
<li class="p-2 hover:bg-teal-100 dark:hover:bg-teal-700 cursor-pointer rounded-md flex items-center gap-3 
                         {{ empty($engineer['job_type']) ? 'opacity-50 cursor-not-allowed' : '' }}"
    data-value="{{ $engineer['id'] }}"
    data-name="{{ $engineer['name'] }}"
    data-image="{{ $engineer['profile'] ? asset('storage/' . $engineer['profile']) : asset('user_profiles/user/user.png') }}"
    data-job-type="{{ $engineer['job_type'] ?? '' }}"
    data-currency="{{ !empty($engineer['engineer_currency']) ? $currencySymbols[$engineer['engineer_currency']] : '' }}">

    <img class="w-10 h-10 rounded-full object-cover shadow-md border border-gray-300 dark:border-gray-700"
        src="{{ $engineer['profile'] ? asset('storage/' . $engineer['profile']) : asset('user_profiles/user/user.png') }}"
        alt="Avatar">

    <div class="flex flex-col text-[1rem]">
        <p class="capitalize font-medium text-gray-900 dark:text-white">
            {{ $engineer['name'] }}
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            <span class="font-medium text-gray-700 dark:text-gray-300">
                {{ $engineer['code'] }}
            </span>
            <span class="mx-1 text-gray-400">•</span>
            <span class="text-blue-800 dark:text-blue-400 font-medium uppercase">
                {{ ucwords(str_replace('_', ' ', $engineer['job_type'])) }}
            </span>
            <span class="mx-1 text-gray-400">•</span>
            <span class="text-blue-800 dark:text-blue-400 font-medium uppercase">
                {{ !empty($engineer['engineer_currency']) ? $currencySymbols[$engineer['engineer_currency']] : '' }}
            </span>
        </p>
    </div>
</li>
@endforeach