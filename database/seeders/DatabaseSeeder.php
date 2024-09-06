<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            ManagementSeeder::class,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@media.pens.ac.id',
            'email_verified_at' => now(),
        ])->assignRole('Super Admin');

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@media.pens.ac.id',
            'email_verified_at' => now(),
        ])->assignRole('Admin');

        \App\Models\User::factory()
            ->hasArticles(10)
            ->hasArtworks(10)
            ->hasEvents(10)->create([
                'name' => 'User',
                'email' => 'user@media.pens.ac.id',
            ])->assignRole('Content Creator');

        \App\Models\User::factory(5)
            ->hasArticles(10)
            ->hasArtworks(10)
            ->hasEvents(10)
            ->create()->each(function (\App\Models\User $user) {
                $user->assignRole('Content Creator');
            });
    }
}
