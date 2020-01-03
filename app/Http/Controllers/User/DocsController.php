<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\User;
use Illuminate\Support\Facades\Redirect;

class DocsController extends APIController
{
    /**
     * Just Docs Routing, nothing to see here.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('docs.routes.index');
    }

    public function start()
    {
        return Redirect::to(config('app.get_started_url'));
    }

    public function swagger($version)
    {
        return view('docs.swagger_docs');
    }

    public function codeAnalysis()
    {
        $analysis = csvToArray(storage_path('app/code_analysis.csv'));
        $analysis = $analysis[0];

        return view('docs.code_analysis', compact('analysis'));
    }

    public function sdk()
    {
        return view('docs.sdk');
    }

    public function history()
    {
        return view('docs.history');
    }

    /**
     * Move along
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bibles()
    {
        return view('docs.routes.bibles');
    }

    /**
     * Keep going
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bibleEquivalents()
    {
        return view('docs.routes.bibleEquivalents');
    }

    /**
     * No loitering citizen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function books()
    {
        return view('docs.routes.books');
    }

    public function languages()
    {
        return view('docs.routes.languages');
    }

    public function countries()
    {
        return view('docs.routes.countries');
    }

    public function alphabets()
    {
        return view('docs.routes.alphabets');
    }

    public function team()
    {
        $teammates = User::whereHas('role.organization', function ($q) {
            $q->where('role', 'teammember');
        })->get();

        return view('docs.team', compact('teammates'));
    }

    public function bookOrderListing()
    {
        return view('docs.v2.books.bookOrderListing');
    }
}
