<?php

namespace App\Http\Composers;

class PartialsComposer
{
    public function compose($view)
    {
        $address_types = [
            [
                'title' => 'Billing Address',
                'prefix' => 'bill_',
            ],
            [
                'title' => 'Shipping Address',
                'prefix' => 'ship_',
            ],
        ];

        $address_fields = [
            [
                'label' => 'Country',
                'type' => 'select',
                'id' => 'country',
                'required' => '1',
                'placeholder' => '',
            ],
            [
                'label' => 'State',
                'type' => 'select',
                'id' => 'state',
                'required' => '0',
                'placeholder' => '',
            ],
            [
                'label' => 'City',
                'type' => 'select',
                'id' => 'city',
                'required' => '1',
                'placeholder' => '',
            ],

            [
                'label' => '1st Line of Address',
                'type' => 'text',
                'id' => 'line1',
                'required' => '1',
                'placeholder' => 'Apt. 123 (or other secondary designation, i.e. suite, floor).',
            ],
            [
                'label' => '2nd Line of Address',
                'type' => 'text',
                'id' => 'line2',
                'required' => '1',
                'placeholder' => '321 Main Street (street address), City, State, ZIP Code',
            ],
            [
                'label' => 'Postal Code',
                'type' => 'text',
                'id' => 'postalCode',
                'required' => '1',
                'placeholder' => '400004',
            ],
            [
                'label' => 'Telephone',
                'type' => 'text',
                'id' => 'telephone',
                'required' => '1',
                'placeholder' => '0091 22 500 50 123',
            ],
        ];

        $dropdown = [
            'name' => 'discountGroup',
            'id' => 'discountGroup',
            'options' => [
                [
                    'value' => '20',
                    'text' => '20%',
                ],
                [
                    'value' => '25',
                    'text' => '25%',
                ],
                [
                    'value' => '30',
                    'text' => '30%',
                ],
                [
                    'value' => '35',
                    'text' => '35%',
                ],
                [
                    'value' => '40',
                    'text' => '40%',
                ],
                [
                    'value' => '45',
                    'text' => '45%',
                ],
                [
                    'value' => '50',
                    'text' => '50%',
                ],
                [
                    'value' => '55',
                    'text' => '55%',
                ],
                [
                    'value' => '60',
                    'text' => '60%',
                ],
            ],
        ];

        $currencydropdown = [
            'name' => 'currency',
            'id' => 'currency',
            'options' => [
                [
                    'value' => 'USD',
                    'text' => 'USD',
                ],
                [
                    'value' => 'AUD',
                    'text' => 'AUD',
                ],
                [
                    'value' => 'INR',
                    'text' => 'INR',
                ],
                [
                    'value' => 'GBP',
                    'text' => 'GBP',
                ],
                [
                    'value' => 'EUR',
                    'text' => 'EUR',
                ],
            ],
        ];

        $view->with(compact('dropdown', 'address_types', 'address_fields'));
    }
}
