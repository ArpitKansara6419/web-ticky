<?php

namespace App\Rules;

use App\Models\Engineer;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class EngineerUniqueMobileRule implements ValidationRule
{
    public $dialCode;

    public $iso2;

    public $engineer_id;

    public function __construct($dialCode, $iso2, $engineer_id = null)
    {
        $this->dialCode = $dialCode;
        $this->iso2 = $iso2;
        $this->engineer_id = $engineer_id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {

            $phoneNumber = $phoneUtil->parse($value, strtoupper($this->iso2)); // null for no specific region

            if (! $phoneUtil->isValidNumber($phoneNumber)) {
                $fail('The '.$attribute.' field contains an invalid phone number.');
            }

            $number = $phoneNumber->getNationalNumber();

            $query = Engineer::where('contact', '=', $number);

            if ($this->engineer_id) {

                $query->where('id', '<>', $this->engineer_id);
            }

            $existingUser = $query->first();

            if ($existingUser) {
                $fail('The '.$attribute.' is already exist.');
            }
        } catch (NumberParseException $e) {
            $fail('The '.$attribute.' field contains an invalid phone number.');
        }
    }
}
