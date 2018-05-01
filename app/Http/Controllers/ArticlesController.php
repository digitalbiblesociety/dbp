<?php

namespace App\Http\Controllers;

use App\Models\User\Article;
use Illuminate\Http\Request;

use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User\Key;
class ArticlesController extends APIController
{
    /**
     * Display a listing of the Articles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if(!$this->api) return view('articles.index');
		$articles = Article::with('translations')->get();
		return $this->reply($articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $user = ($this->api) ? $this->validateUser() : $this->validateUser(\Auth::user());
	    $this->validateArticle($request);

	    $article = new Article();
	    $article->cover = $request->cover;
	    $article->iso = $request->iso ?? "eng";
	    $article->cover_thumbnail = $request->cover_thumbnail;
	    $article->user_id = $user->id ?? "fnrS1pTKktHxwsXJ";
	    $article->organization_id = $request->organization_id ?? 1;
	    $article->save();

	    // $article->translations()->createMany(["name" => $request->name,"description" => $request->description]);

	    if(!$this->api) return redirect()->route('view_articles.show', ['id' => request()->id]);
	    return $this->reply(["message" => "Article Successfully Created"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    return view('articles.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    return view('articles.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	    return view('articles.show');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    return view('articles.index');
    }

	/**
	 * Ensure the current alphabet change is valid
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	private function validateArticle(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'iso'             => 'required|exists:languages,iso',
			'organization_id' => 'required|exists:organizations,id',
			'user_id'         => 'required|exists:users,id',
			'cover'           => 'required',
			'cover_thumbnail' => 'required'
		]);

		if ($validator->fails()) {
			if($this->api)  return $this->setStatusCode(422)->replyWithError($validator->errors());
			if(!$this->api) return redirect('articles/create')->withErrors($validator)->withInput();
		}

	}

	/**
	 * Ensure the current User has permissions to alter the alphabets
	 *
	 * @param null $user
	 *
	 * @return \App\Models\User\User|mixed|null
	 */
	private function validateUser()
	{
		$user = Auth::user();
		if(!$user) {
			$key = Key::where('key',$this->key)->first();
			if(!isset($key)) return $this->setStatusCode(403)->replyWithError('No Authentication Provided or invalid Key');
			$user = $key->user;
		}
		if(!$user->archivist AND !$user->admin) return $this->setStatusCode(401)->replyWithError("You don't have permission to edit the articles");
		return $user;
	}

}
