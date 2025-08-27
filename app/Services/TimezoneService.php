<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;


class TimezoneService
{
    protected Collection $countries;

    public function __construct()
    {
        $this->loadCountries();
    }

    protected function loadCountries(): void
    {
        $json = File::get(public_path('country.json'));
        $this->countries = collect(json_decode($json, true));
    }

    public function getAllTimezones(): Collection
    {
        return $this->countries->map(function ($country) {
            return [
                'timezones' => $country['timezones'] ?? [],
                'name' => $country['name'] ?? null,
                'phone_code' => $country['phone_code'] ?? null,
                'iso2' => $country['iso2'] ?? null,
                'emoji' => $country['emoji'] ?? null,
            ];
        });
    }

    public function getTimezonesByCountry(string $phoneCode, string $iso2): Collection
    {
        return $this->getAllTimezones()
            ->where('phone_code', $phoneCode)
            ->where('iso2', $iso2)
            ->values();
    }
}
