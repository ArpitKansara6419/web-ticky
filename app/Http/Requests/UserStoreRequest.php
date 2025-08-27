<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')
            ],
            'password' => [
                'required',
            ],
            'confirm_password' => [
                'required',
                'same:password'
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
