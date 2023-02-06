<?php

namespace App\Http\Composers;

class NewAccountComposer
{
    public function compose($view)
    {
        $title = 'Create New Account';

        $form_fields = [
            [
                'label' => 'First Name',
                'type' => 'text',
                'id' => 'firstName',
                'required' => '1',
                'placeholder' => 'John',
            ],
            [
                'label' => 'Last Name',
                'type' => 'text',
                'id' => 'lastName',
                'required' => '1',
                'placeholder' => 'Dep',
            ],
            [
                'label' => 'E-mail Address',
                'type' => 'email',
                'id' => 'email',
                'required' => '1',
                'placeholder' => 'John@gmail.com',
            ],
            [
                'label' => 'Currency',
                'type' => 'text',
                'id' => 'currency',
                'required' => '0',
                'placeholder' => '',
            ],
            [
                'label' => 'Password',
                'type' => 'password',
                'id' => 'password',
                'required' => '1',
                'placeholder' => 'John@123',
            ],
            [
                'label' => 'Confirm Password',
                'type' => 'password',
                'id' => 'passwordConfirmation',
                'required' => '1',
                'placeholder' => '',
            ],
            [
                'label' => 'Company Name',
                'type' => 'text',
                'id' => 'companyName',
                'required' => '0',
                'placeholder' => 'JohnEnterprise',
            ],
            [
                'label' => 'NetSuite ID',
                'type' => 'text',
                'id' => 'netsuiteId',
                'required' => '0',
                'placeholder' => 'TSTDRV147601',
            ],
            [
                'label' => 'VAT no.',
                'type' => 'text',
                'id' => 'vat',
                'required' => '0',
                'placeholder' => '74744554455',
            ],
            [
                'label' => 'Discount Group',
                'type' => 'text',
                'id' => 'discountGroup',
                'required' => '0',
                'placeholder' => '',
            ],
        ];

        $view->with('data', compact('title', 'form_fields'));
    }
}
