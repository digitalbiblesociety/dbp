<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class showEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'showEnvironment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show environment vars';

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
        var_dump($_ENV);
    }
}
