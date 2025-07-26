<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IranianMobile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // حذف تمام کاراکترهای غیرعددی
        $mobile = preg_replace('/[^0-9]/', '', $value);

        // بررسی تعداد ارقام
        if (strlen($mobile) !== 11) {
            $fail(__('errors.invalid_mobile_number'));
            return;
        }

        // الگوی  شماره‌های موبایل ایران
        if (!preg_match('/^09(0[1-2]|1[0-9]|2[0-1]|3[0-9]|9[0-9])[0-9]{7}$/', $mobile)) {
            $fail(__('errors.invalid_mobile_number'));
            return;
        }
    }
}
