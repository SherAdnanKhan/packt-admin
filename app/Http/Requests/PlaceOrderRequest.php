<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userLname' => 'string',
            'bill_line1' => 'string|required',
            'bill_city' => 'required',
            'bill_state' => 'string|max:200|nullable',
            'bill_country' => 'required',
            'bill_stateCode' => 'nullable|string|regex:/^([A-Za-z]){2,2}$/',
            'bill_telephone' => 'string|regex:/^([0-9]){10,15}$/',
            'product_type' => 'required',
            'userEmail' => 'required|email',
            'emailInput' => 'email|required',
            'userFname' => 'required',
            'productList' => 'required',
        ];
    }
}
