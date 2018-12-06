<?php

use Illuminate\Database\Seeder;

class RealDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedData('/countries/countries', Country::class);
        $this->seedData('/languages/language_status', LanguageStatus::class);
        $this->seedData('/languages/languages', Language::class);
        $this->seedData('/languages/language_translations', LanguageTranslation::class);
        $this->seedData('/languages/language_bibleInfo', LanguageBibleInfo::class);
        $this->seedData('/languages/numeral_systems', NumeralSystem::class);
        $this->seedData('/languages/alphabets', Alphabet::class);
        $this->seedData('/countries/country_translations', CountryTranslation::class);
        $this->seedData('/countries/country_languages', CountryLanguage::class);
        $this->seedData('/bibles/bibles', Bible::class);
        $this->seedData('/bibles/bibles_translations', BibleTranslation::class);
        $this->seedData('/organizations/organizations', Organization::class);
        $this->seedData('/organizations/assets', Asset::class);
        $this->seedData('/organizations/organization_translations', OrganizationTranslation::class);
        $this->seedData('/organizations/organization_logos', OrganizationLogo::class);
        $this->seedData('/organizations/organization_relationships', OrganizationRelationship::class);
        $this->seedData('/bibles/bible_links', BibleLink::class);
        $this->seedData('/bibles/books', Book::class);
        $this->seedData('/bibles/bible_books', BibleBook::class);
        $this->seedData('/bibles/bible_organization', BibleOrganization::class);
        $this->seedData('/bibles/bible_fileset_sizes', BibleFilesetSize::class);
        $this->seedData('/bibles/bible_fileset_types', BibleFilesetType::class);
        $this->seedData('/bibles/bible_filesets', BibleFileset::class);
        $this->seedData('/bibles/bible_files', BibleFile::class);
        $this->seedData('/bibles/bible_file_timestamps', BibleFileTimestamp::class);
        $this->seedData('/bibles/bible_organization', BibleOrganization::class);
        $this->seedData('/bibles/equivalents/bible-gateway', BibleEquivalent::class);
        $this->seedData('/bibles/equivalents/crosswire', BibleEquivalent::class);
        $this->seedData('/bibles/equivalents/digital-bible-library', BibleEquivalent::class);
        $this->seedData('/bibles/equivalents/talking-bibles', BibleEquivalent::class);
        $this->seedData('/access/access_groups', AccessGroup::class);
        $this->seedData('/access/access_types', AccessType::class);
        $this->seedData('/access/access_group_filesets', AccessGroupFileset::class);
        $this->seedData('/access/access_group_types', AccessGroupType::class);
    }

    public function seedData($path, $object)
    {
        $path = (config('app.env') === 'local') ? '/Sites/dbp-seeds'.$path.'.yaml' : "https://raw.githubusercontent.com/digitalbiblesociety/dbp-seeds/master/$path.yaml";
        $parser = new Yaml();
        $current_object = new $object;
        $entries = $parser->parse(file_get_contents($path));
        foreach ($entries as $entry) {
            $current_object->create($entry);
        }
    }
}
