<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Line_item extends Model
{
    protected $table = 'line_items';
    protected $fillable = [
        'order_id',
        'product_id',
        'primary_product_id',
        'quantity',
        'status',
        'product_type',
        'pre_order',
        'line_item_id',
        'price_paid',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'created_by');
    // }
}
