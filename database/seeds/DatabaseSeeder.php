<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

use Symfony\Component\Yaml\Yaml;



use App\Models\Country\Country;
use App\Models\Country\CountryTranslation;
use App\Models\Country\CountryLanguage;

use App\Models\Language\Language;
use App\Models\Language\LanguageStatus;
use App\Models\Language\LanguageTranslation;
use App\Models\Language\LanguageBibleInfo;
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
use App\Models\Bible\BibleFilesetSize;
use App\Models\Bible\BibleFilesetType;

use App\Models\Organization\Asset;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;
use App\Models\Organization\OrganizationLogo;
use App\Models\Organization\OrganizationRelationship;

use App\Models\User\AccessGroupFileset;
use App\Models\User\AccessGroupType;
use App\Models\User\AccessGroup;
use App\Models\User\AccessType;


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
        ini_set('memory_limit', '2500M');

        $this->call(UserRoleSeeder::class);
        $this->call(UserHighlightColorSeeder::class);

        $this->call(ProjectsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(AnnotationSeeder::class);

        $this->call(AccessKeySeeder::class);
        $this->call(ArticlesSeeder::class);

    }
}
