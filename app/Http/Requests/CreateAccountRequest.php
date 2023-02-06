<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
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
            'firstName' => 'required|alpha',
            'lastName' => 'required|alpha',
            'email' => 'required|email',
            'password' => 'required',
            'passwordConfirmation' => 'required_with:password|same:password|required',
            'vatNo' => 'nullable|alpha_num|between:0,10',
            'g-recaptcha-response' => 'required',
            'discountGroup' => 'required|numeric|between:0,100',
            'companyName' => 'required|alpha',
            'ship_line1' => 'string|max:35|nullable',
            'ship_line2' => 'string|max:35|nullable',
            'ship_city' => 'string|max:30|nullable',
            'ship_state' => 'string|max:200|nullable',
            'ship_postalCode' => 'string|max:9|nullable',
            'ship_telephone' => 'string|regex:/^([0-9]){10,15}$/|nullable',
            'bill_line1' => 'string|required|max:35',
            'bill_line2' => 'string|required|max:35',
            'bill_city' => 'string|required|max:30',
            'bill_state' => 'string|max:200|nullable',
            'bill_postalCode' => 'string|required|max:9',
            'bill_country' => 'required',
            'bill_telephone' => 'string|required|regex:/^([0-9]){10,15}$/',
        ];
    }
}
