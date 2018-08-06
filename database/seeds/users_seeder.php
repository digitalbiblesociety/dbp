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
	    $users = $seederHelper->csv_to_array(storage_path('data/users.csv'));

	    foreach($users as $user) {
		    if($this->validUser($user)) {
	    	    $currentUser = [
	    	    	'id'                => $user['id'],
				    //'username'          => $user['nickname'] ?? $user['email'],
				    'password'          => 'needs_resetting',
	    	    	'name'              => $user['name'],
				    'token'             => str_random(64),
				    'email'             => $user['email'],
				    'activated'         => ($user['verified'] == NULL) ? false : true,
		        ];
		        User::create($currentUser);
		    }
	    }
    }

    public function validUser($user)
    {
    	$verified_badEmails = ['samplefakeemail@mail.com','email','alfakeomega@gmail.com','angelafakes2918@gmail.com','anotherfake@mail.com','b.huffaker@americanexchange.com','blackfakenevo@hotmail.com','bola.fakeye@gmail.com','c8fake2@gmail.com','Dansfakeemail@yahoo.com','davidfakenge@gmail.com','davidpuh@emailfake.us','dorisfakeh@gmail.com','ela.rumfaker@gmail.com','eusoufakedealguem@gmail.com','facetofake@hotmail.fr','fake.laughing@gmail.com','fake.myles@hotmail.com','Fake@fake.com','fake@fakeemail.com','fake123@address.com','fakearney@yahoo.com','fakeeemail@mail.com','Fakeemail@fakeemail.com','fakeEmail@gmail.com','fakeemail@mail.com','fakeju64@yahoo.com','FAkemail@fakemail.com','fakemattjohn@gmail.com','faker2@email.com','fakerfaker561@gmail.com','fakerzahb@gmail.com','fakeslapper@gmail.com','gfakerdinova@gmail.com','Goldenfake2@gmail.com','hackeado-fake@hotmail.com','huffakern@gmail.com','latifakedjar@gmail.com','mackenzie_fake11@hotmail.com','mbfake789@gmail.com','mulletfake@gmail.com','nameisfake@fakename.fknm','nofakejake@aol.com','Oi.xau_fake@hotmail.com','priououd@fakeinbox.info','robert@fakeinbox.com','samplefakeemail@mail.com','sifakerich@gmail.com','sillyguy@fakeaddress.com','Skyiiisntfake@gmail.com','snadhelta@fake.com','Testemailfake@gmail.com','This.is.a@fake.email.com.net.edu.org.us','thisisfake@555.org','tom@fakeinbox.com','Ufake@hotmail.com','wrotreja@fakeinbox.com','yemisifakeye@yahoo.com'];
		$verified_badNames = ['NULL', '.'];
	    $validator = Validator::make($user, [
		    'email' => ['required','unique:users,email','max:255','email',Rule::notIn($verified_badEmails)],
		    'name'  => ['required','string',Rule::notIn($verified_badNames)]
	    ]);
	    if($validator->fails()) return false;
		return true;
    }

}
