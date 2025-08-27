<?php

namespace Database\Seeders;

use App\Enums\ModuleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = array_column(ModuleEnum::cases(), 'value');

        foreach($permissions as $key => $permission)
        {
            $field_check = Permission::where('name', $permission)->first();

            if(!$field_check)
            {
                Permission::create([
                    'name' => $permission
                ]);
            }
        }

        Permission::whereNotIn('name', $permissions)->delete();

        $role = Role::where('name', 'superadmin')->first();
        if($role)
        {
            $permissions = Permission::all()->pluck('name');
            $role->syncPermissions($permissions ?? []);

            $user = User::where('email', 'admin@gmail.com')->first();
            if(!$user) {
                $user =User::create([
                    'name' => 'Super Admin',
                    'email' => 'admin@gmail.com',
                    'contact' => '858585656565',
                    'status' => 1,
                    'password' => Hash::make('12345678')
                ]);
            }
            if($user)
            {
                $user->syncRoles($role->name);
            }

        }
        

    }
}
