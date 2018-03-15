<?php

namespace App\Http\Controllers;

use App\Models\User\Highlight;
use Illuminate\Http\Request;
use Validator;
use App\Transformers\UserHighlightsTransformer;

class UserHighlightsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
	    $bible_id = checkParam('bible_id', null, 'optional');
	    $book_id = checkParam('book_id', null, 'optional');
	    $chapter_id = checkParam('chapter', null, 'optional');
	    $project_id = checkParam('project_id');

	    $highlights = Highlight::select(['id','bible_id', 'book_id', 'chapter', 'verse_start', 'highlight_start', 'highlighted_words'])
			->where('user_id',$user_id)->where('project_id',$project_id)
	        ->when($bible_id, function($q) use ($bible_id) {
		        $q->where('bible_id', '=', $bible_id);
	        })->when($book_id, function($q) use ($book_id) {
			    $q->where('book_id', '=', $book_id);
		    })->when($chapter_id, function($q) use($chapter_id) {
			    $q->where('chapter', $chapter_id);
		    })->orderBy('updated_at')->get();

	    if(!$highlights) return $this->setStatusCode(404)->replyWithError("No User found for the specified ID");
	    return $this->reply(fractal()->collection($highlights)->transformWith(UserHighlightsTransformer::class));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.highlights.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
			'bible_id'          => 'required|exists:bibles,id',
			'user_id'           => 'required|exists:users,id',
			'book_id'           => 'required|exists:books,id',
			'project_id'        => 'required|exists:projects,id',
			'chapter'           => 'required|max:150|min:1|integer',
			'verse_start'       => 'required|max:177|min:1|integer',
			'highlight_start'   => 'required|min:1|integer',
			'highlighted_words' => 'required|min:1|integer',
			'highlighted_color' => 'max:3|min:3',
		]);
		if ($validator->fails()) return ['errors' => $validator->errors() ];
		Highlight::create([
			'user_id'           => $request->user_id,
			'bible_id'          => $request->bible_id,
			'book_id'           => $request->book_id,
			'chapter'           => $request->chapter,
			'project_id'        => $request->project_id,
			'verse_start'       => $request->verse_start,
			'highlight_start'   => $request->highlight_start,
			'highlighted_words' => $request->highlighted_words,
			'highlighted_color' => $request->highlighted_color ?? "EE0",
		]);
		return $this->reply(["success" => "Highlight created"]);
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $project_id = checkParam('project_id');
        $highlight = Highlight::where('project_id',$project_id)->where('id',$id)->first();
	    if(!$highlight) return $this->setStatusCode(404)->replyWithError("No Note found for the specified ID");
        return $highlight;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id,$id)
    {
	    $highlight = Highlight::where('user_id',$user_id)->where('project_id', $request->project_id)->where('id',$id)->first();
	    if(!$highlight) return $this->setStatusCode(404)->replyWithError('Sorry The Highlight was not found');

	    $highlight->fill($request->all())->save();
	    return $this->reply(["success" => "Highlight Updated"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $project_id = checkParam('project_id');
	    $highlight = Highlight::where('project_id',$project_id)->where('id',$id)->first();
	    if(!$highlight) return $this->setStatusCode(404)->replyWithError("Highlight not found");
	    $highlight->delete();
	    return $this->reply(["success" => "Highlight Deleted"]);
    }
}
