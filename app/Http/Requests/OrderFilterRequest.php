<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderFilterRequest extends FormRequest
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
            'per_page' => ['nullable', 'integer', 'in:20,50,100'],
            'date_range' => ['nullable', 'integer', 'in:30,180,365'],
            'sort' => ['nullable', 'string', 'in:newest,oldest,price_asc,price_desc,status'],
            'page' => ['nullable', 'integer', 'min:1']
        ];
    }
}
