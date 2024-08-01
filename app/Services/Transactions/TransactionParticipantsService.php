<?php

namespace App\Services\Transactions;

use App\Model\Category;
use App\Model\City;
use App\Model\Delivery;
use App\Model\Event;
use App\Model\PaymentMethod;
use App\Model\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TransactionParticipantsService
{
    public function getEvents($fullTitle = false)
    {
        return Cache::remember('transactions-events' . ($fullTitle ? '-full' : ''), 10, function () use ($fullTitle) {
            return Event::query()
                ->select([
                    $fullTitle ? DB::raw("CONCAT(title,' / ', published_at) AS title") : 'title',
                    'id',
                ])
                ->orderBy('published_at', 'desc')->pluck('title', 'id');
        });
    }

    public function getPaymentMethods()
    {
        return Cache::remember('transactions-payment_methods', 10, function () {
            return PaymentMethod::pluck('method_name', 'id');
        });
    }

    public function getDeliveries()
    {
        return Cache::remember('transactions-deliveries', 10, function () {
            return Delivery::pluck('name', 'id');
        });
    }

    public function getCoupons()
    {
        return Cache::remember('transactions-coupons', 10, function () {
            return Transaction::select('coupon_code')->distinct()->pluck('coupon_code');
        });
    }

    public function getCities()
    {
        return Cache::remember('transactions-cities', 10, function () {
            // Hardcoded to the initial 3 cities, so that we don't crash the /admin/transaction/registrations page
            return City::limit(3)->oldest()->pluck('name', 'id');
        });
    }

    public function getCategories()
    {
        return Cache::remember('transactions-categories', 10, function () {
            return Category::whereHas('events')->pluck('name', 'id');
        });
    }

    public static function getValidType($type, $amount, $couponCode)
    {
        if ($type && $type != '-' && !is_numeric($type)) {
            return $type;
        }
        if ($amount > 0 || ($couponCode && $couponCode != '-')) {
            return 'Regular*';
        }

        return 'Free*';
    }
}
