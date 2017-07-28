<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use database\seeds\SeederHelper;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Organization\Organization;
use App\Transformers\OrganizationTransformer;
use Illuminate\Support\Facades\Cache;
/**
 * Class OrganizationsController
 * @package App\Http\Controllers
 */
class OrganizationsController extends APIController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$organizations = Organization::with('translations')->get();

		return $this->reply(fractal()->collection($organizations)->transformWith(new OrganizationTransformer())->ToArray());
		return view('community.organizations.index', compact('organizations'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string $slug
	 * @return Response
	 */
	public function show($slug)
	{
		// Support both incrementing ID and Slug
		if(preg_match("/\d+/",$slug)) {
			$organization = Organization::with("bibles.translations","bibles.language","translations")->where('id',$slug)->first();
		} else {
			$organization = Organization::with("bibles.translations","bibles.language","translations")->where('slug',$slug)->first();
		}

		if(!$organization) return $this->setStatusCode(404)->replyWithError('sorry we don\'t have any record for '.$slug);
		if($this->api) return $this->reply($organization);

		return view('community.organizations.show', compact('organization'));
	}

	public function bibles(string $slug, Bible $bible)
	{
		$organization = Organization::with('bibles')->where('slug',$slug)->first();
		return $this->reply(fractal()->collection($organization->bibles)->transformWith(new BiblesTransformer())->toArray());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  class $organization
	 * @return Response
	 */
	public function create()
	{
		return view('community.organizations.create');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  class $organization
	 * @return Response
	 */
	public function store(Request $request)
	{
		$organization = new Organization();
		$organization->save($request->except(['translations']));
		foreach($request->translations as $translation) {
			$organization->translations()->create(['iso' => $translation['iso'], 'name' => $translation['translation'], 'description' => '']);
		}
		return view('community.organizations.show', compact('organization'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string $slug
	 * @return Response
	 */
	public function edit($slug)
	{
		$organization = Organization::where('slug',$slug)->first();
		return view('community.organizations.edit', compact('organization'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request  $request
	 * @param  string  $slug
	 * @return Response
	 */
	public function update(Request $request, $slug)
	{
		$organization = Organization::where('slug',$slug)->first();
		$organization->update($request->except(['translations']));
		$organization->translations()->delete();
		foreach($request->translations as $translation) {
			$organization->translations()->create(['iso' => $translation['iso'], 'name' => $translation['translation'], 'description' => '']);
		}
		return view('community.organizations.show', compact('organization'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$organization = Organization::find($id);
		$organization->delete();

		if($this->api) return $this->reply("Organization successfully deleted");

		$organizations = Organization::with("currentTranslation")->get();
		return view('community.organizations.index', compact('organizations'));
	}
}
