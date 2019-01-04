<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\APIController;
use App\Models\Bible\Bible;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\BibleLink;

class TaskController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @param $role
     *
     * @return \Illuminate\Http\Response
     */
    public function index($role)
    {
        $counts['Bible Equivalents'] = BibleEquivalent::where('needs_review', 1)->count();
        $counts['Bibles'] = Bible::where('needs_review', 1)->count();
        $counts['Bible Links'] = BibleLink::where('needs_review', 1)->count();

        return view('dashboard.tasks.index', compact('role', 'counts'));
    }

    public function bibles()
    {
        return $this->reply(Bible::where('needs_review', 1)->get());
    }
}
