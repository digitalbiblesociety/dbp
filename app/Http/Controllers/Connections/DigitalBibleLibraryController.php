<?php

namespace App\Http\Controllers\Connections;

use App\Models\Language\Language;
use App\Models\Language\NumeralSystem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use database\seeds\SeederHelper;
use App\Models\Bible\Bible;

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
		return view('dashboard.dbl.create-entry');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
    	$equivalents = explode(',',request()->dbl_id);
    	foreach ($equivalents as $equivalent_id) {
		    $entry = json_decode(file_get_contents('https://thedigitalbiblelibrary.org/api/entries/'. $equivalent_id));
		    $revision = $entry->revisions[0];

		    $acronym = "";
		    $words = explode(" ", $revision->nameCommon);
		    foreach ($words as $word) {
			    if($word == 'and') {continue;}
			    if($word == 'of') {continue;}
			    $acronym .= $word[0];
		    }
		    $date = explode('-',$revision->dateCompleted);
		    $year = $date[0];

		    switch ($revision->scope) {
			    case "New Testament":           {$scope = "NT";  break; }
			    case "New Testament+":          {$scope = "NTP"; break; }
			    case "Old Testament":           {$scope = "OT";  break; }
			    case "Bible with Deuterocanon": {$scope = "FBA"; break; }
			    case "Bible":                   {$scope = "FB"; break;}
		    }

		    if(!isset($scope)) {dd($revision->scope);}
		    $language = Language::where('iso', $revision->languageCode)->first();
		    if(!isset($language)) {continue;}

		    $id = ($revision->nameAbbreviationLocal != '') ? strtoupper($language->iso.$revision->nameAbbreviationLocal) : strtoupper($language->iso.$acronym);
		    $id = preg_replace("/[^A-Za-z0-9 ]/", '', $id);
			$bible_ids[] = $id;

			if($revision->languageNumerals == '') $revision->languageNumerals = 'Default';
			if(!NumeralSystem::where('id',$revision->languageNumerals)->exists()) {dd($revision->languageNumerals);}

		    \DB::transaction(function () use($revision,$year,$scope,$language,$acronym,$id) {
			    $bible = Bible::create([
				    'id'                => $id,
				    'iso'               => $language->iso,
				    'language_id'       => $language->id,
				    'versification'     => 'protestant',
				    'numeral_system_id' => $revision->languageNumerals ?? 'Default',
				    'date'              => $year,
				    'scope'             => $scope,
				    'script'            => $revision->languageScriptCode ?? 'Latn',
				    'derived'           => '',
				    'copyright'         => strip_tags($revision->copyrightStatement),
				    'in_progress'       => 0,
				    'priority'          => 2,
			    ]);

			    $bible->translations()->create([
				    'iso'               => $language->iso,
				    'language_id'       => $language->id,
				    'vernacular'        => 1,
				    'vernacular_trade'  => 0,
				    'name'              => $revision->nameCommon,
				    'type'              => null,
				    'features'          => null,
				    'description'       => $revision->description,
				    'notes'             => $revision->idParatextFullName ?? '',
			    ]);
		    });
	    }

	    return redirect()->route('wiki_bibles.all', ['ids' => $bible_ids]);

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
