<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class countSophia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'count:Sophia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $tables = \DB::connection('sophia')->table('bible_list')->select('fcbhId', 'shortTitle')->get()->pluck('shortTitle', 'fcbhId');
        $totalCount = 0;
        foreach ($tables as $table => $title) {
            $tableExists = \Schema::connection('sophia')->hasTable($table.'_vpl');
            if (!$tableExists) {
                echo "\n Missing: $table";
                continue;
            }
            $count[$table]['chapter_count'] = count(\DB::connection('sophia')->table($table.'_vpl')->select('book', 'chapter')->get()->unique());
            $totalCount = $totalCount + $count[$table]['chapter_count'];
        }
        $count['total_count'] = $totalCount;
        file_put_contents(public_path('/static/count_sophia.json'), json_encode($count));
    }
}
