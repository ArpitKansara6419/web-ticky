<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =  [
            'id'                => 'nullable|integer',
            'customer_type'     => 'required|string|in:company,freelancer',
            'name'              => 'required|string|max:100',
            'email'             => 'required|email|unique:customers,email,' . $this->input('id'),
            'company_reg_no'    => 'nullable',
            'vat_no'            => 'nullable|integer|min:0',
            'address'           => 'nullable|string|max:200',
            'auth_person.*'       => [
                'required',
                'filled',
                'bail'
            ],
            // 'auth_person_email.*' => $this->input('customer_type') == 'company' ? 'required' : 'nullable',
            'auth_person_email.*' => [
                'required',
                'email',
                'filled',
                'bail'
            ],
            'auth_person_contact.*' => [
                'required',
                'numeric',
                'filled',
                'bail'
            ],
            'profile_image'     => 'nullable|file|mimes:jpg,jpeg,png',
            'status'            => 'required|in:0,1',
            'document'          => 'nullable|array',
        ];

        // if (is_array($this->input('auth_person'))) {
            
        // }

        // for ($i=0; $i<10; $i++) {
        //     $rules["auth_person.".$i] = ['required', 'filled'];
        //     $rules["auth_person_email.".$i] = ['required', 'filled', 'email'];
        //     $rules["auth_person_contact.".$i] = ['required', 'filled', 'numeric'];
        // }
        // dd($rules);

        //  $auth_person_count = count($this->input('auth_person', []));

        // for ($i = 0; $i < $auth_person_count; $i++) {
        //     $rules["auth_person.$i"] = ['bail', 'required', 'string', 'min:1'];
        //     $rules["auth_person_email.$i"] = ['bail', 'required', 'string', 'email'];
        //     $rules["auth_person_contact.$i"] = ['bail', 'required', 'string', 'numeric'];
        // }
        return $rules;
    }

    public function messages()
    {
        return[
            'auth_person.*.filled' => 'Auth person name is required.',
            'auth_person.*.bail' => 'Auth person name is required.',
            'auth_person.*.required' => 'Auth person name is required.',
            
            'auth_person_email.*.required' => 'Auth person email is required.',
            'auth_person_email.*.filled' => 'Auth person email is required.',
            'auth_person_email.*.bail' => 'Auth person email is required.',
            'auth_person_email.*.email' => 'Please enter valid email.',

            'auth_person_contact.*.required' => 'Auth person contact is required.',
            'auth_person_contact.*.numeric' => 'Please enter valid contact number, only numeric allowed.',    
            'auth_person_contact.*.filled' => 'Auth person contact is required.',
            'auth_person_contact.*.bail' => 'Auth person contact is required.',
        ];
    }
}
