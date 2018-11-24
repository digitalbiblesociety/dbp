<?php

namespace database\seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

use Noodlehaus\FileParser\Yaml;

use App\Models\Country\Country;
use App\Models\Country\CountryTranslation;
use App\Models\Country\CountryLanguage;

use App\Models\Language\Language;
use App\Models\Language\LanguageStatus;
use App\Models\Language\LanguageTranslation;
use App\Models\Language\LanguageBibleInfo;
use App\Models\Language\Alphabet;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleTranslation;
use App\Models\Bible\BibleLink;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleOrganization;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileTimestamp;

use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;
use App\Models\Organization\OrganizationLogo;
use App\Models\Organization\OrganizationRelationship;

// Users

use App\Models\User\Project;
use \App\Models\User\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //$this->seedData('/countries/countries',                         Country::class);
        //$this->seedData('/languages/language_status',                   LanguageStatus::class);
        //$this->seedData('/languages/languages',                         Language::class);
        //$this->seedData('/languages/language_translations',             LanguageTranslation::class);
        //$this->seedData('/languages/language_bibleInfo',                LanguageBibleInfo::class);
        //$this->seedData('/languages/alphabets',                         Alphabet::class);
        //$this->seedData('/countries/country_translations',              CountryTranslation::class);
        //$this->seedData('/countries/country_languages',                 CountryLanguage::class);
        //$this->seedData('/bibles/bibles',                               Bible::class);
        //$this->seedData('/bibles/bibles_translations',                  BibleTranslation::class);
        //$this->seedData('/bibles/bible_links',                          BibleLink::class);
        //$this->seedData('/bibles/bible_books',                          BibleBook::class);
        //$this->seedData('/bibles/organizations',                        BibleOrganization::class);
        //$this->seedData('/bibles/bible_filesets',                       BibleFileset::class);
        //$this->seedData('/bibles/bible_files',                          BibleFile::class);
        //$this->seedData('/bibles/bible_file_timestamps',                BibleFileTimestamp::class);
        //$this->seedData('/organizations/organizations',                 Organization::class);
        //$this->seedData('/organizations/organization_translations',     OrganizationTranslation::class);
        //$this->seedData('/organizations/organization_logos',            OrganizationLogo::class);
        //$this->seedData('/organizations/organizations_relationships',   OrganizationRelationship::class);
        //$this->seedData('/bibles/bible_organization',                   BibleOrganization::class);
        //$this->seedData('/bibles/equivalents/bible-gateway',            BibleEquivalent::class);
        //$this->seedData('/bibles/equivalents/crosswire',                BibleEquivalent::class);
        //$this->seedData('/bibles/equivalents/digital-bible-library',    BibleEquivalent::class);
        //$this->seedData('/bibles/equivalents/talking-bibles',           BibleEquivalent::class);
        $this->seedData('/bibles/equivalents/talking-bibles', BibleEquivalent::class);

        $this->call(UserDatabaseSeeder::class);

        Model::reguard();
    }

    public function seedData($path, $object)
    {
        //$path = "https://raw.githubusercontent.com/digitalbiblesociety/dbp-seeds/master/$path.yaml";

        $entries = Yaml::parse(file_get_contents('../../dbp-seeds/'.$path));
        foreach ($entries as $entry) {
            $object->create($entry);
        }
    }
}
