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
use App\Models\Bible\BibleFileTimestamp;
use App\Models\Bible\Book;
use App\Models\Bible\BookTranslation;
use App\Models\Bible\BibleFilesetSize;
use App\Models\Bible\BibleFilesetType;
use App\Models\Bible\BibleFilesetConnection;
use App\Models\Bible\BibleFilesetCopyright;
use App\Models\Bible\BibleFilesetCopyrightRole;
use App\Models\Bible\BibleFilesetCopyrightOrganization;

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
        echo "\n Seeding countries";
        $this->seedData('/countries/countries', Country::class);
        echo "\n Seeding language_status";
        $this->seedData('/languages/language_status', LanguageStatus::class);
        echo "\n Seeding languages";
        $this->seedData('/languages/languages', Language::class);
        echo "\n Seeding language_translations";
        $this->seedData('/languages/language_translations', LanguageTranslation::class);
        echo "\n Seeding language_bibleInfo";
        $this->seedData('/languages/language_bibleInfo', LanguageBibleInfo::class);
        echo "\n Seeding numeral_systems";
        $this->seedData('/languages/numeral_systems', NumeralSystem::class);
        echo "\n Seeding Numeral Systems Glyphs";
        $this->seedData('/languages/numeral_system_glyphs', NumeralSystemGlyph::class);
        echo "\n Seeding alphabets";
        $this->seedData('/languages/alphabets', Alphabet::class);
        echo "\n Seeding country_translations";
        $this->seedData('/countries/country_translations', CountryTranslation::class);
        echo "\n Seeding country_languages";
        $this->seedData('/countries/country_languages', CountryLanguage::class);
        echo "\n Seeding bibles";
        $this->seedData('/bibles/bibles', Bible::class);
        echo "\n Seeding bibles_translations";
        $this->seedData('/bibles/bibles_translations', BibleTranslation::class);
        echo "\n Seeding organizations";
        $this->seedData('/organizations/organizations', Organization::class);
        echo "\n Seeding assets";
        $this->seedData('/organizations/assets', Asset::class);
        echo "\n Seeding organization_translations";
        $this->seedData('/organizations/organization_translations', OrganizationTranslation::class);
        echo "\n Seeding organization_logos";
        $this->seedData('/organizations/organization_logos', OrganizationLogo::class);
        echo "\n Seeding organization_relationships";
        $this->seedData('/organizations/organization_relationships', OrganizationRelationship::class);
        echo "\n Seeding bible_links";
        $this->seedData('/bibles/bible_links', BibleLink::class);
        echo "\n Seeding books";
        $this->seedData('/bibles/books', Book::class);
        echo "\n Seeding book translations";
        $this->seedData('/bibles/book_translations', BookTranslation::class);
        echo "\n Seeding bible_books";
        $this->seedData('/bibles/bible_books', BibleBook::class);
        echo "\n Seeding bible_organization";
        $this->seedData('/bibles/bible_organization', BibleOrganization::class);
        echo "\n Seeding bible_fileset_sizes";
        $this->seedData('/bibles/bible_fileset_sizes', BibleFilesetSize::class);
        echo "\n Seeding bible_fileset_types";
        $this->seedData('/bibles/bible_fileset_types', BibleFilesetType::class);
        echo "\n Seeding bible_filesets";
        $this->seedData('/bibles/bible_filesets', BibleFileset::class);
        echo "\n Seeding bible_fileset_connections";
        $this->seedData('/bibles/bible_fileset_connections', BibleFilesetConnection::class);

        echo "\n Seeding bible_fileset_copyright roles";
        $this->seedData('/bibles/bible_fileset_copyright_roles', \App\Models\Bible\BibleFilesetCopyrightRole::class);

        echo "\n Seeding bible_fileset_copyrights";
        $this->seedData('/bibles/bible_fileset_copyrights', BibleFilesetCopyright::class);

        echo "\n Seeding bible_fileset_copyrights";
        $this->seedData('/bibles/bible_fileset_copyright_organizations', BibleFilesetCopyrightOrganization::class);

        echo "\n Seeding bible_files";
        $this->seedData('/bibles/bible_files', BibleFile::class);

        echo "\n Seeding bible_file_video_resolutions";
        $this->seedData('/bibles/bible_file_video_resolutions',      \App\Models\Bible\VideoResolution::class);

        echo "\n Seeding bible_file_video_transport_stream";
        $this->seedData('/bibles/bible_file_video_transport_stream', \App\Models\Bible\VideoTransportStream::class);

        echo "\n Seeding bible_organization";
        $this->seedData('/bibles/bible_organization', BibleOrganization::class);
        echo "\n Seeding equivalents";
        $this->seedData('/bibles/equivalents/bible-gateway', BibleEquivalent::class);
        echo "\n Seeding equivalents";
        echo "\n Seeding access_groups";
        $this->seedData('/access/access_groups', AccessGroup::class);
        echo "\n Seeding access_types";
        $this->seedData('/access/access_types', AccessType::class);
        echo "\n Seeding access_group_filesets";
        $this->seedData('/access/access_group_filesets', AccessGroupFileset::class);
        echo "\n Seeding access_group_types";
        $this->seedData('/access/access_group_types', AccessGroupType::class);
    }

    public function seedData($path, $object)
    {
        $path = (config('app.server_name') != 'Travis') ? '/Sites/dbp-seeds'.$path.'.yaml' : "https://raw.githubusercontent.com/digitalbiblesociety/dbp-seeds/master/$path.yaml";
        $parser = new Yaml();
        $current_object = new $object;
        $entries = $parser->parse(file_get_contents($path));
        foreach ($entries as $entry) {
            $current_object->create($entry);
        }
    }
}
