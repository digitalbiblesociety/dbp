<?php

use Illuminate\Database\Seeder;

use App\Models\User\AccessGroup;
use App\Models\User\AccessGroupKey;

class AccessKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $access_groups = AccessGroup::where('name','!=','RESTRICTED')->get();
        $keys = \App\Models\User\Key::all();
        foreach ($keys as $key) {
            $key_count = random_int(1,$access_groups->count());
            while ($key_count > 0) {
                AccessGroupKey::create([
                    'key_id'          => $key->key,
                    'access_group_id' => $access_groups->random()->first()->id
                ]);
                $key_count--;
            }
        }
    }
}
