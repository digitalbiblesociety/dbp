<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleVerse;
use App\Models\Bible\Book;
use App\Models\User\Account;
use App\Models\User\Project;
use App\Models\User\ProjectMember;
use App\Models\User\Role;
use App\Models\User\Study\Bookmark;
use App\Models\User\Study\Highlight;
use App\Models\User\Study\HighlightColor;
use App\Models\User\Study\Note;
use App\Models\User\User;
use App\Transformers\Serializers\BookmarkArraySerializer;
use App\Transformers\Serializers\HighlightArraySerializer;
use App\Transformers\Serializers\NoteArraySerializer;
use App\Transformers\V2\Annotations\BookmarkTransformer;
use App\Transformers\V2\Annotations\HighlightTransformer;
use App\Transformers\V2\Annotations\NoteTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Carbon\Carbon;

class UsersControllerV2 extends APIController
{

    public function __construct()
    {
        $this->preset_v = 2;
        parent::__construct();
        $this->hash = md5(date('m/d/Y').config('services.bibleIs.key').config('services.bibleIs.secret'));
    }

    public function user()
    {
        if (request()->method() === 'POST') {
            $user = $user = User::where('email', request()->email)->first();
            if (!$user) {
                $user = User::create([
                    'email' => request()->email,
                    'password' => request()->password,
                    'name' => request()->username,
                    'token' => unique_random('users', 'token')
                ]);
                return ['id' => (string) $user->id];
            }
        } else {
            $user = User::where('id', request()->id)->first();
            if (!$user) {
                return $this->setStatusCode(401)->replyWithError('User not found');
            }
        }

        return [[
            'id'                => (string) $user->id,
            'first_name'        => (string) $user->first_name,
            'last_name'         => (string) $user->last_name,
            'username'          => (string) $user->name,
            'email'             => (string) $user->email,
            'password'          => (string) $user->password,
            'created'           => (string) $user->created_at,
            'updated'           => (string) $user->updated_at,
            'confirmed'         => (string) $user->activated,
            'confirmation_code' => (string) $user->token,
            'forgot_code'       => null,
            'role'              => 'User',
            'email_updates'     => '0'
        ]];
    }

    /*
     * haven't found a route with working banners yet;
     */
    public function banner()
    {
        return '';
    }
    /**
     * @return mixed
     */
    public function login()
    {
        $email    = request()->email ?? request()->login;
        $provider = request()->remote_type;
        $password = request()->password;

        if ($this->hash === request()->hash) {
            $alt_url = checkParam('alt_url');
            if ($provider == 'twitter') {
                return $this->setStatusCode(422)->replyWithError(trans('api.auth_errors_twitter_stateless'));
            }

            $user = User::whereHas('accounts', function ($query) {
                $query->where('provider_user_id', request()->remote_id)->where('provider_id', request()->remote_type);
            })->where('email', $email)->first();

            if (!$user) {
                // Check if user already exists but just hasn't signed up with that account
                $user    = User::firstOrCreate(['email' => $email]);
                if(!isset($password)) {
                    Account::firstOrCreate([
                        'project_id'       => Project::where('name', 'Bible.is')->first()->id,
                        'user_id'          => $user->id,
                        'provider_user_id' => request()->remote_id,
                        'provider_id'      => request()->remote_type
                    ]);
                }
            }

            ProjectMember::firstOrCreate([
                'user_id'    => $user->id,
                'project_id' => Project::where('name', 'Bible.is')->first()->id,
                'role_id'    => Role::where('slug','user')->first()->id,
            ]);

            return $this->reply([
                'id'        => (string) $user->id,
                'user_data' => [[
                    'id'                => (string) $user->id,
                    'first_name'        => (string) $user->first_name,
                    'last_name'         => (string) $user->last_name,
                    'username'          => (string) $user->name,
                    'email'             => (string) $user->email,
                    'password'          => (string) $user->password,
                    'created'           => (string) $user->created_at->toDateTimeString(),
                    'updated'           => (string) $user->updated_at->toDateTimeString(),
                    'confirmed'         => (string) $user->activated,
                    'confirmation_code' => (string) $user->token,
                    'forgot_code'       => null,
                    'role'              => 'User',
                    'email_updates'     => '0'
                ]]
            ]);
        }
    }

    public function profile()
    {           
        /*
        //TODO : May need to redirect to the following

        return redirect()->route('v4_user.show', request()->all());
        */

        return [
            ['Title' => 'Resource Invalid (improperly formatted request)'],
            ['Error' => ['You must enter a field name']]
        ];
    }

    /**
     * @return mixed
     */
    public function annotationList()
    {
        $count_only = checkParam('count_only');
        $user_id = checkParam('user_id');
        $user = User::withCount('notes', 'highlights', 'bookmarks')->where('id', $user_id)->first();

        if ($count_only) {
            return $this->reply([
                'highlight' => $user->highlights_count,
                'note'      => $user->notes_count,
                'bookmark'  => $user->bookmarks_count
            ]);
        }

        return [];
    }

    /**
     * Emulates the bookmark listing function of the api.bible.is
     * Discovered inputs:
     *
     *
     * @return mixed
     */
    public function bookmark()
    {
        $limit   = checkParam('limit') ?? 1000;
        $offset  = checkParam('offset') ?? 1;
        $user_id = checkParam('user_id');

        $bookmarks = Bookmark::where('user_id', $user_id)
            ->with(['book','bible.filesets' => function($query) {
                $query->where('asset_id', 'dbp-prod')->where('set_type_code', 'text_plain');
            }])
            ->skip($offset)
            ->paginate($limit);

        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $bookmarks->appends($queryParams);
        return $this->reply(fractal($bookmarks->getCollection(), new BookmarkTransformer(), new BookmarkArraySerializer())->paginateWith(new IlluminatePaginatorAdapter($bookmarks)));
    }

    /**
     * @return mixed
     */
    public function bookmarkAlter()
    {
        if (request()->hash === $this->hash) {

            if(request()->_method === 'delete') {
                Bookmark::where('id', request()->id)->delete();
                return ['Status' => 'Done'];
            }

            $book = Book::where('id_osis', request()->book_id)->first();
            $bibleFileset = BibleFileset::with('bible')->uniqueFileset(request()->dam_id, 'dbp-prod', 'text_plain')->first();
            $bookmark = Bookmark::create([
                'bible_id'    => $bibleFileset->bible->id,
                'book_id'     => $book->id,
                'chapter'     => request()->chapter_id,
                'verse_start' => request()->verse_id,
                'user_id'     => request()->user_id,
            ]);
            return [
                'user_id'    => (string) $bookmark->user_id,
                'dam_id'     => (string) request()->dam_id,
                'book_id'    => (string) request()->book_id,
                'chapter_id' => (string) $bookmark->chapter,
                'verse_id'   => (string) $bookmark->verse_start,
                'updated'    => (string) $bookmark->updated_at,
                'created'    => (string) $bookmark->created_at,
                'id'         => (string) $bookmark->id,
            ];
        }
    }

    /**
     *
     *
     */
    public function highlight()
    {
        $fileset_id = checkParam('dam_id');
        $bible_id = BibleFileset::where('id', $fileset_id)->first()->id ?? strtoupper(substr($fileset_id, 0, 6));
        $limit = checkParam('limit') ?? 1000;

        if (checkParam('hash', true) === $this->hash) {
            $user_id = checkParam('user_id');
            $highlights = Highlight::with('color')->where('user_id', $user_id)
                ->join(config('database.connections.dbp.database').'.bible_books', function ($join) {
                    $join->on('user_highlights.bible_id', '=', 'bible_books.bible_id')
                         ->on('bible_books.book_id', '=', 'user_highlights.book_id');
                })
                    ->join(config('database.connections.dbp.database').'.books', 'books.id', '=', 'user_highlights.book_id')
                ->when($fileset_id, function ($q) use ($bible_id) {
                    $q->where('user_highlights.bible_id', $bible_id);
                })->select([
                    'user_highlights.id',
                    'user_highlights.bible_id',
                    'books.id_osis as book_id',
                    'bible_books.name as book_name',
                    'books.protestant_order as protestant_order',
                    'books.book_testament',
                    'user_highlights.user_id',
                    'user_highlights.chapter',
                    'user_highlights.verse_start',
                    'user_highlights.highlight_start',
                    'user_highlights.highlighted_words',
                    'user_highlights.highlighted_color',
                    'user_highlights.updated_at',
                    'user_highlights.created_at',
                ])->orderBy('user_highlights.updated_at')->paginate($limit);
            return $this->reply(fractal($highlights->getCollection(), HighlightTransformer::class, HighlightArraySerializer::class)->paginateWith(new IlluminatePaginatorAdapter($highlights)));
        }

        return $this->setStatusCode(401)->reply('hash not matched');
    }

    /**
     * The Highlight Store
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function highlightAlter()
    {
        if ($this->hash === request()->hash) {
            if (request()->method() == 'DELETE') {
                $deletedHighlight = Highlight::where('id', request()->id)->delete();
                return [];
            }

            $book = Book::where('id_osis', request()->book_id)->first();
            $fileset = BibleFileset::uniqueFileset(request()->dam_id, 'dbp-prod', 'text_plain')->first();
            $chapter = BibleVerse::where('hash_id', $fileset->hash_id)->where('chapter', request()->chapter_id)
                        ->where('book_id', $book->id)->where('verse_start', request()->verse_id)->first();
            if (!$chapter) {
                return $this->setStatusCode(404)->replyWithError('No bible_fileset found');
            }
            $highlightColor = HighlightColor::where('color', request()->color)->first();
            $highlight_content = [
                'user_id'           => request()->user_id,
                'book_id'           => $book->id,
                'bible_id'          => $fileset->id,
                'chapter'           => request()->chapter_id,
                'verse_start'       => request()->verse_id,
                'highlight_start'   => 1
            ];
            $highlight = Highlight::where($highlight_content)->first();

            if($highlight) {
                $highlight->highlighted_color = $highlightColor->id;
                $highlight->save();
            } else {
                $highlight = Highlight::create(
                    array_merge($highlight_content, [
                        'highlighted_words' => substr_count($chapter->verse_text, ' ') + 1,
                        'highlighted_color' => $highlightColor->id
                    ])
                );
            }

            return $this->reply([
                'user_id'       => (string) $highlight->user_id,
                'dam_id'        => request()->dam_id,
                'book_id'       => (string) request()->book_id,
                'chapter_id'    => (string) $highlight->chapter,
                'verse_id'      => (string) $highlight->verse_start,
                'color'         => (string) request()->color,
                'updated'       => (string) $highlight->updated_at,
                'id'            => (string) $highlight->id
            ]);
        }
        return $this->reply('hash did not match');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function note()
    {
        if ($this->hash === checkParam('hash', true)) {
            $user_id = checkParam('user_id');
            $updated = Carbon::createFromDate(2000, 01, 01);

            $notes = Note::with('book')->where('user_id', $user_id)->where('updated_at', '>', $updated)->get();
            return $this->reply(fractal($notes, NoteTransformer::class, NoteArraySerializer::class));
        }
        return $this->setStatusCode(401)->replyWithError('hash does not match');
    }

    /**
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function noteAlter()
    {
        if ($this->hash === request()->hash) {

            if(request()->_method === 'delete') {
                Note::where('id', request()->id)->delete();
                return ['Status' => 'Done'];
            }

            $book = Book::where('id_osis', request()->book_id)->first();
            $fileset = BibleFileset::where('id', substr(request()->dam_id, 0, 6))->first();
            if (!$fileset) {
                return $this->setStatusCode(404)->replyWithError('fileset not found');
            }
            $bible = $fileset->bible->first();
            if (!$bible) {
                return $this->setStatusCode(404)->replyWithError('Bible not found');
            }
            $note = Note::create([
                'user_id'       => request()->user_id,
                'book_id'       => $book->id,
                'bible_id'      => $bible->id,
                'chapter'       => request()->chapter_id,
                'verse_start'   => request()->verse_id,
                'notes'         => encrypt(urldecode(request()->note)),
            ]);
            return [
                'user_id'       => (string) $note->user_id,
                'dam_id'        => request()->dam_id,
                'book_id'       => (string) request()->book_id,
                'chapter_id'    => (string) $note->chapter,
                'verse_id'      => (string) $note->verse_start,
                'note'          => $note->notes,
                'updated'       => (string) $note->updated_at,
                'created'       => (string) $note->created_at,
                'id'            => (string) $note->id
            ];
        }
        return $this->setStatusCode(401)->replyWithError('hash does not match');
    }
}
