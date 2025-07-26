<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KeyboardSellerController extends Controller
{
    use ApiResponse;
    
    public function getKeyboardSellersByCity(Request $request)
{
    $data = $request->only('city');
    $rules = [
        'city' => ['required', 'string', 'in:Zanjan,Tehran,Ardabil,Isfahan,Qazvin'],
    ];

    $validated = $this->validation($data, $rules);
    if (!$validated->isSuccessful()) {
        return $validated;
    }

    $city = $data['city'];

    // کش برای پاسخ API (۱ ساعت ذخیره می‌شه)
    $cacheKey = 'keyboard_sellers_' . strtolower($city);

    try {
        $sellers = Cache::remember($cacheKey, 3600, function () {
            $response = Http::withHeaders([
                'Referer' => 'https://my.arian.co.ir/',
            ])->timeout(5)->get('https://my.arian.co.ir/bpmsback/api/1.0/arian/arian/exercise/product-prices');

            if ($response->failed()) {
                throw new \Exception('External API request failed');
            }

            $data = $response->json();

            if (!is_array($data)) {
                throw new \Exception('Invalid service data');
            }

            return $data;
        });
    } catch (\Exception $e) {
        Log::error('External API request failed: ' . $e->getMessage());
        return $this->output(500, 'errors.external_service_error');
    }

    if (empty($sellers)) {
        return $this->output(404, 'errors.no_sellers_found');
    }

    $totalPrice = 0;
    $count = 0;
    $bestSeller = null;
    $lowestTotalCost = null;
    $sellerList = [];

    foreach ($sellers as $seller) {
        if (!isset($seller['price'], $seller['shipping'], $seller['city'], $seller['store'], $seller['product'])) {
            continue;
        }

        $shippingCost = ($seller['city'] === $city) ? 0 : (float)$seller['shipping'];
        $price = (float)$seller['price'];
        $totalCost = $price + $shippingCost;

        $totalPrice += $price;
        $count++;

        $sellerList[] = [
            'product' => $seller['product'],
            'store' => $seller['store'],
            'price' => $price,
            'city' => $seller['city'],
            'shipping' => $shippingCost,
            'total_cost' => $totalCost,
        ];

        if (is_null($lowestTotalCost) || $totalCost < $lowestTotalCost) {
            $lowestTotalCost = $totalCost;
            $bestSeller = [
                'store' => $seller['store'],
                'product' => $seller['product'],
                'price' => $price,
                'shipping_cost' => $shippingCost,
                'city' => $seller['city'],
                'total_cost' => $totalCost,
            ];
        }
    }

    if ($count === 0) {
        return $this->output(404, 'errors.no_valid_sellers_found');
    }

    $averagePrice = round($totalPrice / $count, 2);

    // ساخت داده برای ذخیره
    $logData = [
        'timestamp' => now()->toDateTimeString(),
        'city' => $city,
        'average_price' => $averagePrice,
        'best_seller' => $bestSeller,
    ];

    try {
        $filePath = 'keyboard_sellers_log.jsonl';
        $jsonLine = json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
        Storage::append($filePath, $jsonLine);
    } catch (\Exception $e) {
        Log::error('Storage Write Error: ' . $e->getMessage());
        return $this->output(500, 'errors.storage_write_error');
    }

    return $this->output(200, 'errors.data_received_and_saved', [
        'timestamp' => now()->toDateTimeString(),
        'city' => $city,
        'average_price' => $averagePrice,
        'best_seller' => $bestSeller,
        'sellers' => $sellerList,
    ]);
}

}
