<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class processDBLBundle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processDBLBundle:name';

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
        $bibles = glob();
        foreach ($bibles as $bible) {
        }
        //file_get_contents('')
    }
}
