<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $guarded = ['id'];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            if (!$order->uuid) {
                $order->uuid = (string) Str::uuid();
            }

            // ORDER NUMBER (SAFE)
            // $lastNumber = DB::table('orders')
            //     ->lockForUpdate()
            //     ->max(DB::raw("CAST(SUBSTRING(order_number, 5) AS UNSIGNED)"));
            // $next = $lastNumber ? $lastNumber + 1 : 10001;
            // $order->order_number = 'ORD-' . $next;

        });
    }
}