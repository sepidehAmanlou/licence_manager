<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IranianNationalCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // حذف کاراکترهای غیرعددی
       $code = preg_replace('/[^0-9]/', '', (string) $value);

        // بررسی طول
        if (strlen($code) !== 10) {
            $fail(__('errors.invalid_national_code'));
            return;
        }

        // لیست کدهای ملی غیرواقعی و رایج برای تست
        $invalidCodes = [
            '0000000000', '1111111111', '2222222222', '3333333333',
            '4444444444', '5555555555', '6666666666', '7777777777',
            '8888888888', '9999999999', '1234567890', '0123456789',
        ];

        if (in_array($code, $invalidCodes)) {
            $fail(__('errors.invalid_national_code'));
            return;
        }

        // محاسبه رقم کنترلی (checksum)
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $code[$i] * (10 - $i);
        }

        $remainder = $sum % 11;
        $checkDigit = (int) $code[9];

        if (
            ($remainder < 2 && $checkDigit !== $remainder) ||
            ($remainder >= 2 && $checkDigit !== (11 - $remainder))
        ) {
            $fail(__('errors.invalid_national_code'));
        }
    }
}
