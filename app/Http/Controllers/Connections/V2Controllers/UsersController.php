<?php

namespace App\Http\Controllers\Connections\V2Controllers;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\User\Account;
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

class UsersController extends APIController
{

	public function __construct()
	{
		parent::__construct();
		$this->hash = md5(date('m/d/Y').env('BIS_API_KEY').env('BIS_API_SECRET'));
	}

	public function user()
	{
		$user = User::where('id',request()->id)->first();
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
		if($this->hash === request()->hash) {
			$alt_url = checkParam('alt_url', null, 'optional');
			$provider = request()->remote_type;
			if($provider == 'twitter') return $this->setStatusCode(422)->replyWithError(trans('api.auth_errors_twitter_stateless'));

			$user = User::whereHas('accounts', function ($query) {
				$query->where('provider_user_id', request()->remote_id)->where('provider_id', request()->remote_type);
			})->where('email',request()->email)->first();

			if(!$user) {
				// Check if user already exists but just hasnt signed up with that account
				$user    = User::where('email', request()->email)->first();
				if(!$user) {
					$user = User::create(['email' => request()->email()]);
					$account = new Account(['provider_user_id' => request()->remote_id, 'provider_id' => request()->remote_type]);
				}

				$account->user()->associate($user);
				$account->save();
			}

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
		$count_only = checkParam('count_only',null,'optional');
		$user_id = checkParam('user_id');
		$user = User::withCount('notes','highlights','bookmarks')->where('id',$user_id)->first();

		if($count_only) {
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
	public function annotationBookmark()
	{
		$limit   = checkParam('limit',null,'optional') ?? 1000;
		$offset  = checkParam('offset',null,'optional') ?? 0;
		$user_id = checkParam('user_id');

		$bookmarks = \DB::table('user_bookmarks')
			->where('user_id', $user_id)
			->join('dbp.bibles','bibles.id','user_bookmarks.bible_id')
			->join('dbp.bible_books', function ($join) {
				$join->on('bibles.id','bible_books.bible_id')
				     ->on('user_bookmarks.book_id', 'bible_books.book_id');
			})
			->join('dbp.books','user_bookmarks.book_id','books.id')
			->select([
				'books.protestant_order as protestant_order',
				'books.id_osis as book_id',
				'books.book_testament',
				'user_id',
				'chapter',
				'verse_start',
				'user_bookmarks.id as id',
				'bibles.id as bible_id',
				'bible_books.name as book_name',
				'user_bookmarks.created_at',
				'user_bookmarks.updated_at'
			])
			->paginate($limit);

		$queryParams = array_diff_key($_GET, array_flip(['page']));
		$bookmarks->appends($queryParams);
		return $this->reply(fractal($bookmarks->getCollection(), new BookmarkTransformer(),new BookmarkArraySerializer())->paginateWith(new IlluminatePaginatorAdapter($bookmarks)));
	}

	/**
	 * @return mixed
	 */
	public function annotationBookmarkStore()
	{
		if(request()->hash === $this->hash) {
			$book = Book::where('id_osis',request()->book_id)->first();
			$bibleFileset = BibleFileset::with('bible')
				->where('bible_filesets.id', request()->dam_id)
				->orWhere('bible_filesets.id',substr(request()->dam_id,0,-4))
				->orWhere('bible_filesets.id',substr(request()->dam_id,0,-2))->first();
			$bookmark = Bookmark::create([
				'bible_id'    => @$bibleFileset->bible->first()->id ?? $bibleFileset->id,
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
	public function annotationHighlight()
	{
		$fileset_id = checkParam('dam_id', null, 'optional');
		$limit = checkParam('limit', null, 'optional') ?? 1000;

		if(checkParam('hash') === $this->hash) {
			$user_id = checkParam('user_id');
			$highlights = Highlight::with('color')->where('user_id', $user_id)
				->join(env('DBP_DATABASE').'.bible_filesets as fileset', 'fileset.id', '=', env('DBP_USERS_DATABASE').'.user_highlights.fileset_id')
				->join(env('DBP_DATABASE').'.bible_fileset_connections as connection', 'connection.hash_id', 'fileset.hash_id')
				->join(env('DBP_DATABASE').'.bible_books', function ($join) {
				    $join->on('connection.bible_id', '=', 'bible_books.bible_id')
				         ->on('bible_books.book_id', '=', 'user_highlights.book_id');
				})
					->join(env('DBP_DATABASE').'.books', 'books.id', '=', 'user_highlights.book_id')
				->when($fileset_id, function ($q) use ($fileset_id) {
				    $q->where('fileset_id', $fileset_id);
				})->select([
					'user_highlights.id',
					'user_highlights.fileset_id',
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
			return $this->reply(fractal($highlights->getCollection(), HighlightTransformer::class,HighlightArraySerializer::class)->paginateWith(new IlluminatePaginatorAdapter($highlights)));
		}

		return $this->setStatusCode(401)->reply('hash not matched');
	}

	/**
	 * The Highlight Store
	 *
	 * @return $this|\Illuminate\Database\Eloquent\Model
	 */
	public function annotationHighlightAlter()
	{
		if($this->hash === request()->hash) {

			if(request()->method() == 'DELETE') {
				$deletedHighlight = Highlight::where('id',request()->id)->delete();
				return [];
			}

			$book = Book::where('id_osis',request()->book_id)->first();
			$fileset_id = strtoupper(substr(request()->dam_id,0,6));
			$chapter = \DB::connection('sophia')->table($fileset_id.'_vpl')->where('chapter',request()->chapter_id)->where('book',$book->id_usfx)->where('verse_start',request()->verse_id)->first();
			if(!$chapter) return $this->setStatusCode(404)->replyWithError('No bible_fileset found');
			$highlightColor = HighlightColor::where('color',request()->color)->first();
			$highlight = Highlight::create([
				'user_id'           => request()->user_id,
				'book_id'           => $book->id,
				'fileset_id'        => $fileset_id,
				'chapter'           => request()->chapter_id,
				'verse_start'       => request()->verse_id,
				'highlight_start'   => 1,
				'highlighted_words' => substr_count($chapter->verse_text, ' ') + 1,
				'highlighted_color' => $highlightColor->id
			]);
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
	public function annotationNote()
	{
		if ($this->hash === checkParam('hash')) {
			$user_id = checkParam('user_id', null, 'optional');
			$updated = checkParam('updated', null, 'optional') ?? Carbon::createFromDate(1969);
			$notes = Note::with('book:id,id_osis,book_testament')->where('user_id', $user_id)->where('updated_at', '>', $updated)->get();

			return $this->reply(fractal($notes, NoteTransformer::class,NoteArraySerializer::class));
		}
		return $this->setStatusCode(401)->replyWithError('hash does not match');
	}

	/**
	 *
	 * @return $this|\Illuminate\Database\Eloquent\Model
	 */
	public function annotationNoteStore()
	{
		if ($this->hash === request()->hash) {
			$book = Book::where('id_osis',request()->book_id)->first();
			$fileset = BibleFileset::where('id',substr(request()->dam_id,0,6))->first();
			if(!$fileset) return $this->setStatusCode(404)->replyWithError('fileset not found');
			$bible = $fileset->bible->first();
			if(!$bible) return $this->setStatusCode(404)->replyWithError('Bible not found');
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