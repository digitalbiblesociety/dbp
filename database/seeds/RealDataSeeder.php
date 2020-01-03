<?php

use Illuminate\Database\Seeder;

use Symfony\Component\Yaml\Yaml;

use App\Models\Country\Country;
use App\Models\Country\CountryTranslation;
use App\Models\Country\CountryLanguage;

use App\Models\Language\Language;
use App\Models\Language\LanguageStatus;
use App\Models\Language\LanguageTranslation;
use App\Models\Language\LanguageBibleInfo;
use App\Models\Language\NumeralSystemGlyph;
use App\Models\Language\Alphabet;
use App\Models\Language\NumeralSystem;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleTranslation;
use App\Models\Bible\BibleLink;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleOrganization;
use App\Models\Bible\BibleFile;
use App\Models\Bible\Book;
use App\Models\Bible\BookTranslation;
use App\Models\Bible\BibleFilesetSize;
use App\Models\Bible\BibleFilesetType;
use App\Models\Bible\BibleFilesetConnection;
use App\Models\Bible\BibleFilesetCopyright;
use App\Models\Bible\BibleFilesetCopyrightRole;
use App\Models\Bible\BibleFilesetCopyrightOrganization;
use App\Models\Bible\StreamBandwidth;
use App\Models\Bible\StreamTS;

use App\Models\Organization\Asset;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;
use App\Models\Organization\OrganizationLogo;
use App\Models\Organization\OrganizationRelationship;

use App\Models\User\AccessGroupFileset;
use App\Models\User\AccessGroupType;
use App\Models\User\AccessGroup;
use App\Models\User\AccessType;

class RealDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedData('/countries/countries',                          Country::class);
        $this->seedData('/languages/language_status',                    LanguageStatus::class);
        $this->seedData('/languages/languages',                          Language::class);
        $this->seedData('/languages/language_translations',              LanguageTranslation::class);
        $this->seedData('/languages/language_bibleInfo',                 LanguageBibleInfo::class);
        $this->seedData('/languages/numeral_systems',                    NumeralSystem::class);
        $this->seedData('/languages/numeral_system_glyphs',              NumeralSystemGlyph::class);
        $this->seedData('/languages/alphabets',                          Alphabet::class);
        $this->seedData('/countries/country_translations',               CountryTranslation::class);
        $this->seedData('/countries/country_languages',                  CountryLanguage::class);
        $this->seedData('/bibles/bibles',                                Bible::class);
        $this->seedData('/bibles/bibles_translations',                   BibleTranslation::class);
        $this->seedData('/organizations/organizations',                  Organization::class);
        $this->seedData('/organizations/assets',                         Asset::class);
        $this->seedData('/organizations/organization_translations',      OrganizationTranslation::class);
        $this->seedData('/organizations/organization_logos',             OrganizationLogo::class);
        $this->seedData('/organizations/organization_relationships',     OrganizationRelationship::class);
        $this->seedData('/bibles/bible_links',                           BibleLink::class);
        $this->seedData('/bibles/books',                                 Book::class);
        $this->seedData('/bibles/book_translations',                     BookTranslation::class);
        $this->seedData('/bibles/bible_books',                           BibleBook::class);
        $this->seedData('/bibles/bible_organization',                    BibleOrganization::class);
        $this->seedData('/bibles/bible_fileset_sizes',                   BibleFilesetSize::class);
        $this->seedData('/bibles/bible_fileset_types',                   BibleFilesetType::class);
        $this->seedData('/bibles/bible_filesets',                        BibleFileset::class);
        $this->seedData('/bibles/bible_fileset_connections',             BibleFilesetConnection::class);
        $this->seedData('/bibles/bible_fileset_copyright_roles',         BibleFilesetCopyrightRole::class);
        $this->seedData('/bibles/bible_fileset_copyrights',              BibleFilesetCopyright::class);
        $this->seedData('/bibles/bible_fileset_copyright_organizations', BibleFilesetCopyrightOrganization::class);
        $this->seedData('/bibles/bible_files',                           BibleFile::class);
        $this->seedData('/bibles/bible_file_video_resolutions',          StreamBandwidth::class);
        $this->seedData('/bibles/bible_file_video_transport_stream',     StreamTS::class);
        $this->seedData('/bibles/bible_organization',                    BibleOrganization::class);
        $this->seedData('/bibles/equivalents/bible-gateway',             BibleEquivalent::class);
        $this->seedData('/access/access_groups',                         AccessGroup::class);
        $this->seedData('/access/access_types',                          AccessType::class);
        $this->seedData('/access/access_group_filesets',                 AccessGroupFileset::class);
        $this->seedData('/access/access_group_types',                    AccessGroupType::class);
    }

    public function seedData($path, $object)
    {
        $subpath = (config('app.server_name') != 'LOCAL') ? 'https://raw.githubusercontent.com/digitalbiblesociety/dbp-seeds/master/' : '/Sites/dbp-seeds/';
        $parser = new Yaml();
        $current_object = new $object;
        $entries = $parser->parse(file_get_contents($subpath.$path.'.yaml'));
        $entries_count = count($entries);
        $current_count = 0;
        foreach ($entries as $entry) {
            $current_count++;
            $current_object->create($entry);

            if ($current_count % 1000 === 0) {
                echo "\n Seeded ".$current_count .' of '. $entries_count;
            }
        }
    }
}
