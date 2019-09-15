<?php

use Illuminate\Database\Seeder;


class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::connection('dbp_users')->table('roles')->insert([
            'name' => 'User',
            'slug' => 'user',
        ]);
        \DB::connection('dbp_users')->table('roles')->insert([
            'name' => 'Developer',
            'slug' => 'developer',
        ]);
        \DB::connection('dbp_users')->table('roles')->insert([
            'name' => 'Archivist',
            'slug' => 'archivist',
        ]);
        \DB::connection('dbp_users')->table('roles')->insert([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);
    }
}
