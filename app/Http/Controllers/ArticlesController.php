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
	 * Returns Articles
	 *
	 * @version 4
	 * @category v4_articles.index
	 * @link http://bible.build/articles - V4 Access
	 * @link https://api.dbp.dev/articles?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_articles_all - V4 Test Docs
	 *
	 * @return mixed $articles string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
    public function index()
    {
		if(!$this->api) return view('articles.index');
		$articles = Article::with('translations')->get();
		return $this->reply($articles);
    }

	/**
	 * Create an Article UI Form
	 *
	 * @version 4
	 * @category view_alphabets.create
	 * @link http://bible.build/articles/create - V4 Access
	 * @link https://api.dbp.dev/articles/create - V4 Test Access
	 *
	 * @return View - the Article Creation Form
	 *
	 */
    public function create()
    {
        return view('articles.create');
    }

	/**
	 * Store an Article in the database
	 *
	 * @version 4
	 * @category view_alphabets.store
	 * @link http://bible.build/articles/create - V4 Access
	 * @link https://api.dbp.dev/articles/create - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_articles_all - V4 Test Docs
	 *
	 * @param Request $request - Store
	 *
	 * @return mixed $articles string - A JSON string that contains the status code and error messages if applicable.
	 *
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
	 * Returns a single Article
	 *
	 * @version 4
	 * @category v4_articles.show
	 * @link http://bible.build/articles - V4 Access
	 * @link https://api.dbp.dev/articles?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_articles_all - V4 Test Docs
	 *
	 * @param string $id
	 *
	 * @return mixed $articles string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
    public function show($id)
    {
	    if(!$this->api) return view('articles.show');
	    return $this->reply(Article::find($id));
    }

	/**
	 * Edit Article UI Form
	 *
	 * @version 4
	 * @category view_alphabets.edit
	 * @link http://bible.build/articles/create - V4 Access
	 * @link https://api.dbp.dev/articles/create - V4 Test Access
	 *
	 * @param string $id - the Article slug
	 *
	 * @return View - the Article Edit Form
	 *
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
