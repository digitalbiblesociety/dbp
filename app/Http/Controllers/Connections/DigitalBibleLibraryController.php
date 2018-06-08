<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use database\seeds\SeederHelper;

class DigitalBibleLibraryController extends APIController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seederHelper = new SeederHelper();
        $oldRecords   = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=2021834900');
        $oldKeys      = collect($oldRecords)->pluck('equivalent_id')->toArray();
        $oldRecords   = collect($oldRecords)->keyBy('equivalent_id')->toArray();

        $bibles   = glob(storage_path('data/bibles/DBL/*.json'));
        $output[] = ['fab_id', 'nameCommon', 'nameCommonLocal', 'languageCode', 'languageName', 'languageNameLocal', 'dateCompleted', 'languageScript', 'countryCode', 'id'];
        foreach ($bibles as $bible) {
            $bible    = json_decode(file_get_contents($bible), true);
            $bible    = $bible['revisions'][0];
            $output[] = [
                'fab_id'            => (in_array($bible['id'], $oldKeys)) ? $oldRecords[$bible['id']]['bible_id'] : '',
                'nameCommon'        => $bible['nameCommon'] ?? "",
                'nameCommonLocal'   => $bible['nameCommonLocal'] ?? "",
                'languageCode'      => $bible['languageCode'] ?? "",
                'languageName'      => $bible['languageName'] ?? "",
                'languageNameLocal' => $bible['languageNameLocal'] ?? "",
                'dateCompleted'     => $bible['dateCompleted'] ?? "",
                'languageScript'    => $bible['languageScript'] ?? "",
                'countryCode'       => $bible['countryCode'] ?? "",
                'id'                => $bible['id'] ?? "",
            ];
        }

        $fp = fopen(storage_path('data/bibles/DBL.csv'), 'w');
        foreach ($output as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
