<?php

namespace App\Model;

use App\Model\Invoice;
use App\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Subscription as Sub;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'credit_request_id',
        'payment_method_id',
        'user_id',
        'account_id',
        'payment_status',
        'payment_initial_response',
        'payment_response',
        'placement_date',
        'ip_address',
        'type',
        'status',
        'status_history',
        'is_bonus',
        'billing_details',
        'customer_notes',
        'coupon_code',
        'coupon_discount',
        'order_vat',
        'surcharge_amount',
        'discount_amount',
        'amount',
        'total_amount',
        'created_at',
        'ends_at',
        'trial',
    ];

    public function user()
    {
        return $this->morphedByMany(User::class, 'transactionable')->with('ticket');
    }

    public function event()
    {
        return $this->morphedByMany(Event::class, 'transactionable');
    }

    public function invoice()
    {
        return $this->morphToMany(Invoice::class, 'invoiceable');
    }

    public function subscription()
    {
        return $this->morphedByMany(Sub::class, 'transactionable');
    }

    public function isSubscription()
    {
        return $this->morphedByMany(Sub::class, 'transactionable');
    }

    // public function events()
    // {
    //     return $this->belongsToMany(User::class, 'event_user');
    // }

    public function getStatusHistoryAttribute($value)
    {
        if ($value) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }
}
