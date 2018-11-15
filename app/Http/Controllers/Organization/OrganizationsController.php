<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleLink;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Transformers\OrganizationTransformer;
use App\Transformers\BibleTransformer;

class OrganizationsController extends APIController
{

	/**
	 * Display a listing of the organizations.
	 *
	 * @return mixed
	 */
	public function index()
	{
		if (!$this->api) {
			// If User is authorized pass them on to the Dashboard
			$user = \Auth::user();

			return view('dashboard.organizations.index', compact('user'));
		}

		$i10n        = checkParam('iso', null, 'optional') ?? 'eng';
		$i10n_language     = Language::where('iso',$i10n)->first();
		if(!$i10n_language) return $this->setStatusCode(404)->replyWithError(trans('api.i10n_errors_404', ['id' => $i10n]));
		$membership  = checkParam('membership', null, 'optional');
		$content     = checkParam('has_content', null, 'optional');
		$bibles      = checkParam('bibles', null, 'optional');
		$resources   = checkParam('resources', null, 'optional');

		$cache_string = $this->v . 'organizations' . $i10n . $membership . $content . $bibles .$resources;

		$organizations = \Cache::remember($cache_string, 2400,
			function () use ($i10n_language, $membership, $content, $bibles, $resources) {
				if ($membership) {
					$membership = Organization::where('slug', $membership)->first();
					if (!$membership) {
						return $this->setStatusCode(404)->replyWithError(trans('api.organizations_relationship_members_404'));
					}
					$membership = $membership->id;
				}

				// Otherwise Fetch API route
				$organizations = Organization::with(['translations',
					'logos' => function($query) use ($i10n_language) {
						$query->where('language_id', $i10n_language->id);
					}])
					->when(
                        $membership, function ($q) use ($membership) {
					    $q->join('organization_relationships',
					        function ($join) use ($membership) {
					            $join->on('organizations.id', '=', 'organization_relationships.organization_child_id')
					                 ->where('organization_relationships.organization_parent_id', $membership);
					        });
					})->when($bibles, function ($q) {
						$q->has('bibles')->orHas('links');
					})->when($resources, function($q) {
						$q->has('resources');
					})->when($content, function($q) {
						$q->has('bibles')->orHas('links')->orHas('resources');
					})->has('translations')->get();
				if (isset($_GET['count']) || (\Route::currentRouteName() === 'v2_volume_organization_list')) {
					$organizations->load('bibles');
				}

				return fractal($organizations,new OrganizationTransformer(),$this->serializer);
			});

		return $this->reply($organizations);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string $slug
	 *
	 * @return mixed
	 */
	public function show($slug)
	{
		$i10n           = checkParam('iso', null, 'optional') ?? 'eng';
		$i10n_language  = Language::where('iso',$i10n)->first();
		if(!$i10n_language) return $this->setStatusCode(404)->replyWithError(trans('api.i10n_errors_404', ['id' => $i10n]));
		$searchedColumn = is_numeric($slug) ? 'id' : 'slug';

		$organization = Organization::with(['bibles.translations','bibles.language','memberships.child_organization.bibles.translations','memberships.child_organization.bibles.links','links','translations','currentTranslation','resources',
		'logos' => function($query) use ($i10n_language) {
			$query->where('language_id', $i10n_language->id);
		}])->where($searchedColumn, $slug)->first();

		if(!$organization) return $this->setStatusCode(404)->replyWithError(trans('api.organizations_errors_404', ['id' => $slug]));

		// Handle API First
		if ($this->api) return $this->reply(fractal()->item($organization)->serializeWith($this->serializer)->transformWith(new OrganizationTransformer()));

		// Than Try Admin
		$user = \Auth::user();
		if ($user) {
			$organization->load('filesets.bible.currentTranslation');
			return view('dashboard.organizations.show', compact('user', 'organization'));
		}

		// Finally send them to the public view
		return view('community.organizations.show', compact('organization'));
	}

    /**
     * @param string $slug
     * @return mixed
     */
    public function bibles(string $slug)
	{
		$organization = Organization::with('bibles')->where('slug', $slug)->first();

		return $this->reply(fractal()->collection($organization->bibles)->transformWith(new BibleTransformer())->toArray());
	}

	public function compare($source_organization,$destination_organization)
	{
		$source_organization = Organization::with('bibles')->where('slug',$source_organization)->first();
		if(!$source_organization) return $this->setStatusCode(404)->replyWithError('source_organization not found');
		$source_bibles = $source_organization->bibles->pluck('id');
		$source_links = BibleLink::where('organization_id',$source_organization->id)->get()->pluck('bible_id');
		$source = $source_bibles->merge($source_links);

		$destination_organization = Organization::with('bibles')->where('slug',$destination_organization)->first();
		if(!$destination_organization) return $this->setStatusCode(404)->replyWithError('destination_organization not found');
		$destination_bibles = $destination_organization->bibles->pluck('id');
		$destination_links = BibleLink::where('organization_id',$destination_organization->id)->get()->pluck('bible_id');
		$destination = $destination_bibles->merge($destination_links);

		return $this->reply(array_flatten(array_sort($destination->diff($source)->unique())));

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return mixed
	 */
	public function create()
	{
		$user = \Auth::user() ?? $this->user;
		if(!$user->archivist) return $this->setStatusCode(401)->replyWithError(trans('api.wiki_authorization_failed'));
		return view('community.organizations.create');
	}

	public function apply()
	{
		$user = \Auth::user() ?? $this->user;
		$organizations = Organization::with('translations')->get();

		return view('dashboard.organizations.roles.create', compact('user', 'organizations'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return mixed
	 */
	public function store()
	{
		$organization = new Organization();
		$organization->save(request()->except(['translations']));
		foreach (request()->translations as $translation) {
			$organization->translations()->create(['iso'         => $translation['iso'],
			                                       'name'        => $translation['translation'],
			                                       'description' => '',
			]);
		}

		return view('community.organizations.show', compact('organization'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string $slug
	 *
	 * @return View
	 */
	public function edit($slug)
	{
		$organization = Organization::where('slug', $slug)->first();

		return view('community.organizations.edit', compact('organization'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  string $slug
	 *
	 * @return View
	 */
	public function update($slug)
	{
		$organization = Organization::where('slug', $slug)->first();
		if(!$organization) return $this->setStatusCode(404)->replyWithError(trans('api.organizations_errors_404'));
		$organization->update(request()->except(['translations']));
		$organization->translations()->delete();
		foreach (request()->translations as $translation) {
			$organization->translations()->create(['iso' => $translation['iso'], 'name' => $translation['translation'], 'description' => '']);
		}

		return view('community.organizations.show', compact('organization'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return View
	 */
	public function destroy($id)
	{
		$organization = Organization::find($id);
		$organization->delete();

		if($this->api) return $this->setStatusCode(200)->reply('Organization successfully deleted');

		$organizations = Organization::with('currentTranslation')->get();
		return view('community.organizations.index', compact('organizations'));
	}

}
