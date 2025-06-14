<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isBoard();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $member = User::TYPE_MEMBER;
        $employee = User::TYPE_EMPLOYEE;
        $board = User::TYPE_BOARD;
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
            'type' => "required|string|in:$member,$employee,$board",
        ];
    }
}
