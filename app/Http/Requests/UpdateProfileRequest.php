<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|required_with:password|string|min:8',
            'current_password' => 'nullable|required_with:password|string',
            'gender' => 'required|in:M,F',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nif' => 'nullable|string|max:9',
            'default_delivery_address' => 'nullable|string|max:255',
            'default_payment_type' => 'nullable|string|in:Visa,PayPal,MB WAY',
            'default_payment_reference' => 'nullable|string|max:255',
            'remove_photo' => 'nullable|string|in:0,1',
        ];
    }
}
