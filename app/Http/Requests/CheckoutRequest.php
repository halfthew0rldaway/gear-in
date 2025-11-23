<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:160'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['required', 'regex:/^[0-9]{8,15}$/'],
            'address_line1' => ['required', 'string', 'max:160'],
            'address_line2' => ['nullable', 'string', 'max:160'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'regex:/^[0-9]{4,10}$/'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', Rule::in(['bank_transfer', 'cod', 'ewallet'])],
            'shipping_method' => ['required', Rule::in(['standard', 'express', 'same_day'])],
        ];
    }
}
