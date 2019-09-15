<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\Article;
use App\Models\User\User;
use Illuminate\Http\Request;

use Validator;
use App\Models\User\Key;
use View;

class ArticlesController extends APIController
{
    /**
     * Returns Articles
     *
     * @version 4
     * @category v4_articles.index
     * @link http://bible.build/articles - V4 Access
     * @link https://api.dbp.test/articles?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_articles_all - V4 Test Docs
     *
     * @return mixed $articles string - A JSON string that contains the status code and error messages if applicable.
     *
     */
    public function index()
    {
        if (!$this->api) {
            return view('community.articles.index');
        }
        $articles = Article::with('translations')->get();

        return $this->reply($articles);
    }

    /**
     * Create an Article UI Form
     *
     * @version 4
     * @category view_alphabets.create
     * @link http://bible.build/articles/create - V4 Access
     * @link https://api.dbp.test/articles/create - V4 Test Access
     *
     * @return View - the Article Creation Form
     *
     */
    public function create()
    {
        return view('community.articles.create');
    }

    /**
     * Store an Article in the database
     *
     * @version 4
     * @category view_alphabets.store
     * @link http://bible.build/articles/create - V4 Access
     * @link https://api.dbp.test/articles/create - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_articles_all - V4 Test Docs
     *
     * @param Request $request - Store
     *
     * @return mixed $articles string - A JSON string that contains the status code and error messages if applicable.
     *
     */
    public function store(Request $request)
    {
        $invalidUser = $this->invalidUser();
        if ($invalidUser) {
            return $invalidUser;
        }

        $invalidArticle = $this->invalidArticle($request);
        if ($invalidArticle) {
            return $invalidArticle;
        }

        $article                  = new Article();
        $article->cover           = $request->cover;
        $article->cover_thumbnail = $request->cover_thumbnail;
        $article->user_id         = $this->user->id;
        $article->save();

        $article->translations()->createMany($request->translations);
        $article->tags()->createMany($request->tags);

        if (!$this->api) {
            return redirect()->route('view_articles.show', ['id' => request()->id]);
        }
        return $this->reply($article);
    }

    /**
     * Returns a single Article
     *
     * @version 4
     * @category v4_articles.show
     * @link http://bible.build/articles - V4 Access
     * @link https://api.dbp.test/articles?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_articles_all - V4 Test Docs
     *
     * @param string $id
     *
     * @return mixed $articles string - A JSON string that contains the status code and error messages if applicable.
     *
     */
    public function show($id)
    {
        if (!$this->api) {
            return view('community.articles.show');
        }
        $article = Article::where('id', $id)->orWhere('name', $id)->first();

        return $this->reply($article);
    }

    /**
     * Edit Article UI Form
     *
     * @version 4
     * @category view_alphabets.edit
     * @link http://bible.build/articles/create - V4 Access
     * @link https://api.dbp.test/articles/create - V4 Test Access
     *
     * @param string $id - the Article slug
     *
     * @return View - the Article Edit Form
     *
     */
    public function edit($id)
    {
        return view('community.articles.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invalidUser = $this->invalidUser();
        if ($invalidUser) {
            return $invalidUser;
        }

        $invalidArticle = $this->invalidArticle($request);
        if ($invalidArticle) {
            return $invalidArticle;
        }

        return view('community.articles.show', compact('request', 'id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->validateUser();
        $article = Article::find($id);
        if (!$article) {
            return $this->setStatusCode(404)->replyWithError(trans('api.articles_show_404', ['id' => $id]));
        }
        $article->delete();
        return view('community.articles.index');
    }

    /**
     * Ensure the current alphabet change is valid
     *
     * @param Request $request
     *
     * @return mixed
     */
    private function invalidArticle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iso'                  => 'required|exists:dbp.languages,iso',
            'user_id'              => 'required|exists:dbp_users.users,id',
            'translations.*.title' => 'required',
            'translations.*.body'  => 'required'
        ]);

        if ($validator->fails()) {
            if ($this->api) {
                return $this->setStatusCode(422)->replyWithError($validator->errors());
            }
            if (!$this->api) {
                return redirect('articles/create')->withErrors($validator)->withInput();
            }
        }
        return null;
    }

    /**
     * Ensure the current User has permissions to alter the alphabets
     *
     *
     * @return \App\Models\User\User|mixed|null
     */
    private function invalidUser()
    {
        $user = $this->user;
        $is_archivist = $user->roles->whereIn('slug', 'archivist')->first();
        $is_admin = $user->roles->where('slug', 'admin')->first();
        if (!$is_archivist && !$is_admin) {
            return $this->setStatusCode(401)->replyWithError(trans('api.articles_edit_permission_failed'));
        }
        return null;
    }
}
