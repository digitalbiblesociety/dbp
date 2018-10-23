<?php

namespace Tests\Feature;

class v4_userRoutesTest extends API_V4_Test
{


	public function test_v4_access_groups()
	{
		/**@category V4_API
		 * @category Route Name: v4_access_groups.index
		 * @category Route Path: https://api.dbp.test/access/groups?v=4&key=1234
		 * @see      \App\Http\Controllers\User\AccessGroupController::index
		 */
		$path = route('v4_access_groups.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_access_groups.store
		 * @category Route Path: https://api.dbp.test/access/groups/?v=4&key=1234
		 * @see      \App\Http\Controllers\User\AccessGroupController::store
		 */
		$path = route('v4_access_groups.store', $this->params);
		echo "\nTesting The creation of a new Access Group at: $path";
		$response = $this->withHeaders($this->params)->post($path, [
			'name'        => 'TEST_CREATED_BY_TEST',
			'description' => 'A test Group Created Automatically',
		]);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_access_groups.show
		 * @category Route Path: https://api.dbp.test/access/groups/TEST_CREATED_BY_TEST?v=4&key=1234&pretty
		 * @see      \App\Http\Controllers\User\AccessGroupController::show
		 */
		$additional_params = ['id' => 'TEST_CREATED_BY_TEST'];
		$path              = route('v4_access_groups.show', array_merge($additional_params, $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_access_groups.update
		 * @category Route Path: https://api.dbp.test/access/groups/TEST_CREATED_BY_TEST?v=4&key=1234
		 * @see      \App\Http\Controllers\User\AccessGroupController::update
		 */
		$additional_params = ['id' => 'TEST_CREATED_BY_TEST'];
		$path              = route('v4_access_groups.update', array_merge($additional_params, $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path, ['description' => 'Shortened']);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_access_groups.destroy
		 * @category Route Path: https://api.dbp.test/access/groups/{group_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\AccessGroupController::destroy
		 */
		$additional_params = ['id' => 'TEST_CREATED_BY_TEST'];
		$path              = route('v4_access_groups.destroy', array_merge($additional_params, $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->delete($path);
		$response->assertSuccessful();
	}

	public function test_v4_articles()
	{
		/**@category V4_API
		 * @category Route Name: v4_articles.index
		 * @category Route Path: https://api.dbp.test/articles?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::index
		 */
		$path = route('v4_articles.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_articles.store
		 * @category Route Path: https://api.dbp.test/articles?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::store
		 */
		$path    = route('v4_articles.store', $this->params);
		$article = [
			'cover'           => 'www.example.com/url/to/image.jpg',
			'cover_thumbnail' => 'www.example.com/url/to/image_thumbnail.jpg',
			'tags'            => [['iso' => 'eng', 'name' => 'Test Tag 1']],
			'translations'    => [
				['iso' => 'eng', 'name' => 'Test Title 1', 'body' => 'This is the body of the article'],
				['iso' => 'spa', 'name' => 'El Testo Articleo', 'body' => 'Soy el Conteno de Article'],
			],
		];
		echo "\nPosting to: $path";
		$response = $this->withHeaders($this->params)->post($path, $article);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_articles.show
		 * @category Route Path: https://api.dbp.test/articles/{article_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::show
		 */

		$path = route('v4_articles.show', array_merge(['name' => 'test-title-1'], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_articles.update
		 * @category Route Path: https://api.dbp.test/articles/{article_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::update
		 */
		$path = route('v4_articles.update', array_merge(['name' => 'test-title-1'], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->put($path,
			['translations' => ['iso' => 'eng', 'body' => 'Updated Body']]);
		$response->assertSuccessful();

		/**@category V4_API
		 * @category Route Name: v4_articles.destroy
		 * @category Route Path: https://api.dbp.test/articles/{article_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\ArticlesController::destroy
		 */
		$path = route('v4_articles.destroy', array_merge(['name' => 'test-title-1'], $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	public function test_v4_resources()
	{
		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.index
		 * @category Route Path: https://api.dbp.test/resources?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::index
		 */
		$path = route('v4_resources.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.show
		 * @category Route Path: https://api.dbp.test/resources/{resource_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::show
		 */
		$path = route('v4_resources.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();


		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.update
		 * @category Route Path: https://api.dbp.test/resources/{resource_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::update
		 */
		$path = route('v4_resources.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();


		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.store
		 * @category Route Path: https://api.dbp.test/resources?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::store
		 */
		$path = route('v4_resources.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_resources.destroy
		 * @category Route Path: https://api.dbp.test/resources/{resource_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\Organization\ResourcesController::destroy
		 */
		$path = route('v4_resources.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}


	public function test_v4_users()
	{
		/**
		 * @category V4_API
		 * @category Route Name: v4_user.index
		 * @category Route Path: https://api.dbp.test/users?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::index
		 */
		$path = route('v4_user.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user.store
		 * @category Route Path: https://api.dbp.test/users?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::store
		 */
		$path = route('v4_user.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user.show
		 * @category Route Path: https://api.dbp.test/users/{user_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::show
		 */
		$path = route('v4_user.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user.update
		 * @category Route Path: https://api.dbp.test/users/{user_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::update
		 */
		$path = route('v4_user.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();

		/**
		 * @category V4_API
		 * @category Route Name: v4_user.destroy
		 * @category Route Path: https://api.dbp.test/users/{user_id}?v=4&key=1234
		 * @see      \App\Http\Controllers\User\UsersController::destroy
		 */
		$path = route('v4_user.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.login
	 * @category Route Path: https://api.dbp.test/users/login?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController::login
	 */
	public function test_v4_user_login()
	{
		$path = route('v4_user.login', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.geolocate
	 * @category Route Path: https://api.dbp.test/users/geolocate?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController::geoLocate
	 */
	public function test_v4_user_geolocate()
	{
		$path = route('v4_user.geolocate', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.oAuth
	 * @category Route Path: https://api.dbp.test/users/login/{driver}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController::getSocialRedirect
	 */
	public function test_v4_user_oAuth()
	{
		$path = route('v4_user.oAuth', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.oAuthCallback
	 * @category Route Path: https://api.dbp.test/users/login/{driver}/callback?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController::getSocialHandle
	 */
	public function test_v4_user_oAuthCallback()
	{
		$path = route('v4_user.oAuthCallback', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.password_reset
	 * @category Route Path: https://api.dbp.test/users/password/reset?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserPasswordsController::validatePasswordReset
	 */
	public function test_v4_user_password_reset()
	{
		$path = route('v4_user.password_reset', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user.password_email
	 * @category Route Path: https://api.dbp.test/users/password/email?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserPasswordsController::triggerPasswordResetEmail
	 */
	public function test_v4_user_password_email()
	{
		$path = route('v4_user.password_email', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.index
	 * @category Route Path: https://api.dbp.test//accounts?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController::index
	 */
	public function test_v4_user_accounts_index()
	{
		$path = route('v4_user_accounts.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.show
	 * @category Route Path: https://api.dbp.test//accounts/{account_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController::show
	 */
	public function test_v4_user_accounts_show()
	{
		$path = route('v4_user_accounts.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.store
	 * @category Route Path: https://api.dbp.test//accounts?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController::store
	 */
	public function test_v4_user_accounts_store()
	{
		$path = route('v4_user_accounts.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.update
	 * @category Route Path: https://api.dbp.test//accounts/{account_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController::update
	 */
	public function test_v4_user_accounts_update()
	{
		$path = route('v4_user_accounts.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.destroy
	 * @category Route Path: https://api.dbp.test//accounts/{account_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController::destroy
	 */
	public function test_v4_user_accounts_destroy()
	{
		$path = route('v4_user_accounts.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_notes.index
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController::index
	 */
	public function test_v4_notes_index()
	{
		$path = route('v4_notes.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_notes.show
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes/{note_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController::show
	 */
	public function test_v4_notes_show()
	{
		$path = route('v4_notes.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_notes.store
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController::store
	 */
	public function test_v4_notes_store()
	{
		$path = route('v4_notes.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_notes.update
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes/{note_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController::update
	 */
	public function test_v4_notes_update()
	{
		$path = route('v4_notes.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_notes.destroy
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes/{note_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController::destroy
	 */
	public function test_v4_notes_destroy()
	{
		$path = route('v4_notes.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_messages.index
	 * @category Route Path: https://api.dbp.test/users/messages?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserContactController::index
	 */
	public function test_v4_messages_index()
	{
		$path = route('v4_messages.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_messages.show
	 * @category Route Path: https://api.dbp.test/users/messages/{note_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserContactController::show
	 */
	public function test_v4_messages_show()
	{
		$path = route('v4_messages.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bookmarks.index
	 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserBookmarksController::index
	 */
	public function test_v4_bookmarks_index()
	{
		$path = route('v4_bookmarks.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bookmarks.store
	 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserBookmarksController::store
	 */
	public function test_v4_bookmarks_store()
	{
		$path = route('v4_bookmarks.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bookmarks.update
	 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks/{bookmark_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserBookmarksController::update
	 */
	public function test_v4_bookmarks_update()
	{
		$path = route('v4_bookmarks.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bookmarks.destroy
	 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks/{bookmark_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserBookmarksController::destroy
	 */
	public function test_v4_bookmarks_destroy()
	{
		$path = route('v4_bookmarks.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_highlights.index
	 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserHighlightsController::index
	 */
	public function test_v4_highlights_index()
	{
		$path = route('v4_highlights.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_highlights.store
	 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserHighlightsController::store
	 */
	public function test_v4_highlights_store()
	{
		$path = route('v4_highlights.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_highlights.update
	 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights/{highlight_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserHighlightsController::update
	 */
	public function test_v4_highlights_update()
	{
		$path = route('v4_highlights.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_highlights.destroy
	 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights/{highlight_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserHighlightsController::destroy
	 */
	public function test_v4_highlights_destroy()
	{
		$path = route('v4_highlights.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}


}