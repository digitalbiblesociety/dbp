<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;
class users_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	    $user = new User();
	    $user->id = "1234";
	    $user->name = "admin";
	    $user->email = "amdin@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->id = "7dfa67f0b5e111e7aaf8a53dd0c8fc55";
	    $user->name = "Test Build IOS";
	    $user->save();

	    $user = new User();
	    $user->name = "Emijo J.";
	    $user->email = "emily@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->name = "Dalton M.";
	    $user->email = "dalton@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->name = "Basha L.";
	    $user->email = "bishara@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->name = "Nathan Daniels";
	    $user->email = "nathan@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->name = "Shannon Gale";
	    $user->email = "shannon@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->name = "Ken B.";
	    $user->email = "ken@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->name = "Rudolf K.";
	    $user->email = "kurt@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->name = "Anesse M.";
	    $user->email = "aggie@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();
    }
}
