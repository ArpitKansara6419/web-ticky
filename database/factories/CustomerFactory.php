<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customerType = $this->faker->randomElement(['company', 'freelancer']);
        return [
            'name'               => $this->faker->name(),
            'customer_code'      => $this->faker->numerify('AIM-C-####'),
            'customer_type'      => $customerType,
            'company_reg_no'     => $customerType === 'company' ? $this->faker->numerify('CRN###') : null,
            'address'            => $this->faker->optional()->address(),
            'vat_no'             => $customerType === 'company' ? $this->faker->bothify('VAT#######') : null,
            'email'              => $this->faker->unique()->safeEmail(),
            'auth_person'        => $this->faker->name(),
            'auth_person_email'  => $customerType === 'company' ? $this->faker->unique()->safeEmail() : null,
            'auth_person_contact'=> $this->faker->numerify('+1-###-###-####'),
            'status'             => $this->faker->randomElement([0, 1]),
            'profile_image'      => null,
            'doc_ref'            => null,
            'created_at'         => now(),
            'updated_at'         => now(),
        ];
    }
}
