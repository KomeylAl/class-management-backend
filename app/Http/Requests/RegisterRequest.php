<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id_number' => ['required', 'unique:users,id_number'],
            'national_code' => ['required', 'unique:users,national_code'],
            'phone' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'name' => ['required'],
            'password' => ['required']
        ];
    }
}
