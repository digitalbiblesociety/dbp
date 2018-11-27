<?php

use Illuminate\Database\Seeder;

use App\Models\User\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'User',
            'slug' => 'user',
        ]);
        Role::create([
            'name' => 'Developer',
            'slug' => 'developer',
        ]);
        Role::create([
            'name' => 'Archivist',
            'slug' => 'archivist',
        ]);
        Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

    }
}
