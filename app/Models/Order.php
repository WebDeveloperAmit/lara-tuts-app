<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;

class Order extends Model
{
    protected $guarded = ['id'];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}