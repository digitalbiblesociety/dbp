<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Resource\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;
use \Illuminate\Http\Response;

use App\Transformers\ResourcesTransformer;

class ResourcesController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $iso             = checkParam('iso');
        $limit           = checkParam('limit') ?? 2000;
        $organization    = checkParam('organization_id');
        $dialects        = checkParam('include_dialects');

        $resources = Resource::with('translations', 'links', 'organization.translations', 'language')
            ->when($iso, function ($query) use ($iso, $dialects) {
                $query->whereHas('language,', function($subquery) use($iso,$dialects) {
                    if(!$dialects) {
                        $subquery->where('iso', $iso);
                    } else {
                        $language = Language::with('dialects')->where('iso',$iso)->select('id')->get();
                        $subquery->whereIn('id',$language->pluck('id'));
                    }
                });
            })
            ->when($organization, function ($query) use ($organization) {
                $query->where('id', $organization)->orWhere('slug', $organization);
            })->take($limit)->get();

        return $this->reply(fractal($resources, new ResourcesTransformer(), $this->serializer));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return View
     */
    public function show($id)
    {
        $resource = Resource::with('translations', 'links', 'organization.translations')->find($id);
        return $this->reply(fractal($resource, new ResourcesTransformer()));
    }

    private function invalidResource(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'unicode_pdf'         => 'url|nullable',
            'slug'                => 'required|unique:dbp.resources,slug|string|maxLength:191|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'language_id'         => 'required|exists:dbp.languages,id',
            'organization_id'     => 'required|exists:dbp.organizations,id',
            'source_id'           => 'string|maxLength:191',
            'cover'               => 'string|maxLength:191',
            'cover_thumbnail'     => 'string|maxLength:191',
            'date'                => 'date',
            'type'                => 'string',
            'translations.*.name' => 'required|unique:dbp.resource_translations,title|maxLength:191',
            'translations.*.tag'  => 'boolean',
            'links.*.url'         => 'required|url',
            'links.*.title'       => 'string|maxLength:191'
        ]);

        if ($validator->fails()) {
            if (!$this->api) {
                return redirect('dashboard/resources/create')->withErrors($validator)->withInput();
            }
            return $this->setStatusCode(422)->replyWithError($validator->errors());
        }

        return null;
    }

    /**
     *
     * dam_id: DAM ID for the Jesus Film volume desired.
     * encoding: [mp4|m3u8] The video encoding format desired.
     * book_id (optional): OSIS book code to filter segments by references to book desired.
     * chapter_id (optional): Chapter id to filter segments by references based on book and chapter.
     * verse_id (optional): Verse id to filter segments by references based on book, chapter and verse.
     *
     */
    public function jesusFilmListing()
    {

        $id         = checkParam('dam_id');
        $encoding   = checkParam('encoding');
        $book_id    = checkParam('book_id');
        $chapter_id = checkParam('chapter_id');
        $verse_id   = checkParam('verse_id');

        $organization = Organization::where('slug', 'the-jesus-film-project')->first();
        $iso          = strtolower(substr($id, 0, 3));
        $language = false;
        if ($iso !== null) {
            $language = Language::where('iso', $iso)->first();
            if (!$language) {
                return $this->setStatusCode(404)->replyWithError("Language not found for provided iso");
            }
        }

        $jesusFilm = Resource::with('translations')
            ->when($language, function ($query) use ($language) {
                $query->where('language_id', $language->id);
            })->where('organization_id', $organization->id)->first();

        return $jesusFilm;
    }
}
