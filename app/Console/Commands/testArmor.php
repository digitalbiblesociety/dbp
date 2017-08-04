<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class testArmor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:armor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A test to test app armor';

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
        // Just creating some happy txt files
	    $locations = ['/bin','/home','/etc','/home/forge/aaTrap/'];
	    foreach ( $locations as $location ) {
	        echo "\nAttempting: ".$location;
	    	@file_put_contents("$location/armorTest.txt","Hi, \nI'm a test for app Armor");
		    if(file_exists("$location/armorTest.txt")) {
			    echo "\n File Successfully created";
		    } else {
			    echo "\n File was not created";
		    }
	    }
    }
}
