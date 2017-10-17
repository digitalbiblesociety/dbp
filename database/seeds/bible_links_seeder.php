<?php

use Illuminate\Database\Seeder;
use \App\Models\Organization\OrganizationTranslation;
use \database\seeds\SeederHelper;
use App\Models\Bible\Bible;
class bible_links_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit','600M');

        $seederhelper = new SeederHelper();
        $bibleLinks = $seederhelper->csv_to_array(storage_path().'/data/bibles/bible_links.csv');

        foreach($bibleLinks as $item) {
            // id,"abbr","type","title","link","provider","created_at","updated_at"
            // 1,"RUSLCV","PDF","Greek and Russian FB","ftp://ftp.logos.md/Biblioteca/Biblia%28Biblija%29/InterlGreekRusNT.pdf","Logos","2015-11-06 02:04:05","2015-11-06 02:04:05"
            $link['bible_id'] = $item['abbr'];
            $link['type'] = $item['type'];
            $link['link'] = $item['link'];
            $bible = Bible::find($item['abbr']);
            if(isset($bible)) {

                $organizationTranslation = OrganizationTranslation::with('organization')->where('name','=',$item['provider'])->first();
                if(isset($organizationTranslation)) {
                    $link['organization_id'] = $organizationTranslation->organization->id;
                    $link['created_at'] = $item['created_at'];
                    $link['updated_at'] = $item['updated_at'];
                    $link['title'] = $item['title'];
                    DB::table('bible_links')->insert($link);
                } else {
                    /*
                    $organization['website'] = $item['provider'];
                    DB::table('organizations')->insert($organization);
                    $organization = DB::table('organizations')->where('website','=',$item['provider'])->first();
                    $organizationTranslation['organization_id'] = $organization->slug;
                    $organizationTranslation['iso'] = "eng";
                    $organizationTranslation['title'] = $item['provider'];
                    DB::table('organization_translations')->insert($organizationTranslation);

                    $link['organization_id'] = $organization->slug;
                    $link['created_at'] = $item['created_at'];
                    $link['updated_at'] = $item['updated_at'];
                    $link['title'] = $item['title'];
                    DB::table('bible_links')->insert($link);
                    */
                }
            } else {
                echo $item['abbr']."\n";
            }



        }

    }

}
