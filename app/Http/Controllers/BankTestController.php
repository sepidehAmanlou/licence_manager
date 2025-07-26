<?php

namespace App\Http\Controllers;

use App\Services\MellatBank;
use App\Services\SamanBank;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class BankTestController extends Controller
{
    use ApiResponse;

    protected MellatBank $mellatBank;
    protected SamanBank $samanBank;

    public function __construct(MellatBank $mellatBank, SamanBank $samanBank)
    {
        $this->mellatBank = $mellatBank;
        $this->samanBank = $samanBank;
    }

      /**
     * گرفتن ۳ تراکنش آخر از بانک ملت و سامان.
     */

    public function index(): JsonResponse
    {  
        try {
            $mellatTransactions = $this->mellatBank->getLastThreeTransactions();
            $samanTransactions = $this->samanBank->getLastThreeTransactions();

            return $this->output(200, 'message.data_retrieved_successfully', [
                'mellat_bank_transactions' => $mellatTransactions,
                'saman_bank_transactions' => $samanTransactions,
            ]);
        } catch (Exception $e) {
            Log::error('BankTest Error: ' . $e->getMessage());
            return $this->output(500, 'errors.failed_to_fetch_transactions');
        }
    }
}

