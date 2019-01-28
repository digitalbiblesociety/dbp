<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User\User;
use App\Models\Language\Language;
use App\Models\Country\Country;
use App\Models\User\Profile;

use Carbon\Carbon;

class syncV2Profiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncV2:profiles {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the Profiles with the V2 Database';

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
        $from_date = $this->argument('date') ?? '00-00-00';
        $from_date = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();

        $countries = Country::all();
        \DB::connection('dbp_users_v2')->table('user_profile')->where('field_value','!=','')
           ->where('created', '>', $from_date)
           ->orderBy('id')
           ->chunk(5000, function ($profiles) use($countries) {
               foreach ($profiles as $profile) {
                   $user_exists = User::where('v2_id',$profile->user_id)->first();
                   while(!$user_exists) {
                       sleep(15);
                       $user_exists = User::where('v2_id',$profile->user_id)->first();
                   }

                   echo "\n".$profile->id;
                   $current_profile = Profile::where('user_id',$user_exists->id)->first();
                   if(!$current_profile) {
                       $current_profile = Profile::create(['user_id' => $user_exists->id]);
                   }

                   if(\strlen($profile->field_value) > 191) {
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
                           break;
                           $current_date = str_replace('_','/',$profile->field_value);
                           $current_date = str_replace('-','/',$current_date);
                           try {
                               $current_date = \Carbon\Carbon::parse($current_date)->toDateTimeString();
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
                           if(\strlen($profile->field_value) > 22) {
                               //echo "\nToo long for phone:".$profile->field_value;
                               break;
                           }
                           $current_profile->phone = $profile->field_value;
                           break;
                       }
                       case 'locale': {
                           $lang = Language::where('iso_2B',substr($profile->field_value,0,2))->select('id')->first();
                           if($lang) {
                               $current_profile->language_id = $lang->id;
                           }
                           break;
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
