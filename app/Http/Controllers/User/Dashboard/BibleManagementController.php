<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Models\Bible\Bible;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BibleManagementController extends Controller
{
    public function index()
    {
        return view('dashboard.bibles.home');
    }

    public function show()
    {
        return view('dashboard.bibles.show');
    }

    public function create()
    {
        $languages     = Language::select(['iso', 'name'])->get();
        $organizations = OrganizationTranslation::select(['name', 'organization_id'])->where('language_id', 'eng')->get();
        $alphabets     = Alphabet::select('script')->get();

        return view('dashboard.bibles.create', compact('languages', 'organizations', 'alphabets'));
    }

    public function update($id)
    {
        $bible = Bible::find($id);

        request()->validate([
            'id'                  => 'required|max:24',
            'iso'                 => 'required|exists:dbp.languages,iso',
            'translations.*.name' => 'required',
            'translations.*.iso'  => 'required|exists:dbp.languages,iso',
            'date'                => 'integer',
        ]);

        $bible = \DB::transaction(function () use ($id) {
            $bible = Bible::with('translations', 'organizations', 'equivalents', 'links')->find($id);
            $bible->update(request()->only(['id', 'date', 'script', 'portions', 'copyright', 'derived', 'in_progress', 'notes', 'iso']));

            if (request()->translations) {
                foreach ($bible->translations as $translation) {
                    $translation->delete();
                }
                foreach (request()->translations as $translation) {
                    if ($translation['name']) {
                        $bible->translations()->create($translation);
                    }
                }
            }

            if (request()->organizations) {
                $bible->organizations()->sync(request()->organizations);
            }

            if (request()->equivalents) {
                foreach ($bible->equivalents as $equivalent) {
                    $equivalent->delete();
                }
                foreach (request()->equivalents as $equivalent) {
                    if ($equivalent['equivalent_id']) {
                        $bible->equivalents()->create($equivalent);
                    }
                }
            }

            if (request()->links) {
                foreach ($bible->links as $link) {
                    $link->delete();
                }
                foreach (request()->links as $link) {
                    if ($link['url']) {
                        $bible->links()->create($link);
                    }
                }
            }

            return $bible;
        });

        return view('dashboard.bibles.show', compact('bible'));
    }

    public function edit($id)
    {
        $bible = Bible::with('translations.language')->find($id);
        if (!$this->api) {
            $languages     = Language::select(['iso', 'name'])->orderBy('iso')->get();
            $organizations = OrganizationTranslation::select(['name', 'organization_id'])->where(
                'language_iso',
                'eng'
            )->get();
            $alphabets     = Alphabet::select('script')->get();
            return view('bibles.edit', compact('languages', 'organizations', 'alphabets', 'bible'));
        }

        return $this->reply(fractal($bible, new BibleTransformer())->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'id'                  => 'required|unique:dbp.bibles,id|max:24',
            'iso'                 => 'required|exists:dbp.languages,iso',
            'translations.*.name' => 'required',
            'translations.*.iso'  => 'required|exists:dbp.languages,iso',
            'date'                => 'integer',
        ]);

        $bible = \DB::transaction(function () {
            $bible = new Bible();
            $bible = $bible->create(request()->only(['id', 'date', 'script', 'portions', 'copyright', 'derived', 'in_progress', 'notes', 'iso']));
            $bible->translations()->createMany(request()->translations);
            $bible->organizations()->attach(request()->organizations);
            $bible->equivalents()->createMany(request()->equivalents);
            $bible->links()->createMany(request()->links);
            return $bible;
        });

        return redirect()->route('dashboard.bibles.show', ['id' => $bible->id]);
    }
}
