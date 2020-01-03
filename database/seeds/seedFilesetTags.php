<?php

use Illuminate\Database\Seeder;

class seedFilesetTags extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_types = [ 'mobile', 'download', 'download_text', 'web', 'web_streaming', 'sign_language', 'local_bundled', 'podcast', 'mp3_cd', 'streaming_url', 'developer', 'radio', 'television', 'digital_download', 'bible_stick', 'subsplash', 'lo_res', 'med_res', 'hi_res', 'is_right_to_left', 'num_art', 'num_sample_audio', 'organization_id', 'tlibrary_id', 'stocknumber'];

        $dam_volumes = \DB::connection('dbp_v2')->table('dam_library')->get();
        foreach ($dam_volumes as $dam_volume) {
            switch (substr($dam_volume->dam_id, -2, 2)) {
                case 'ET':
                    $type = 'text_plain';
                    break;
                case 'DA':
                    $type =' audio';
                    break;
            }

            if (!isset($type)) {
                continue;
            }

            $type .= (substr($dam_volume->dam_id, -3, 1) === 2) ? '_drama' : '';

            $fileset =  \App\Models\Bible\BibleFileset::uniqueFileset($dam_volume->dam_id, 'dbp-prod', $type)->first();
            if (!$fileset) {
                continue;
            }
            foreach ($permission_types as $permission_type) {
                if ($dam_volume->{$permission_type}) {
                    \App\Models\Bible\BibleFilesetTag::firstOrCreate([
                        'hash_id'     => $fileset->hash_id,
                        'name'        => 'v2_access_'.$permission_type,
                        'description' => $permission_type,
                        'language_id' => 6414
                    ]);
                }
            }
        }
    }
}
