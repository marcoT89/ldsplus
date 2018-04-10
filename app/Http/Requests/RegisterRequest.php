<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users',
            'birthday' => 'required|before:' . date('Y-m-d'),
            'gender' => 'required',
            'password' => 'required|confirmed|min:6',
            'ward_id' => 'required|exists:wards,id',
        ];
    }

    public function messages()
    {
        return [
            'birthday.before' => 'The :attribute date mus be before today'
        ];
    }

    public function attributes()
    {
        return [
            'ward_id' => 'ward'
        ];
    }
}
