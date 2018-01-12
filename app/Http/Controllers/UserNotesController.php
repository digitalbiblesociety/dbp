<?php

namespace App\Http\Controllers;

use App\Models\User\Note;
use Illuminate\Http\Request;
use Validator;
class UserNotesController extends APIController
{
    public function index($user_id)
    {
		$user = Note::where('user_id',$user_id)->paginate(25);
		if(!$user) return $this->setStatusCode(404)->replyWithError("No User found for the specified ID");
		return $this->reply($user);
    }

    public function store(Request $request) {

	    $validator = Validator::make($request->all(), [
		    'bible_id'     => 'required|exists:bibles,id',
		    'reference_id' => 'required',
		    'notes'        => 'required',
	    ]);
	    if ($validator->fails()) return ['errors' => $validator->errors() ];
    	Note::create([
    		'user_id'      => $request->user_id,
			'bible_id'     => $request->bible_id,
			'reference_id' => $request->reference_id,
			'highlights'   => $request->highlights,
			'notes'        => $request->notes
	    ]);
    }

    public function update(Request $request) {
    	$note = Note::where('user_id',$request->user_id)->where('');
    	$note->highlights = $request->highlights;
	    $note->notes = $request->notes;
	    $note->save();
    }
}
