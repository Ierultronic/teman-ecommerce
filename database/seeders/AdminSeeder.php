<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create basic permissions if they don't exist
        $permissions = [
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view orders',
            'manage orders',
            'view users',
            'manage users',
            'access admin panel'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to admin role
        $adminRole->givePermissionTo(Permission::all());

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@teman.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@teman.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role to the user
        $admin->assignRole($adminRole);

        // Output success message
        echo "Admin user created successfully!\n";
        echo "Email: admin@teman.com\n";
        echo "Password: admin123\n";
    }
}
