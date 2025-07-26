<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class BankMockController extends Controller
{
    public function mellatToken()
    {
        return response()->json([
            'result' => [
                'data' => [
                    'access_token' => 'dry564tygrdhfyu8764evhrt876uv536yv35ygv'
                ]
            ]
        ]);
    }

    public function mellatTransactions()
    {
        return response()->json([
            'transactions' => [
                ['id' => 45, 'amount' => 250000, 'datetime' => "2023-08-23 11:25:36"],
                ['id' => 44, 'amount' => -70000, 'datetime' => "2023-08-23 11:20:10"],
                ['id' => 43, 'amount' => 100000, 'datetime' => "2023-08-23 08:05:06"],
            ]
        ]);
    }

    public function samanToken()
    {
        return response()->json([
            'token' => '465yfghnguin86i9n64uin67im678inytujnu'
        ]);
    }

    public function samanTransactions()
    {
        return response()->json([
            'transactions' => [
                ['id' => 45, 'amount' => 250000, 'datetime' => "2023-08-23 11:25:36"],
                ['id' => 44, 'amount' => -70000, 'datetime' => "2023-08-23 11:20:10"],
                ['id' => 43, 'amount' => 100000, 'datetime' => "2023-08-23 08:05:06"],
            ]
        ]);
    }
}
