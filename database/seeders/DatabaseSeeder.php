<?php

namespace Database\Seeders;

use App\Features\Users\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use App\Features\Roles\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Artisan::call('shield:generate', [
            '--all' => true,
            '--option' => 'permissions',
            '--panel' => 'admin',
            '--no-interaction' => true,
            '--quiet' => true,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::query()->pluck('name')->all());

        $admin = User::firstOrCreate(
            ['email' => 'admin@app.com'],
            [
                'first_name' => 'System',
                'middle_initial' => null,
                'last_name' => 'Admin',
                'contact_number' => '123456789',
                'password' => Hash::make('password'),
            ]
        );

        $admin->syncRoles([$adminRole]);

        $this->call([
            RoleSeeder::class,
            DocumentProcessSeeder::class,
            DocumentCategorySeeder::class,
        ]);
    }
}