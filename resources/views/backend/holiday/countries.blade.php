@foreach ($response as $country)
    <div class="mb-2 flex justify-between col-span-1 md:col-span-2 country-list">
        <div>
            <p>
                {{ $country['flag_unicode'] }}
                <strong class="country-name"> {{ $country['country_name'] }} : </strong>
                <span>{{ $country['iso-3166'] }}</span>
                <br />
                <strong>Holidays : </strong> {{ $country['total_holidays'] }}

            </p>
        </div>
        <div class="">
            <button type="button" {{ $country['is_sync'] ? 'disabled' : '' }}
                data-country="{{ $country['country_name'] }}" data-year="{{ $year }}" data-flag_unicode="{{ $country['flag_unicode'] }}"
                data-uuid="{{ $country['uuid'] }}"
                data-country_code="{{ $country['iso-3166'] }}"
                class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sync_holiday_country {{ $country['is_sync'] ? 'disabled:opacity-50 disabled:cursor-not-allowed' : '' }}">
                Sync
            </button>
        </div>

    </div>
@endforeach
