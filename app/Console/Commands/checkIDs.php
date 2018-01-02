<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bible\Bible;
class checkIDs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:check {connection}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
	protected $connection;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $connection = explode('=',$this->argument('connection'));

        switch($connection[1]) {

	        case "sophiaToBiblesTable": {
		        $tables = collect(\DB::connection('sophia')->select('SHOW TABLES'))->pluck('Tables_in_sophia');
		        $bible_tables = collect($tables)->filter(function ($value) {
			        return (strpos($value, '_vpl') !== false) ? $value : false;
		        });

		        foreach ($bible_tables as $bible_id) {
		        	$id = substr($bible_id,0,-4);
			        $currentBible = Bible::find($id);
			        if(!$currentBible) { echo "\n Missing: $id"; }
		        }
	        }

	        case "dbt_vs_dbp4": {

				$context = ["ssl" => ["verify_peer"=> false]];
		        $dbp4_bibles = collect(json_decode(file_get_contents('https://api.dbp.dev/library/volume?key=809db3bd83c66f3bc41be0cbd6bd1e3f&v=2', false, stream_context_create($context))))->pluck('dam_id')->toArray();
		        $dbt_bibles =  collect(json_decode(file_get_contents('https://dbt.io/library/volume?key=809db3bd83c66f3bc41be0cbd6bd1e3f&v=2', false, stream_context_create($context))))->pluck('dam_id')->toArray();
		        $results = array_diff($dbt_bibles,$dbp4_bibles);
		        dd($results);
	        }

        }
    }
}
