<?php

use Illuminate\Database\Seeder;
use App\Models\User\Profile;

class users_profile_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$countries = \App\Models\Country\Country::all();
	    \DB::connection('dbp_users_v2')->table('user_profile')->where('field_value','!=','')->orderBy('id')
	       ->chunk(100, function ($profiles) use($countries) {
		       foreach ($profiles as $profile) {
			       echo "\n".$profile->id;
			       $current_profile = Profile::where('user_id',$profile->user_id)->first();
			       if(!$current_profile) $current_profile = Profile::create(['user_id' => $profile->user_id]);
			       if(strlen($profile->field_value) > 191) {
				       echo "\nToo long for ".$profile->field_name.': '.$profile->field_value;
				       continue;
			       }
			       switch($profile->field_name) {
				       case 'country': {
				       	    if($profile->field_value == 'USA' || 'United States of America' || 'US') {
					            $current_profile->country_id = 'US';
				            } else {
					            $current_country = $countries->where('name',$profile->field_value)->first();
					            if($profile->field_value === '') {break;}
					            if(!$current_country) {dd('Country: '.$profile->field_value);}
					            $current_profile->country_id = $current_country->id;
				            }
					        break;
				       }

				       case 'birthday':
				       case 'birthdate': {
					       $current_date = str_replace('_','/',$profile->field_value);
					       $current_date = str_replace('-','/',$current_date);
					       try {
						       $current_date = \Carbon\Carbon::parse($current_date)->toDateTime();
						       $current_profile->birthday = $current_date;
						       if(!$current_date) {break;}
					       } catch (Exception $e) {
						       break;
					       }
				       	   break;
				       }

				       case 'gender': {
							$current_profile->sex = ($profile->field_value == 'female') ? 2 : 1;
							break;
				       }

				       case 'download_status':
				       case 'testitem':
				       case 'testitem2':
				       case 'age_range': {
							break;
				       }

				       case 'phone_number':
				       case 'phone': {
					       if(strlen($profile->field_value) > 22) {
						       //echo "\nToo long for phone:".$profile->field_value;
						       break;
					       }
					       $current_profile->phone = $profile->field_value;
					       break;
				       }

				       case 'locale': {
					       if(strlen($profile->field_value) > 5) {
						       //echo "\nToo long for locale:".$profile->field_value;
						       break;
					       }
				       }

				       default: {
					       $current_profile->{$profile->field_name} = $profile->field_value;
				       }
			       }
			       $current_profile->save();
		       }
	       });

    }
}
