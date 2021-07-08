<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\User;
use App\Model\Invoice;

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
        'created_at'
    ];

    public function user()
    {
        return $this->morphedByMany(User::class, 'transactionable','transactionables');
    }

    public function event()
    {
        return $this->morphedByMany(Event::class, 'transactionable','transactionables');
    }

    public function invoice()
    {
        return $this->morphToMany(Invoice::class, 'invoiceable','invoiceables');
    }

    /*public function subscription()
    {
        return $this->morphToMany(::class, 'invoiceable','invoiceables');
    }*/

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
