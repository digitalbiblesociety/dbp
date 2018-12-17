<?php

namespace App\Http\Controllers\Bible;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleVerse;
use App\Models\Bible\Book;
use App\Models\Bible\BibleFileset;
use App\Transformers\BooksTransformer;
use App\Http\Controllers\APIController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BooksController extends APIController
{

    /**
     *
     * Returns a static list of Scriptural Books and Accompanying meta data
     *
     * @version 4
     * @category v4_bible_books_all
     * @link http://api.dbp.test/bibles/books?key=1234&v=4 - V4 Test Access URL
     * @link https://dbp.test/eng/docs/swagger/v4#/Bible/v4_bible_books2 - V4 Test Docs
     *
     * @OA\Get(
     *     path="/bibles/books/",
     *     tags={"Bibles"},
     *     summary="Returns the books of the Bible",
     *     description="Returns all of the books of the Bible both canonical and deuterocanonical",
     *     operationId="v4_bible_books_all",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(ref="#/components/schemas/v4_bible_books_all")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function index()
    {
        $books = \Cache::rememberForever('v4_books_index', function () {
            $books = Book::orderBy('protestant_order')->get();
            return fractal($books, new BooksTransformer(), $this->serializer);
        });
        return $this->reply($books);
    }

    /**
     *
     * Returns the books and chapters for a specific fileset
     *
     * @version  4
     * @category v4_bible_filesets.books
     * @link     https://api.dbp.test/bibles/filesets/TZTWBT/books?key=e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824&v=4&pretty
     * @link     https://dbp.test/eng/docs/swagger/v4#/Bible/v4_bible_filesets.books - V4 Test Docs
     *
     * @OA\Get(
     *     path="/bibles/filesets/{fileset_id}/books/",
     *     tags={"Bibles"},
     *     summary="Returns the books of the Bible",
     *     description="Returns the books and chapters for a specific fileset",
     *     operationId="v4_bible_filesets.books",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="fileset_id",
     *         in="path",
     *         @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")
     *     ),
     *     @OA\Parameter(
     *         name="fileset_type",
     *         in="query",
     *         required=true,
     *         @OA\Schema(ref="#/components/schemas/BibleFileset/properties/set_type_code"),
     *         description="The type of fileset being queried"
     *     ),
     *     @OA\Parameter(
     *         name="asset_id",
     *         in="query",
     *         @OA\Schema(ref="#/components/schemas/BibleFileset/properties/asset_id"),
     *         description="The asset id to select the fileset by"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(ref="#/components/schemas/v4_bible_books_all")
     *         )
     *     )
     * )
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $fileset_type = checkParam('fileset_type');
        $asset_id = checkParam('asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');

        $cache_string = 'bible_books_'.$id.$fileset_type.$asset_id;
        $books = \Cache::remember($cache_string, 2400, function () use ($fileset_type, $asset_id, $id) {
            $fileset = BibleFileset::where('id', $id)->where('asset_id', $asset_id)->where('set_type_code', $fileset_type)->first();
            if (!$fileset) {
                return $this->replyWithError('Fileset Not Found');
            }
            $is_plain_text = BibleVerse::where('hash_id', $fileset->hash_id)->exists();

            $dbp_database = config('database.connections.dbp.database');
            $books = \DB::connection('dbp')->table($dbp_database.'.bible_filesets as fileset')
                ->where('fileset.id', $id)->where('fileset.asset_id', $asset_id)
                ->leftJoin($dbp_database.'.bible_fileset_connections as connection', 'connection.hash_id', 'fileset.hash_id')
                ->leftJoin($dbp_database.'.bibles', 'bibles.id', 'connection.bible_id')
                ->when($fileset_type, function ($q) use ($fileset_type) {
                    $q->where('set_type_code', $fileset_type);
                })
                ->when($is_plain_text, function ($query) use ($fileset) {
                    $this->compareFilesetToSophiaBooks($query, $fileset->hash_id);
                }, function ($query) use ($fileset) {
                    $this->compareFilesetToFileTableBooks($query, $fileset->hash_id);
                })
                ->orderBy('books.protestant_order')->select([
                    'books.id',
                    'books.id_usfx',
                    'books.id_osis',
                    'books.book_testament',
                    'books.testament_order',
                    'books.protestant_order',
                    'books.book_group',
                    'bible_books.chapters',
                    'bible_books.name'
                ])->get();

            return fractal($books, new BooksTransformer(), $this->serializer);
        });

        if (is_a($books, JsonResponse::class)) {
            return $books;
        }

        return $this->reply($books);
    }

    /**
     *
     * @param $query
     * @param $id
     */
    private function compareFilesetToSophiaBooks($query, $hash_id)
    {
        // If the fileset references sophia.*_vpl than fetch the existing books from that database
        $dbp_database = config('database.connections.dbp.database');
        $sophia_books = BibleVerse::where('hash_id', $hash_id)->select('book_id')->distinct()->get();

        // Join the books for the books returned from Sophia
        $query->join($dbp_database.'.bible_books', function ($join) use ($sophia_books) {
            $join->on('bible_books.bible_id', 'bibles.id')
                 ->whereIn('bible_books.book_id', $sophia_books->pluck('book_id'));
        })->rightJoin($dbp_database.'.books', 'books.id', 'bible_books.book_id');
    }

    /**
     *
     * @param $query
     * @param $hashId
     */
    private function compareFilesetToFileTableBooks($query, $hashId)
    {
        // If the fileset referencesade dbp.bible_files from that table
        $dbp_database = config('database.connections.dbp.database');
        $fileset_book_ids = DB::connection('dbp')
            ->table('bible_files')
            ->where('hash_id', $hashId)
            ->select(['book_id'])
            ->distinct()
            ->get()
            ->pluck('book_id');

        // Join the books for the books returned from bible_files
        $query->join($dbp_database.'.bible_books', function ($join) use ($fileset_book_ids) {
            $join->on('bible_books.bible_id', 'bibles.id')
                 ->whereIn('bible_books.book_id', $fileset_book_ids);
        })->rightJoin($dbp_database.'.books', 'books.id', 'bible_books.book_id');
    }
}
