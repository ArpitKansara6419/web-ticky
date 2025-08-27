<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->input('user_id'))
            ],
            'password' => [
                'nullable'
            ],
            'role_id' => [
                'required'
            ],
            'timezone' => [
                'required'
            ],
        ];
    }

    public function processData()
    {
        return [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'timezone' => $this->input('timezone'),
        ];
    }
}
