<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['role' => 'superadmin', 'name' => 'John Doe', 'email' => 'superadmin@gmail.com', 'password' => '12345678', 'contact' => '858585656565', 'status' => 1],
            ['role' => 'admin', 'name' => 'Jane Smith', 'email' => 'admin@gmail.com', 'password' => '12345678', 'contact' => '8885566655', 'status' => 1],
            ['role' => 'user', 'name' => 'Ahmed Khan', 'email' => 'ahmed.khan@gmail.com', 'password' => '12345678', 'contact' => '8885566685', 'status' => 1],
            ['role' => 'user', 'name' => 'Emily Williams', 'email' => 'emily.williams@gmail.com', 'password' => '12345678', 'contact' => '8885566695', 'status' => 1],
            ['role' => 'user', 'name' => 'Carlos Mendez', 'email' => 'carlos.mendez@gmail.com', 'password' => '12345678', 'contact' => '8885566705', 'status' => 1],
            ['role' => 'user', 'name' => 'Fatima Patel', 'email' => 'fatima.patel@gmail.com', 'password' => '12345678', 'contact' => '8885566715', 'status' => 1],
            ['role' => 'engineer', 'name' => 'Hiten Patel', 'email' => 'hiten.patel@gmail.com', 'password' => '12345678', 'contact' => '8885566715', 'status' => 1],
            ['role' => 'engineer', 'name' => 'Adam Levi', 'email' => 'adam.levi@gmail.com', 'password' => '12345678', 'contact' => '8885566715', 'status' => 1],
        ];

        foreach ($users as $userData) {
            // Create user with the provided data
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
                'contact' => $userData['contact'], 
                'status' => $userData['status']
            ]);

            // Assign role to user
            $role = Role::where('name', $userData['role'])->first();

            if ($role) {
                $user->assignRole($role);
            } else {
                $this->command->info("Role '{$userData['role']}' not found.");
            }
        }
    }
}
