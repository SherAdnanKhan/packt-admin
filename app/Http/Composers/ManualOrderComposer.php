<?php

namespace App\Http\Composers;

class ManualOrderComposer
{
    public function compose($view)
    {
        $products_footer_labels = [
            [
                'label' => 'Subtotal',
                'name'  => 'Subtotal',
                'id' => 'subtotal',
            ],
            [
                'label' => 'Discount',
                'name'  => 'Discount',
                'id' => 'discount',
            ],
            [
                'label' => 'Shipping',
                'name'  => 'Shipping',
                'id' => 'shipping',
            ],
            [
                'label' => 'Grand Total',
                'name'  => 'Grand Total',
                'id' => 'grand-total',
            ],
        ];

        $product_info = [
            [
                'label' => 'Title',
                'type' => 'text',
                'id' => 'title',
                'required' => '0',
            ],
            [
                'label' => 'Type',
                'type' => 'text',
                'id' => 'type',
                'required' => '0',
            ],
            [
                'label' => 'ISBN',
                'type' => 'text',
                'id' => 'isbn-13',
                'required' => '0',
            ],
            [
                'label' => 'Quantity',
                'type' => 'text',
                'id' => 'quantity',
                'required' => '0',
            ],
            [
                'label' => 'List Price',
                'type' => 'text',
                'id' => 'list-price',
                'required' => '0',
            ],
        ];

        $vars = [
            'products_footer_labels' => $products_footer_labels,
            'product_info' => $product_info,
        ];

        $view->with('vars', $vars);
    }
}
