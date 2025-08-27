<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleStoreRequest extends FormRequest
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
                'required',
                'regex:/^[a-zA-Z\s]+$/',
                Rule::unique('roles')
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Only alphabets allowed.'
        ];
    }

    public function processData() 
    {
        return [
            'name' => $this->input('name'),
            'guard_name' => 'web'
        ];
    }
}
