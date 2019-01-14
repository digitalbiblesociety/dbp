<?php

namespace App\Console\Commands;

use App\Models\Bible\BibleVerse;
use App\Models\User\Study\HighlightColor;
use Illuminate\Console\Command;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;

use App\Models\User\User;
use App\Models\User\Study\Highlight;
use App\Models\User\Study\Note;
use App\Models\User\Study\Bookmark;

use App\Models\Country\Country;
use App\Models\User\Profile;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class syncV2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:v2 {action} {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync User information between v4 and v2 databases';

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

        $from_date = $this->argument('date');
        $from_date = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();

        switch($this->argument('action')) {
            case 'users':
                $this->syncUsers($from_date);
                break;
            case 'profiles':
                $this->syncProfiles($from_date);
                break;
            case 'notes':
                $this->syncNotes($from_date);
                break;
            case 'bookmarks':
                $this->syncBookmarks($from_date);
                break;
            case 'highlights':
                $this->syncHighlights($from_date);
                break;
        }
    }

    private function syncUsers($from_date)
    {
        $newV2Users = \DB::connection('dbp_users_v2')->table('user')->where('created', '>', $from_date)->get();
        $newV2UsersEmails = $newV2Users->pluck('email');
        $matchingV4UserEmails = User::whereIn('email', $newV2UsersEmails)->pluck('email');
        $newEmails = $newV2UsersEmails->diff($matchingV4UserEmails);

        foreach ($newV2Users as $user) {
            if ($newEmails->contains($user->email)) {
                User::create([
                    'v2_id'            => $user->id,
                    'name'             => $user->username ?? $user->email,
                    'password'         => bcrypt($user->password),
                    'first_name'       => $user->first_name,
                    'last_name'        => $user->last_name,
                    'token'            => str_random(24),
                    'email'            => $user->email,
                    'activated'        => (int) $user->confirmed,
                ]);
            } else {
                User::where('email', $user->email)->update(['v2_id' => $user->id]);
            }

        }
    }

    private function syncHighlights($from_date)
    {
        $this->highlightColors = HighlightColor::select('color', 'id')->get()->pluck('id','color')->toArray();
        $bibles = Bible::with('filesets')->get();
        $books = Book::select(['id_osis','id_usfx','id','protestant_order'])->get();

        \DB::connection('dbp_users_v2')
           ->table('highlight')
           ->where('created', '>', $from_date)
           ->orderBy('created')
           ->chunk(500, function ($highlights) use($bibles, $books) {
               foreach($highlights as $highlight) {
                   $this->syncHighlight($highlight, $bibles, $books);
               }
           });
    }

    private function syncHighlight($highlight, $bibles, $books)
    {
        $bible = $bibles->where('id', substr($highlight->dam_id, 0, 6))->first();
        if(!$bible) {
            Log::driver('seed_errors')->info('bb_nfd_'.substr($highlight->dam_id,0,6));
            return;
        }
        $fileset = $bible->filesets->where('set_type_code','text_plain')->first();
        if(!$fileset) {
            return;
        }
        $book = $books->where('id_osis', $highlight->book_id)->first();
        if($book === null) {
            $book = $books->where('protestant_order',$highlight->book_id)->first();
        }
        if($book === null) {
            Log::driver('seed_errors')->info('bb_nfd_'.$highlight->book_id);
            return;
        }

        $user_exists = User::where('v2_id',$highlight->user_id)->first();
        $verse = BibleVerse::where('hash_id',$fileset->hash_id)->select(['verse_text','chapter'])
                           ->where('book_id',$book->id)->where('chapter',$highlight->chapter_id)
                           ->where('verse_start',$highlight->verse_id)->first();

        if ($verse === null) {
            Log::driver('seed_errors')->info('bb_nfd_'.$fileset->id);
            throw new \Exception('Not getting a verse for bb_nfd_' . $fileset->id);
        }

        $v4Highlight = Highlight::firstOrNew([
            'user_id'           => $user_exists->id,
            'bible_id'          => $bible->id,
            'book_id'           => $book->id,
            'chapter'           => $highlight->chapter_id,
            'verse_start'       => $highlight->verse_id,
            'highlight_start'   => 1,
            'highlighted_words' => substr_count($verse->verse_text, ' ') + 1,
            'highlighted_color' => $this->getRelatedColorIdForHighlightColorString($highlight->color),
        ]);
        $v4Highlight->v2_id = $highlight->id;
        $v4Highlight->save();
    }

    private function getRelatedColorIdForHighlightColorString($colorString)
    {
        return $this->highlightColors[$colorString];
    }

    private function syncNotes($created_at)
    {
        $filesets = BibleFileset::with('bible')->get();
        $books = Book::select('id_osis','id')->get()->pluck('id','id_osis')->toArray();

        \DB::connection('dbp_users_v2')->table('note')->where('created', '>', $created_at)
            ->orderBy('id')->chunk(5000, function ($notes) use($filesets, $books) {
            foreach($notes as $note) {
                $fileset = $filesets->where('id', $note->dam_id)->first();
                if(!$fileset) $fileset = $filesets->where('id',substr($note->dam_id,0,-4))->first();
                if(!$fileset) $fileset = $filesets->where('id',substr($note->dam_id,0,-2))->first();
                if(!$fileset) {continue;}
                if($fileset->bible->first()) {
                    if(!isset($fileset->bible->first()->id)) {continue;}
                } else {
                    continue;
                }
                if(!isset($books[$note->book_id])) {continue;}

                $note = Note::firstOrNew([
                    'user_id'     => $note->user_id,
                    'bible_id'    => $fileset->bible->first()->id,    //  => "ENGESVO2ET"
                    'book_id'     => $books[$note->book_id],          //  => "Ezra"
                    'chapter'     => $note->chapter_id,               //  => "1"
                    'verse_start' => $note->verse_id,                 //  => "1"
                    'created_at'  => Carbon::createFromTimeString($note->created)->toDateString(),
                    'updated_at'  => Carbon::createFromTimeString($note->updated)->toDateString(),
                ]);
                $note->v2_id = $note->id;
                $note->save();
            }
        });

    }


    private function syncBookmarks($created_at)
    {
        $filesets = BibleFileset::with('bible')->get();
        $books = Book::select('id_osis','id')->get()->pluck('id','id_osis')->toArray();

        \DB::connection('dbp_users_v2')->table('bookmark')
           ->where('status','current')
           ->where('created', '>', $created_at)
           ->orderBy('id')->chunk(500, function ($bookmarks) use($filesets, $books) {
                foreach ($bookmarks as $bookmark) {

                    $user_exists = User::where('v2_id',$bookmark->user_id)->first();
                    while(!$user_exists) {

                        $v2_user = \DB::connection('dbp_users_v2')->table('user')->where('id',$bookmark->user_id)->first();
                        $user_exists = User::where('email', $v2_user->email)->first();
                        if(isset($user_exists)) {
                            $user_exists->v2_id = $v2_user->id;
                            $user_exists->save();
                            echo "\nUser v2 id updated";
                        } else {
                            sleep(15);
                            echo 'waiting for users seeder';
                            continue;
                        }

                    }

                    $fileset = $filesets->where('id', $bookmark->dam_id)->first();
                    if(!$fileset) $fileset = $filesets->where('id', substr($bookmark->dam_id, 0, -4))->first();
                    if(!$fileset) $fileset = $filesets->where('id', substr($bookmark->dam_id, 0, -2))->first();

                    if(!$fileset) {
                        echo "\nSkipping $bookmark->dam_id";
                        continue;
                    }

                    if(!$fileset->bible->first()) {
                        echo "\n Skipping". $bookmark->dam_id;
                        continue;
                    }

                    if(!isset($books[$bookmark->book_id])) {
                        echo "\n Skipping $bookmark->book_id";
                        continue;
                    }

                    $bookmark = Bookmark::firstOrNew([
                        'user_id'    => $user_exists->id,
                        'bible_id'   => $fileset->bible->first()->id,
                        'book_id'    => $books[$bookmark->book_id],
                        'chapter'    => $bookmark->chapter_id,
                        'verse_start'=> $bookmark->verse_id,
                        'created_at' => Carbon::createFromTimeString($bookmark->created)->toDateString(),
                        'updated_at' => Carbon::createFromTimeString($bookmark->updated)->toDateString()
                    ]);
                    $bookmark->v2_id = $bookmark->id;
                    $bookmark->save();


                    echo "\n".$bookmark->id;
                }
            });
    }

    private function syncProfiles($created_at)
    {
        $countries = Country::all();
        \DB::connection('dbp_users_v2')->table('user_profile')->where('field_value','!=','')
            ->where('created', '>', $created_at)->orderBy('id')
           ->chunk(500, function ($profiles) use($countries) {
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
