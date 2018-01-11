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
	    \DB::table('user_keys')->delete();
    	\DB::table('users')->delete();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "admin";
	    $user->email = "amdin@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "Test Build Bible.is";
	    $user->save();

	    $key = \App\Models\User\Key::create([
	    	'key' => "e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824",
		    'user_id' => $user->id
	    ])->save();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "Emijo J.";
	    $user->email = "emily@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "Dalton M.";
	    $user->email = "dalton@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "Basha L.";
	    $user->email = "bishara@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "Nathan Daniels";
	    $user->email = "nathan@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "Shannon Gale";
	    $user->email = "shannon@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "Ken B.";
	    $user->email = "ken@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "Rudolf K.";
	    $user->email = "kurt@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();

	    $user = new User();
	    $user->id = $this->generateRandomString();
	    $user->name = "Anesse M.";
	    $user->email = "aggie@dbs.org";
	    $user->password = Hash::make("2ch714");
	    $user->save();
    }

	public function generateRandomString($length = 16)
	{
		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}

}
