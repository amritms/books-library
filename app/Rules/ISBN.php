<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ISBN implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $sanitized_value = $this->getValue($value);

        if (strlen($sanitized_value) !== 10) {
            return false;
        }

        return $this->checksumMatches($sanitized_value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must have a valid ISBN format.';
    }

    /**
     * Determine if checksum for short ISBN numbers is valid
     *
     * @return bool
     */
    private function checksumMatches($value)
    {
        return $this->getChecksum($value) % 11 === 0;
    }

    /**
     * Calculate checksum of short ISBN numbers
     *
     * @return int
     */
    private function getChecksum($value)
    {
        $checksum = 0;
        $multiplier = 10;
        foreach (str_split($value) as $digit) {
            $digit = strtolower($digit) == 'x' ? 10 : (int)$digit;
            $checksum += $digit * $multiplier;
            $multiplier--;
        }

        return $checksum;
    }


    /**
     * Prepare value to validate
     *
     * @return string
     */
    public function getValue($value)
    {
        return preg_replace("/[^0-9x]/i", '', $value);
    }
}
