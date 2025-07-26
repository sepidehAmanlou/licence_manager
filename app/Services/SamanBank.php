<?php

namespace App\Services;

use App\Http\Controllers\BankMockController;
use Exception;

class SamanBank extends Bank
{
    protected function requestToken(): string
    {
        try {
            // مستقیماً صدا زدن کنترلر یا سرویس داخلی (نه HTTP)
            $data = app(BankMockController::class)->samanToken()->getData(true);

            return $data['token']
                ?? throw new Exception('Token not found in SamanBank response');
        } catch (Exception $e) {
            throw new Exception('Failed to fetch token from SamanBank: ' . $e->getMessage(), previous: $e);
        }
    }

    protected function fetchTransactions(string $token): array
    {
        try {
            // مشابه بالا، فرض بر اینکه متد داخل controller وجود داشته باشه
            $data = app(BankMockController::class)->samanTransactions($token)->getData(true);

            return $data['transactions']
                ?? throw new Exception('Transactions not found in SamanBank response');
        } catch (Exception $e) {
            throw new Exception('Failed to fetch transactions from SamanBank: ' . $e->getMessage(), previous: $e);
        }
    }
}

