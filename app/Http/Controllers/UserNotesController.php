<?php

namespace App\Http\Controllers;

use App\Models\User\Note;
use App\Models\User\User;
use App\Models\Bible\Bible;
use Illuminate\Http\Request;
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

		$notes = Note::where('user_id',$user_id)
		->when($bible_id, function($q) use ($bible_id) {
			$q->where('bible_id', '=', $bible_id);
		})->when($bible_id, function($q) use ($book_id) {
			$q->where('book_id', '=', $book_id);
		})->paginate(25);
    	foreach($notes as $key => $note) {
		    $notes[$key]->notes = decrypt($note->notes);
	    }
		if(!$notes) return $this->setStatusCode(404)->replyWithError("No User found for the specified ID");
		return $this->reply($notes);
    }

    public function show($note_id)
    {
	    if(!$this->api) {
		    if(!Auth::user()->hasRole('admin')) return $this->setStatusCode(401)->replyWithError('You must have admin class access to manage user notes');
		    return view('dashboard.notes.index');
	    }
	    $note = Note::find($note_id);
	    if(!$note) return $this->setStatusCode(404)->replyWithError("No Note found for the specified ID");
	    return $this->reply($note);
    }

    public function store(Request $request) {

	    $validator = Validator::make($request->all(), [
		    'bible_id'     => 'required|exists:bibles,id',
		    'chapter'      => 'required',
		    'verse_start'  => 'required',
		    'notes'        => 'required',
	    ]);
	    if ($validator->fails()) return ['errors' => $validator->errors() ];
    	Note::create([
    		'user_id'      => $request->user_id,
			'bible_id'     => $request->bible_id,
			'chapter'      => $request->chapter,
		    'verse_start'  => $request->verse_start,
		    'verse_end'    => $request->verse_start,
			'highlights'   => $request->highlights,
			'notes'        => $request->notes
	    ]);

    	return $this->reply(["success" => "Note created"]);
    }

    public function update(Request $request) {
    	$note = Note::where('user_id',$request->user_id)->where('id',$request->id)->first();
    	$note->highlights = $request->highlights;
	    $note->notes = $request->notes;
	    $note->save();
	    return $this->reply(["success" => "Note Updated"]);
    }
}
