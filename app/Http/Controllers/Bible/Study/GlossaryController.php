<?php

namespace App\Http\Controllers\Bible\Study;

use App\Models\Bible\Study\GlossaryPerson;

class GlossaryController extends APIController
{
    public function people()
    {
        $glossary_person = checkParam('glossary_person');
        $person = GlossaryPerson::with('names', 'relationships', 'translation')->where('id', $glossary_person)->first();
        return $person;
    }

    // public function
}
