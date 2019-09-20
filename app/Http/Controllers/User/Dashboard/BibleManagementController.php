<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Models\Bible\Bible;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Models\Language\Language;
use App\Models\Bible\BibleTranslation;
use App\Models\Organization\OrganizationTranslation;
use App\Models\Language\Alphabet;
use App\Models\Bible\Book;

class BibleManagementController extends Controller
{
    public function index()
    {
        $bibles = BibleTranslation::select('bible_id', 'name')->where('language_id', '6414')->distinct()->get();
        return view('dashboard.bibles.home', compact('bibles'));
    }

    public function show()
    {
        return view('dashboard.bibles.show');
    }

    public function create()
    {
        $alphabets     = Alphabet::select('script')->get();
        $languages     = Language::select(['iso', 'name'])->get();
        $bibles        = Bible::with('currentTranslation')->get();
        $organizations = OrganizationTranslation::select(['name', 'organization_id'])->where('language_id', 'eng')->get();
        $books         = Book::all();
        $bible         = new Bible();

        return view('dashboard.bibles.create', compact('languages', 'organizations', 'alphabets', 'bibles', 'bible', 'books'));
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
        $bibles        = Bible::with('translations')->get();
        $bible         = Bible::with('translations.language')->find($id);
        $books         = Book::all();
        $organizations = OrganizationTranslation::select(['name', 'organization_id'])->where('language_id', '6466')->get();
        $alphabets     = Alphabet::select('script')->get();
        $languages     = Language::select(['iso', 'name'])->orderBy('iso')->get();
        $language_current = Language::select(['iso', 'name'])->where('id', $bible->language_id)->first();

        return view('dashboard.bibles.edit', compact('languages', 'organizations', 'alphabets', 'bible', 'bibles', 'books', 'language_current'));
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
