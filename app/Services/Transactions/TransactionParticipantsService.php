<?php

namespace App\Services\Transactions;

use App\Model\Category;
use App\Model\City;
use App\Model\Delivery;
use App\Model\Event;
use App\Model\PaymentMethod;
use App\Model\Transaction;
use Illuminate\Support\Facades\Cache;

class TransactionParticipantsService
{
    public function getEvents()
    {
        return Cache::remember('transactions-events', 10, function () {
            return Event::orderBy('published_at', 'desc')->pluck('title', 'id');
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
            return City::pluck('name', 'id');
        });
    }

    public function getCategories()
    {
        return Cache::remember('transactions-categories', 10, function () {
            return Category::whereHas('events')->pluck('name', 'id');
        });
    }
}
