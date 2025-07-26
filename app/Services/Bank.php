<?php

namespace App\Services;

use Exception;

abstract class Bank
{
    private ?string $token = null;

    /**
     * درخواست توکن از بانک
     */
    abstract protected function requestToken(): string;

    /**
     * دریافت توکن، با کش شدن در حافظه داخلی کلاس
     */
    public function getToken(): string
    {
        if (empty($this->token)) {
            $this->token = $this->requestToken();
            if (empty($this->token)) {
                throw new Exception('Failed to get token from ' . static::class);
            }
        }

        return $this->token;
    }

    /**
     * پاک کردن توکن فعلی
     */
    protected function clearToken(): void
    {
        $this->token = null;
    }

    /**
     * رفرش توکن (پاک‌سازی و دریافت مجدد)
     */
    public function refreshToken(): string
    {
        $this->clearToken();
        return $this->getToken();
    }

    /**
     * دریافت سه تراکنش آخر با استفاده از توکن
     */
    final public function getLastThreeTransactions(): array
    {
        return $this->fetchTransactions($this->getToken());
    }

    /**
     * متد پیاده‌سازی برای دریافت تراکنش‌ها
     */
    abstract protected function fetchTransactions(string $token): array;
}
