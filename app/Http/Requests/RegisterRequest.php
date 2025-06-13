<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'gender' => 'required|in:M,F',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'nif' => 'nullable|string|max:9',
            'default_delivery_address' => 'nullable|string|max:255',
            'default_payment_type' => 'nullable|string|in:Visa,PayPal,MB WAY',
            'default_payment_reference' => 'nullable|string|max:255',
            'cvv' => 'nullable|required_if:default_payment_type,Visa|string|max:3',
            'terms' => 'required|accepted',
        ];
    }
}
