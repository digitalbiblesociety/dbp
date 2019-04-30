<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFilesetConnection;
use App\Models\Bible\BibleTranslation;
use App\Models\Bible\BibleVerse;
use App\Models\Language\Language;
use App\Models\Organization\Organization;

use Illuminate\Support\Facades\Input;

class ValidateController extends APIController
{
    public function index()
    {
        $bibles_sine_connections     = Bible::select('id')->whereDoesntHave('filesetConnections')->count();
        $bibles_sine_translations    = Bible::whereDoesntHave('translations')->get();
        $bibles_sine_bookNames       = Bible::whereDoesntHave('books')->whereHas('filesets')->get();
        $filesets_sine_bible_files   = BibleFileset::select('hash_id')->whereDoesntHave('files')->where('set_type_code', '!=', 'text_plain')->distinct()->get();
        $filesets_sine_bibleverses   = BibleFileset::whereDoesntHave('verses')->where('set_type_code', 'text_plain')->select('hash_id')->distinct()->get()->pluck('hash_id')->unique();
        $filesets_sine_connections   = BibleFileset::select('hash_id')->whereDoesntHave('connections')->get();
        $filesets_sine_copyrights    = BibleFileset::whereDoesntHave('copyright')->get();
        $filesets_sine_organizations = BibleFileset::whereDoesntHave('copyrightOrganization')->get();
        $filesets_sine_permissions   = BibleFileset::whereDoesntHave('permissions')->get();

        return view('validations.index', compact(
            'bibles_sine_connections',
            'bibles_sine_translations',
            'bibles_sine_bookNames',
            'filesets_sine_bible_files',
            'filesets_sine_bibleverses',
            'filesets_sine_connections',
            'filesets_sine_copyrights',
            'filesets_sine_organizations',
            'filesets_sine_permissions'
        ));
    }

    public function bibles()
    {
        $bibles = Bible::withCount('filesets')->withCount('links')->get();
        return view('validations.bibles', compact('bibles'));
    }

    public function languages()
    {
        $languages = Language::includeAutonymTranslation()
            ->select([
                'languages.id',
                'languages.glotto_id',
                'languages.iso',
                'languages.name as backup_name',
                'autonym.name as autonym'
            ])->get();
        return view('validations.languages', compact('languages'));
    }

    public function organizations()
    {
        $organizations = Organization::withCount('links')
            ->withCount('filesets')
            ->withCount('resources')
            ->with('logos','translations','relationships')
            ->get();

        return view('validations.organizations', compact('organizations'));
    }

    public function filesets()
    {
        $days = Input::get('days') ?? 31;

        $filesets = BibleFileset::with('bible')->where('created_at','>', now()->subDays($days))->get();
        return view('validations.filesets', compact('filesets', 'days'));
    }


}
