<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;

use Illuminate\Validation\Rule;
class users_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::statement("SET foreign_key_checks=0");
	    User::truncate();
	    DB::statement("SET foreign_key_checks=1");

	    $seederHelper = new \database\seeds\SeederHelper();
	    ini_set('memory_limit', '2064M');
	    set_time_limit(-1);
	    $users = $seederHelper->csv_to_array(storage_path('data/dbp2/bibleis_api_prod7-26-18_user.csv'));

	    foreach($users as $user) {
	    	if(!$this->validUser($user)) { continue; }
			$currentUser = [
				'notes'             => $user['id'],
			    //'username'          => $user['nickname'] ?? $user['email'],
			    'password'          => 'needs_resetting',
			    'first_name'        => $user['first_name'],
			    'last_name'         => $user['last_name'],
				'name'              => $user['first_name'].' '.$user['last_name'],
			    'token'             => str_random(64),
			    'email'             => $user['email'],
			    'activated'         => ($user['confirmed'] == NULL) ? false : true,
			];
			User::create($currentUser);
	    }
    }

    public function validUser($user)
    {
	    $validator = Validator::make($user, [
		    'email' => ['required','unique:users,email','max:255','email']
	    ]);
	    if($validator->fails()) return false;
		return true;
    }

}
