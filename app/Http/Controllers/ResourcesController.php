<?php

namespace App\Http\Controllers;

use App\Models\Resource\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Transformers\ResourcesTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\DataArraySerializer;

class ResourcesController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->api) return view('resources.index');
        $iso = checkParam('iso', null, 'optional');
        $limit = checkParam('limit', null, 'optional') ?? 25;
        $organization_id = checkParam('organization_id', null, 'optional');

        $pagination = Resource::with('translations','links','organization.translations')
		->when($iso, function($q) use ($iso) {
	        $q->where('iso', $iso);
        })->when($organization_id, function($q) use ($organization_id) {
		    $q->where('organization_id', $organization_id);
	    })->paginate($limit);
	    $resources = $pagination->getCollection();

	    return $this->reply(
	    	fractal()->collection($resources)
				->transformWith(new ResourcesTransformer())
				->serializeWith(new DataArraySerializer())
				->paginateWith(new IlluminatePaginatorAdapter($pagination))
	    );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('resources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return View
     */
    public function store(Request $request)
    {
        return view('resources.store');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
	    if(!$this->api) return view('resources.show');
	    $resource = Resource::with('translations','links','organization.translations')->find($id);
	    return $this->reply($resource);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('resources.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return View
     */
    public function update(Request $request, $id)
    {
        return view('resources.update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return View
     */
    public function destroy($id)
    {
        return view('resources.destroy');
    }
}
