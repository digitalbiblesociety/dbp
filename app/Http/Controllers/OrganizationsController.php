<?php

namespace App\Http\Controllers;


use App\Models\Organization\Organization;
use App\Transformers\OrganizationTransformer;
use Illuminate\View\View;
use App\Transformers\BibleTransformer;

class OrganizationsController extends APIController {

	/**
	 * Display a listing of the organizations.
	 *
	 * @return mixed
	 */
	public function index() {
		if(!$this->api) {
			// If User is authorized pass them on to the Dashboard
			$user = \Auth::user();
			return view('dashboard.organizations.index', compact('user'));
		}

		$iso        = checkParam('iso', null, 'optional') ?? "eng";
		$membership = checkParam('membership', null, 'optional');
		$bibles     = checkParam('bibles', null, 'optional');

		$organizations = \Cache::remember($this->v . 'organizations' . $iso . $membership . $bibles, 2400, function() use ($iso, $membership, $bibles) {
			if($membership) {
				$membership = Organization::where('slug', $membership)->first();
				if(!$membership) return $this->setStatusCode(404)->replyWithError("No membership connection found.");
				$membership = $membership->id;
			}

			// Otherwise Fetch API route
			$organizations = Organization::with('translations', 'logos')
				->when($membership, function($q) use ($membership) {
				    $q->join('organization_relationships', function($join) use ($membership) {
				        $join->on('organizations.id', '=', 'organization_relationships.organization_child_id')
				             ->where('organization_relationships.organization_parent_id', $membership);
				    });
				})->when($bibles, function($q) {
					$q->has('bibles');
				})->has('translations')->get();
			if(isset($_GET['count']) or (\Route::currentRouteName() == 'v2_volume_organization_list')) $organizations->load('bibles');
		});

		return $this->reply(fractal()->collection($organizations)->serializeWith($this->serializer)->transformWith(new OrganizationTransformer())->ToArray());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string $slug
	 *
	 * @return mixed
	 */
	public function show($slug) {
		$organization = Organization::with("bibles.translations", "bibles.language", "translations", "logos", "currentTranslation")->where('id', $slug)->orWhere('slug', $slug)->first();
		if(!$organization) return $this->setStatusCode(404)->replyWithError("Sorry we don't have any record for $slug");

		// Handle API First
		if($this->api) return $this->reply(fractal()->item($organization)->serializeWith($this->serializer)->transformWith(new OrganizationTransformer()));

		// Than Try Admin
		$user = \Auth::user();
		if($user) {
			$organization->load('filesets.bible.currentTranslation');
			return view('dashboard.organizations.show', compact('user', 'organization'));
		}

		// Finally send them to the public view
		return view('community.organizations.show', compact('organization'));
	}

	public function bibles(string $slug) {
		$organization = Organization::with('bibles')->where('slug', $slug)->first();

		return $this->reply(fractal()->collection($organization->bibles)->transformWith(new BibleTransformer())->toArray());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return mixed
	 */
	public function create() {
		$user = \Auth::user();
		if(!$user->archivist) return $this->replyWithError('Not an Archivist');
		return view('community.organizations.create');
	}

	public function apply() {
		$user = \Auth::user();
		if(!$user) return $this->replyWithError("You must be logged in");
		$organizations = Organization::with('translations')->get();

		return view('dashboard.organizations.roles.create', compact('user', 'organizations'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return mixed
	 */
	public function store() {
		$organization = new Organization();
		$organization->save(request()->except(['translations']));
		foreach(request()->translations as $translation) $organization->translations()->create(['iso' => $translation['iso'],'name' => $translation['translation'],'description' => '']);

		return view('community.organizations.show', compact('organization'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string $slug
	 *
	 * @return View
	 */
	public function edit($slug) {
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
	public function update($slug) {
		$organization = Organization::where('slug', $slug)->first();
		$organization->update(request()->except(['translations']));
		$organization->translations()->delete();
		foreach(request()->translations as $translation) {
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
	public function destroy($id) {
		$organization = Organization::find($id);
		$organization->delete();

		if($this->api) return $this->reply("Organization successfully deleted");
		$organizations = Organization::with("currentTranslation")->get();
		return view('community.organizations.index', compact('organizations'));
	}

}
