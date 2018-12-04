<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\APIController;

class DigitalBibleLibraryController extends APIController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oldRecords   = csvToArray('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=2021834900');
        $oldKeys      = collect($oldRecords)->pluck('equivalent_id')->toArray();
        $oldRecords   = collect($oldRecords)->keyBy('equivalent_id')->toArray();

        $bibles   = glob(storage_path('data/bibles/DBL/*.json'));
        $output[] = ['fab_id', 'nameCommon', 'nameCommonLocal', 'languageCode', 'languageName', 'languageNameLocal', 'dateCompleted', 'languageScript', 'countryCode', 'id'];
        foreach ($bibles as $bible) {
            $bible    = json_decode(file_get_contents($bible), true);
            $bible    = $bible['revisions'][0];
            $output[] = [
                'fab_id'            => \in_array($bible['id'], $oldKeys) ? $oldRecords[$bible['id']]['bible_id'] : '',
                'nameCommon'        => $bible['nameCommon'],
                'nameCommonLocal'   => $bible['nameCommonLocal'],
                'languageCode'      => $bible['languageCode'],
                'languageName'      => $bible['languageName'],
                'languageNameLocal' => $bible['languageNameLocal'],
                'dateCompleted'     => $bible['dateCompleted'],
                'languageScript'    => $bible['languageScript'],
                'countryCode'       => $bible['countryCode'],
                'id'                => $bible['id'],
            ];
        }

        $fp = fopen(storage_path('data/bibles/DBL.csv'), 'wb');
        foreach ($output as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }
}
