<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFileset;
use App\Models\Language\Language;
use App\Models\Organization\Organization;


class ValidateController extends APIController
{
    public function index()
    {
        $bibles_sine_connections     = Bible::select('id')->whereDoesntHave('filesetConnections')->count();
        $bibles_sine_translations    = Bible::select('id')->whereDoesntHave('translations')->get();
        $bibles_sine_bookNames       = Bible::select('id')->whereDoesntHave('books')->whereHas('filesets')->get();
        $filesets_sine_bible_files   = BibleFileset::select(['hash_id','id'])->whereDoesntHave('files')->where('set_type_code', '!=', 'text_plain')->distinct()->get();
        $filesets_sine_bibleverses   = BibleFileset::select(['hash_id','id'])->whereDoesntHave('verses')->where('set_type_code', 'text_plain')->distinct()->get();
        $filesets_sine_connections   = BibleFileset::select(['hash_id','id'])->whereDoesntHave('connections')->get();
        $filesets_sine_copyrights    = BibleFileset::select(['hash_id','id'])->whereDoesntHave('copyright')->get();
        $filesets_sine_organizations = BibleFileset::select(['hash_id','id'])->whereDoesntHave('copyrightOrganization')->get();
        $filesets_sine_permissions   = BibleFileset::select(['hash_id','id'])->whereDoesntHave('permissions')->get();

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

    public function placeholder_books()
    {
        $books = BibleBook::select(['bible_id','book_id'])->where('name', 'LIKE', '[%')->getQuery()->get()->groupBy('bible_id');
        return view('validations.placeholder_books', compact('books'));
    }

    public function bibles()
    {
        $bibles = Bible::whereHas('filesets')
            ->with('filesets.copyright', 'filesets.organization', 'filesets.permissions', 'translations')
            ->whereHas('filesets', function ($q) {
                $q->where('asset_id', 'dbp-prod');
            })->get();
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
        $organizations = Organization::withCount('filesets')
            ->withCount('resources')
            ->with('logos', 'translations', 'relationships')
            ->get();

        return view('validations.organizations', compact('organizations'));
    }

    public function filesets()
    {
        $days = checkParam('days');
        $media_type = checkParam('media_type');

        $filesets = BibleFileset::with('bible')
            ->where('created_at', '>', now()->subDays($days))
            ->orWhere('updated_at', '>', now()->subDays($days))
            ->orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->when($media_type, function ($query) use ($media_type) {
                $query->where('set_type_code', $media_type);
            })
            ->get();

        return view('validations.filesets', compact('filesets', 'days'));
    }
}
