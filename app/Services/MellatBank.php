<?php

namespace App\Services;

use App\Http\Controllers\BankMockController;
use Exception;


class MellatBank extends Bank
{
    protected function requestToken(): string
    {
        try {
            $data = app(BankMockController::class)->mellatToken()->getData(true);

            return $data['result']['data']['access_token']
                ?? throw new Exception('Token not found in MellatBank response');
        } catch (Exception $e) {
            throw new Exception('Failed to fetch token from MellatBank: ' . $e->getMessage(), previous: $e);
        }
    }

    protected function fetchTransactions(string $token): array
    {
        try {
            $data = app(BankMockController::class)->mellatTransactions($token)->getData(true);

            return $data['transactions']
                ?? throw new Exception('Transactions not found in MellatBank response');
        } catch (Exception $e) {
            throw new Exception('Failed to fetch transactions from MellatBank: ' . $e->getMessage(), previous: $e);
        }
    }
}


