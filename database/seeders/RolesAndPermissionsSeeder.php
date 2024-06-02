<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::create(['name' => 'admin']);
        $buyer = Role::create(['name' => 'buyer']);

        Permission::create(['name' => 'manage shop']);
        Permission::create(['name' => 'manage buy']);

        $admin->givePermissionTo(['manage shop', 'manage buy']);
        $buyer->givePermissionTo(['manage buy']);
    }
}

