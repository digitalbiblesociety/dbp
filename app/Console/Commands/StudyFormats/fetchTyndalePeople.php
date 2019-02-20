<?php

namespace App\Console\Commands\StudyFormats;

use App\Models\Bible\Study\GlossaryPerson;
use App\Models\Bible\Study\GlossaryPersonName;
use Illuminate\Console\Command;

class fetchTyndalePeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tyndale:fetchPeople';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches & parses the tyndale people glossary into the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->path_source = storage_path('data/study/tyndale/proper_names_source.txt');
        $this->path_json = storage_path('data/study/tyndale/proper_names.json');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // JSON is filled with errors,
        // $this->fetchPeopleFileFromGithub();

        $people = json_decode(file_get_contents($this->path_json));
        //$this->createGlossaryPerson($people);
        $this->addPeopleNames($people);
    }

    private function fetchPeopleFileFromGithub(): void
    {
        if (!file_exists($this->path_source)) {
            $proper_names = file_get_contents('https://github.com/tyndale/STEPBible-Data/blob/master/TIPNR%20-%20Tyndale%20Individualised%20Proper%20Names%20with%20all%20References%20-%20TyndaleHouse.com%20STEPBible.org%20CC%20BY-NC.txt?raw=true');
            $splitData    = explode('JSON version:', $proper_names);
            file_put_contents($this->path_source, $splitData[1]);
        }

        $input_lines = file_get_contents($this->path_source);
        $input_lines = preg_replace('/\},\s+\]/', '}]', $input_lines);
        $input_lines = preg_replace('/\},\s+\$\s+===\s+===\s+===\s+=\s+PERSON\(s\)\s+.*?\{/', '}, {', $input_lines);
        $input_lines = preg_replace('/\},\s+\$========== PERSON\(s\)\s+.*?\{/ims', '}, {', $input_lines);
        $input_lines = preg_replace('/\},\s+\$ === === === =\s+.*?\{/ims', '}, {', $input_lines);
        $input_lines = preg_replace('/\},\s+\$==========\s+.*?\{/ims', '}, {', $input_lines);
        $input_lines = preg_replace('/,r\s+"geoposition":/', ',"geoposition":', $input_lines);
        $input_lines = preg_replace('/"geoposition": "(\d+)\s+",/', '"geoposition": "\1",', $input_lines);
        $input_lines = preg_replace('/"names": \[\s+.*?\{/msi', '"names": [{', $input_lines);

        file_put_contents($this->path_json, $input_lines);
    }

    /**
     * @param $people
     *
     * @return mixed
     */
    private function createGlossaryPerson($people)
    {
        foreach ($people as $person) {
            $personExists = GlossaryPerson::where('id', $person->uniqueName)->first();
            if (!$personExists) {
                GlossaryPerson::create([
                    'id'          => $person->uniqueName,
                    'description' => $person->description
                ]);
            }
        }
    }

    /**
     * @param $people
     */
    private function addPeopleNames($people): void
    {
        foreach ($people as $person) {
            foreach ($person->names as $name) {
                $person_name = GlossaryPersonName::create([
                    'glossary_person_id'    => $name->translatedUnique,
                    'extended_strongs'      => $name->extendedStrongs,
                    'vernacular'            => $name->Hebrew_Greek,
                    'ot_ketiv_translated'   => $name->OT_Ketiv_translated,
                    'ot_qere_translated'    => $name->OT_Qere_translated,
                    'nt_variant_translated' => $name->NT_Variant_translated,
                ]);

                $translations_array = [];

                if ($name->KJV_translation) {
                    $translations_array[] = [
                        'bible_id' => 'ENGKJV',
                        'name'     => $name->KJV_translation
                    ];
                }

                if ($name->NIV_translation) {
                    $translations_array[] = [
                        'bible_id' => 'ENGNIV',
                        'name'     => $name->NIV_translation
                    ];
                }

                if ($name->ESV_translation) {
                    $translations_array[] = [
                        'bible_id' => 'ENGESV',
                        'name'     => $name->ESV_translation
                    ];
                }

                $person_name->translations()->create($translations_array);
            }
        }
    }
}
