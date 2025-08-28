<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user with admin role and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating admin user...');

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

        $this->info('Admin user created successfully!');
        $this->info('Email: admin@teman.com');
        $this->info('Password: admin123');
    }
}
