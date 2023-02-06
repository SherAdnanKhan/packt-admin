<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_ref',
        'order_id',
        'user_id',
        'created_by',
        'invoiceNumber',
        'printingHouse',
        'customerGroup',
        'price_paid',
    ];
}
