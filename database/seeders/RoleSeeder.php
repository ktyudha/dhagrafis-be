<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Super Admin', 'Admin', 'Content Creator'];

        $dashboard = $this->createPermissions(['dashboard']);

        $masterPermissions = $this->createPermissions([
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view users',
            'create users',
            'edit users',
            'delete users',
        ]);

        $userPermissions = $this->createPermissions([
            'view own articles',
            'create articles',
            'edit own articles',
            'delete own articles',
            'view own events',
            'create events',
            'edit own events',
            'delete own events',
            'view own artworks',
            'create artworks',
            'edit own artworks',
            'delete own artworks',
        ]);

        $adminPermissions = $this->createPermissions([
            'view all articles',
            'edit all articles',
            'delete all articles',
            'publish articles',
            'unpublish articles',
            'view all events',
            'edit all events',
            'delete all events',
            'publish events',
            'unpublish events',
            'view all artworks',
            'edit all artworks',
            'delete all artworks',
            'publish artworks',
            'unpublish artworks',
            'users read',
            'users create',
            'users update',
            'users delete',
            'sliders read',
            'sliders create',
            'sliders update',
            'sliders delete',
            'gallery images read',
            'gallery images create',
            'gallery images update',
            'gallery images delete',
            'gallery videos read',
            'gallery videos create',
            'gallery videos update',
            'gallery videos delete',
            'gallery file manager access',
            'management read',
            'management create',
            'management update',
            'management delete',
            // 'about read',
            // 'about create',
            // 'about update',
            // 'about delete',
            // 'contact read',
            // 'contact create',
            // 'contact update',
            // 'contact delete',
            // 'katalog info read',
            // 'katalog info update',
            // 'promo info read',
            // 'promo info update',
            'social read',
            'social update',
            'katalog read',
            'katalog create',
            'katalog update',
            'katalog delete',
            'promo read',
            'promo create',
            'promo update',
            'promo delete',
            'menu read',
            'menu create',
            'menu update',
            'menu delete',
            'submenu read',
            'submenu create',
            'submenu update',
            'submenu delete',
            'jobs read',
            'jobs create',
            'jobs update',
            'jobs delete',
        ]);

        foreach ($roles as $role) {
            $role = Role::create(['name' => $role]);
            if ($role->name == 'Super Admin') {
                $role->syncPermissions([
                    'dashboard',
                    ...$masterPermissions,
                    ...$adminPermissions,
                    ...$userPermissions
                ]);
            } else if ($role->name == 'Admin') {
                $role->syncPermissions([
                    'dashboard',
                    ...$adminPermissions,
                ]);
            } else {
                $role->syncPermissions([
                    'dashboard',
                    ...$userPermissions
                ]);
            }
        }
    }

    private function createPermissions(array $permissions)
    {
        $rows = [];
        foreach ($permissions as $permission) {
            $rows[] = Permission::create(['name' => $permission]);
        }
        return $rows;
    }
}
