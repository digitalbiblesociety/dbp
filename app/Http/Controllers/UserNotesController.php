<?php

namespace App\Http\Controllers;

use App\Models\User\Note;
use App\Models\User\User;
use App\Models\Bible\Bible;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Validator;

class UserNotesController extends APIController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @OAS\Get(
	 *     path="/users/{user_id}/notes",
	 *     tags={"Version 4"},
	 *     summary="Get a list of Notes for a user/project combination",
	 *     description="In order to query information about a user's notes you must provide the project_id",
	 *     operationId="v4_notes.index",
	 *     @OAS\Parameter(name="bible_id",    in="query", description="If provided the fileset_id will filter results to only those related to the Bible", @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="book_id",     in="query", description="If provided the USFM 2.4 book id will filter results to only those related to the book", @OAS\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OAS\Parameter(name="chapter_id",  in="query", description="The starting chapter", @OAS\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OAS\Parameter(name="project_id",  in="query", required=true, description="The secret id assigned to your project", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(name="limit",       in="query", description="The number of highlights to return", @OAS\Schema(type="integer",example=15)),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(ref="#/components/parameters/sort_by"),
	 *     @OAS\Parameter(ref="#/components/parameters/sort_dir"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function index($user_id = null)
    {
    	if(!$this->api) {
    		$authorized_user = \Auth::user();
    		if(!$authorized_user) return $this->setStatusCode(401)->replyWithError('You must be logged in to access this page');
    		if(!$authorized_user->admin) return $this->setStatusCode(401)->replyWithError('You must have admin class access to manage user notes');

    		$notes['most_popular_bible'] = Bible::find(Note::selectRaw('count(*) AS count, bible_id')->groupBy('bible_id')->orderBy('count', 'DESC')->limit(1)->first()->bible_id)->currentTranslation->name;
		    $notes['count'] = Note::count();
		    $notes['most_prolific_user'] = User::find(Note::selectRaw('count(*) AS count, user_id')->groupBy('user_id')->orderBy('count', 'DESC')->limit(1)->first()->user_id)->name ?? "";

    		return view('dashboard.notes.index',compact('notes'));
	    }

	    $bible_id = checkParam('bible_id', null, 'optional');
	    $book_id = checkParam('book_id', null, 'optional');
	    $chapter_id = checkParam('chapter_id', null, 'optional');
	    $project_id = checkParam('project_id', null, 'optional');
	    $bookmark = explode('.',\Request::route()->getName());
	    $bookmark = ($bookmark[0] == "v4_bookmarks") ? true : false;
	    $limit = intval(checkParam('limit', null, 'optional') ?? 25);
	    $sort_by = checkParam('sort_by', null, 'optional');
	    $sort_dir = checkParam('sort_dir', null, 'optional') ?? "asc";

		$notes = Note::with('tags')->where('user_id',$user_id)->where('project_id',$project_id)
		->when($bible_id, function($q) use ($bible_id) {
			$q->where('bible_id', '=', $bible_id);
		})->when($book_id, function($q) use ($book_id) {
			$q->where('book_id', '=', $book_id);
		})->when($bookmark, function($q) {
			$q->where('bookmark', true);
		}, function($q) {
			$q->where('bookmark', false);
		})->when($sort_by, function($q) use ($sort_by,$sort_dir) {
			$q->orderBy($sort_by, $sort_dir);
		})->paginate($limit);

    	foreach($notes as $key => $note) $notes[$key]->notes = decrypt($note->notes);
		if(!$notes) return $this->setStatusCode(404)->replyWithError("No User found for the specified ID");
		return $this->reply($notes);
    }

	/**
	 * Show a single note.
	 *
	 * @OAS\Get(
	 *     path="/users/{user_id}/notes/{note_id}",
	 *     tags={"Version 4"},
	 *     summary="Get a single Note",
	 *     description="",
	 *     operationId="v4_notes.show",
	 *     @OAS\Parameter(name="bible_id",    in="query", description="", @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="book_id",     in="query", description="", @OAS\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OAS\Parameter(name="chapter_id",  in="query", description="", @OAS\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OAS\Parameter(name="project_id",  in="query", description="", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(name="limit",       in="query", description="", @OAS\Schema(type="integer",example=15)),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function show($user_id,$note_id)
    {
	    if(!$this->api) {
		    if(!Auth::user()->hasRole('admin')) return $this->setStatusCode(401)->replyWithError('You must have admin class access to manage user notes');
		    return view('dashboard.notes.index');
	    }

	    $project_id = checkParam('project_id');
	    $note = Note::where('project_id',$project_id)->where('user_id',$user_id)->where('id',$note_id)->first();
	    $note->notes = decrypt($note->notes);
	    if(!$note) return $this->setStatusCode(404)->replyWithError("No Note found for the specified ID");
	    return $this->reply($note);
    }

	/**
	 * Create a single note.
	 *
	 * @OAS\Post(
	 *     path="/users/{user_id}/notes/",
	 *     tags={"Version 4"},
	 *     summary="Store a Note",
	 *     description="",
	 *     operationId="v4_notes.store",
	 *     @OAS\Parameter(name="bible_id",    in="query", description="", @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="book_id",     in="query", description="", @OAS\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OAS\Parameter(name="chapter_id",  in="query", description="", @OAS\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OAS\Parameter(name="project_id",  in="query", description="", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(name="limit",       in="query", description="", @OAS\Schema(type="integer",example=15)),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function store(Request $request) {
	    $validator = Validator::make($request->all(), [
		    'bible_id'     => 'required|exists:bibles,id',
		    'user_id'      => 'required|exists:users,id',
		    'book_id'      => 'required|exists:books,id',
		    'project_id'   => 'required|exists:projects,id',
		    'chapter'      => 'required|max:150|min:1',
		    'verse_start'  => 'required|max:177|min:1',
		    'notes'        => 'required_without:bookmark',
		    'bookmark'     => 'required_without:notes|boolean',
	    ]);
	    if ($validator->fails()) return ['errors' => $validator->errors() ];
    	$note = Note::create([
    		'user_id'      => $request->user_id,
			'bible_id'     => $request->bible_id,
			'book_id'      => $request->book_id,
			'project_id'   => $request->project_id,
			'chapter'      => $request->chapter,
		    'verse_start'  => $request->verse_start,
		    'verse_end'    => $request->verse_start,
		    'bookmark'     => ($request->bookmark) ? 1 : 0,
			'notes'        => isset($request->notes) ? encrypt($request->notes) : null
	    ]);

	    $this->handleTags($request, $note);
    	return $this->reply(["success" => "Note created"]);
    }

	/**
	 * Update a single note.
	 *
	 * @OAS\Put(
	 *     path="/users/{user_id}/notes/{note_id}",
	 *     tags={"Version 4"},
	 *     summary="Update a Note",
	 *     description="",
	 *     operationId="v4_notes.update",
	 *     @OAS\Parameter(name="bible_id",    in="query", description="", @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(name="book_id",     in="query", description="", @OAS\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OAS\Parameter(name="chapter_id",  in="query", description="", @OAS\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OAS\Parameter(name="project_id",  in="query", description="", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(name="limit",       in="query", description="", @OAS\Schema(type="integer",example=15)),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function update(Request $request, $user_id, $note_id) {
    	$note = Note::where('project_id',$request->project_id)->where('user_id',$user_id)->where('id',$note_id)->first();
	    $note->fill($request->all());
	    if(isset($request->notes)) $note->notes = encrypt($request->notes);
		$note->save();

	    $this->handleTags($request, $note);
	    return $this->reply(["success" => "Note Updated"]);
    }

	/**
	 * Delete a single note.
	 *
	 * @OAS\Delete(
	 *     path="/users/{user_id}/notes/{note_id}",
	 *     tags={"Version 4"},
	 *     summary="Delete a Note",
	 *     description="",
	 *     operationId="v4_notes.destroy",
	 *     @OAS\Parameter(name="project_id",  in="query", description="", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function destroy($user_id,$note_id)
    {
	    $project_id = checkParam('project_id');
	    $note = Note::where('project_id',$project_id)->where('user_id',$user_id)->where('id',$note_id)->first();
	    if(!$note) $this->setStatusCode(404)->replyWithError("Note Not Found");
	    $note->delete();
	    return $this->reply(["success" => "Note Deleted"]);
    }

    public function handleTags(Request $request, $note)
    {
	    $tags = collect(explode(',',$request->tags))->map(function ($tag) {
		    if(strpos($tag, ':::') !== false) {
			    $tag = explode(':::',$tag);
			    return ['value' => ltrim($tag[1]), 'type'  => ltrim($tag[0])];
		    } else {
			    return ['value' => ltrim($tag), 'type'  => 'general'];
		    }
	    })->toArray();

	    if($request->method() == "POST") $note->tags()->createMany($tags);
	    if($request->method() == "PUT") {$note->tags()->delete(); $note->tags()->createMany($tags);}

    }

}
