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
	    $bookmark = ($bookmark[1] == "v4_bookmark") ? true : false;
	    $limit = checkParam('limit', null, 'optional') ?? 25;

		$notes = Note::with('tags')->where('user_id',$user_id)->where('project_id',$project_id)
		->when($bible_id, function($q) use ($bible_id) {
			$q->where('bible_id', '=', $bible_id);
		})->when($book_id, function($q) use ($book_id) {
			$q->where('book_id', '=', $book_id);
		})->when($bookmark, function($q) {
			$q->where('bookmark', true);
		})->orderBy('updated_at')->paginate($limit);

    	foreach($notes as $key => $note) $notes[$key]->notes = decrypt($note->notes);
		if(!$notes) return $this->setStatusCode(404)->replyWithError("No User found for the specified ID");
		return $this->reply($notes);
    }

    public function show($note_id)
    {
	    if(!$this->api) {
		    if(!Auth::user()->hasRole('admin')) return $this->setStatusCode(401)->replyWithError('You must have admin class access to manage user notes');
		    return view('dashboard.notes.index');
	    }

	    $project_id = checkParam('project_id');
	    $note = Note::where('project_id',$project_id)->find($note_id);
	    if(!$note) return $this->setStatusCode(404)->replyWithError("No Note found for the specified ID");
	    return $this->reply($note);
    }

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

    public function update(Request $request, $user_id, $note_id) {
    	$note = Note::where('project_id',$request->project_id)->where('user_id',$user_id)->where('id',$note_id)->first();
	    $note->fill($request->all())->save();

	    $this->handleTags($request, $note);
	    return $this->reply(["success" => "Note Updated"]);
    }

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
