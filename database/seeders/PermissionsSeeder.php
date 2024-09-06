<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Role::truncate();
        Permission::truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();

        $roles = ['superadmin', 'admin', 'writer', 'user'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $adminAccessPermission = Permission::create(['name' => 'admin access']);
        $adminRole = Role::whereName('admin')->first();
        $adminRole->givePermissionTo($adminAccessPermission);

        $writerRole = Role::whereName('writer')->first();
        $writerPermissions = [
            'medical posts read',
            'medical posts create',
            'medical posts update',
            'medical posts delete',
            'medical categories read',
            'medical categories create',
            'medical categories update',
            'medical categories delete',
            'medical record read',
            'medical record create',
            'medical record update',
            'medical record delete',
            'patient booking read',
            'patient booking create',
            'patient booking update',
            'patient booking delete',
            'salary read',
            'salary create',
            'salary update',
            'salary delete',
        ];

        foreach ($writerPermissions as $name) {
            $permission = Permission::create(['name' => $name]);
            $writerRole->givePermissionTo($permission);
        }

        $writerRole->givePermissionTo($adminAccessPermission);

        $otherPermissions = [
            'users read',
            'users create',
            'users update',
            'users delete',
            'sliders read',
            'sliders create',
            'sliders update',
            'sliders delete',
            'clients read',
            'clients create',
            'clients update',
            'clients delete',
            'teams read',
            'teams create',
            'teams update',
            'teams delete',
            'services read',
            'services create',
            'services update',
            'services delete',
            'portofolio read',
            'portofolio create',
            'portofolio update',
            'portofolio delete',
            'portofolio categories read',
            'portofolio categories create',
            'portofolio categories update',
            'portofolio categories delete',
            'portofolio posts read',
            'portofolio posts create',
            'portofolio posts update',
            'portofolio posts delete',
            'product categories read',
            'product categories create',
            'product categories update',
            'product categories delete',
            'product posts read',
            'product posts create',
            'product posts update',
            'product posts delete',
            'testimoni read',
            'testimoni create',
            'testimoni update',
            'testimoni delete',
            'gallery images read',
            'gallery images create',
            'gallery images update',
            'gallery images delete',
            'gallery videos read',
            'gallery videos create',
            'gallery videos update',
            'gallery videos delete',
            'gallery file manager access',
            'inboxes read',
            'inboxes create',
            'inboxes update',
            'inboxes delete',
            'subscribes read',
            'subscribes create',
            'subscribes update',
            'subscribes delete',
            'settings',
            'pages read',
            'pages create',
            'pages update',
            'pages delete',
            'tamu read',
            'tamu create',
            'tamu update',
            'tamu delete',
            'tamu confirm',
            'tamu coming',
            'jam read',
            'jam create',
            'jam update',
            'jam delete',

            'blog categories read',
            'blog categories create',
            'blog categories update',
            'blog categories delete',
            'blog posts read',
            'blog posts create',
            'blog posts update',
            'blog posts delete',


            // 'medical posts read',
            // 'medical posts create',
            // 'medical posts update',
            // 'medical posts delete',
            // 'medical record read',
            // 'medical record create',
            // 'medical record update',
            // 'medical record delete',
        ];

        foreach ($otherPermissions as $name) {
            Permission::create(['name' => $name]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
