<?php

namespace App\Http\Controllers\Bible;

use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleVerse;
use App\Models\Bible\Book;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BookTranslation;
use App\Models\Language\Language;
use App\Transformers\V2\LibraryCatalog\BookTransformer;
use App\Http\Controllers\APIController;

class BooksControllerV2 extends APIController
{

    /**
     * Gets the book order and code listing for a volume.
     *
     * @version 2
     * @category v2_library_book
     * @category v2_library_bookOrder
     * @link http://dbt.io/library/bookorder - V2 Access
     * @link http://api.dbp.test/library/bookorder?key=1234&v=2&dam_id=AMKWBT&pretty - V2 Test
     * @link https://dbp.test/eng/docs/swagger/v2#/Library/v2_library_book - V2 Test Docs
     *
     * @OA\Get(
     *     path="/library/book/",
     *     tags={"Library Catalog"},
     *     summary="Returns books order",
     *     description="Gets the book order and code listing for a volume.",
     *     operationId="v2_library_book",
     *     @OA\Parameter(name="dam_id",in="query",required=true, @OA\Schema(ref="#/components/schemas/Bible/properties/id")),
     *     @OA\Parameter(name="asset_id",in="query", @OA\Schema(ref="#/components/schemas/Asset/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_book"))
     *     )
     * )
     *
     * @param dam_id - the volume internal bible_id.
     *
     * @return Book string - A JSON string that contains the status code and error messages if applicable.
     */
    public function book()
    {
        $id        = checkParam('dam_id');
        $asset_id  = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');

        $testament = $this->getTestamentString($id);

        $fileset   = BibleFileset::with('bible')->uniqueFileset($id, $asset_id, null, null, $testament)->first();

        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $id]));
        }

        $cache_string = strtolower('v2_library_book:' . $asset_id .':'. $id .':' . $fileset . '_' . implode('-', $testament));
        $libraryBook = \Cache::remember($cache_string, now()->addDay(), function () use ($id, $fileset, $testament) {

            if ($fileset->set_type_code === 'text_plain') {
                // If plain text check BibleVerse
                $booksChapters = BibleVerse::where('hash_id', $fileset->hash_id)->select(['book_id', 'chapter'])->distinct()->get();
                $chapter_field = 'chapter';
            } else {
                // Otherwise refer to Bible Files
                $booksChapters = BibleFile::where('hash_id', $fileset->hash_id)->select(['book_id','chapter_start'])->distinct()->get();
                $chapter_field = 'chapter_start';
            }

               $books = Book::whereIn('id', $booksChapters->pluck('book_id')->unique())
                            ->filterByTestament($testament)
                            ->orderBy('protestant_order')
                            ->get();

                foreach ($books as $key => $book) {
                    $current_chapters[$key] = $booksChapters->where('book_id', $book->id)->pluck($chapter_field);
                }

                $bible_id = $fileset->bible->first()->id;
                foreach ($books as $key => $book) {
                    $books[$key]->source_id       = $id;
                    $books[$key]->bible_id        = $bible_id;
                    $books[$key]->number_chapters = $current_chapters[$key]->count();
                    $books[$key]->chapters        = $current_chapters[$key]->implode(',');
                }

                return fractal($books, new BookTransformer(), $this->serializer);
            }
        );

        return $this->reply($libraryBook);
    }

    public function bookOrder()
    {
        $id        = checkParam('dam_id', true);
        $asset_id  = checkParam('bucket|bucket_id|asset_id') ?? 'dbp-prod';

        $fileset   = BibleFileset::with('bible')->uniqueFileset($id, $asset_id, 'text_plain')->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $id]));
        }

        $testament = $this->getTestamentString($id);

        $cache_string = strtolower('v2_library_bookOrder_' . $id . $asset_id . $fileset . $testament);
        $libraryBook = \Cache::remember($cache_string, now()->addDay(),
            function () use ($id, $fileset, $testament) {
                $booksChapters = BibleVerse::where('hash_id', $fileset->hash_id)->select('book_id', 'chapter')->distinct()->get();
                $books = Book::whereIn('id', $booksChapters->pluck('book_id')->unique()->toArray())
                             ->when(!empty($testament), function ($q) use ($testament) {
                                 $q->where('book_testament', $testament);
                             })->orderBy('protestant_order')->get();

                $bible_id = $fileset->bible()->first()->id;

                foreach ($books as $key => $book) {
                    $chapters                     = $booksChapters->where('book', $book->id_usfx)->pluck('chapter');
                    $books[$key]->source_id       = $id;
                    $books[$key]->bible_id        = $bible_id;
                    $books[$key]->chapters        = $chapters->implode(',');
                    $books[$key]->number_chapters = $chapters->count();
                }

                return fractal($books, new BookTransformer())->serializeWith($this->serializer);
            }
        );

        return $this->reply($libraryBook);
    }

    /**
     * Gets the book order and code listing for a volume.
     *
     * @version 2
     * @category v2_library_bookName
     * @link http://dbt.io/library/bookname - V2 Access
     * @link http://api.dbp.test/library/bookname?key=1234&v=2&language_code=ben - V2 Test Access
     * @link https://dbp.test/eng/docs/swagger/v2#/Library/v2_library_bookname - V2 Test Docs
     *
     * @OA\Get(
     *     path="/library/bookname/",
     *     tags={"Library Catalog"},
     *     summary="Returns book Names",
     *     description="Gets the book order and code listing for a volume.",
     *     operationId="v2_library_bookName",
     *     @OA\Parameter(name="language_code",in="query",description="The language_code. For a complete list see the `iso` field in the `/languages` route",required=true, @OA\Schema(ref="#/components/schemas/Language/properties/iso")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_bookName"))
     *     )
     * )
     *
     * @param language_code - The language code to filter the books by
     *
     * @return BookTranslation string - A JSON string that contains the status code and error messages if applicable.
     *
     */
    public function bookNames()
    {
        if (!$this->api) {
            return view('docs.books.bookNames');
        }
        $iso = checkParam('language_code');
        $language = Language::where('iso', $iso)->first();
        if (!$language) {
            return $this->setStatusCode(404)->replywithError('No language could be found for the iso code specified');
        }

        $cache_string = 'v2_library_bookName_' . strtolower($iso);
        $libraryBookName = \Cache::remember($cache_string, now()->addDay(), function () use ($language) {
            $bookTranslations = BookTranslation::where('language_id', $language->id)->with('book')->select(['name', 'book_id'])->get()->pluck('name', 'book.id_osis');
            $bookTranslations['AL'] = 'Alternative';
            $bookTranslations['ON'] = 'Old and New Testament';
            $bookTranslations['OT'] = 'Old Testament';
            $bookTranslations['NT'] = 'New Testament';
            $bookTranslations['AP'] = 'Apocrypha';
            $bookTranslations['VU'] = 'Vulgate';
            $bookTranslations['ET'] = 'Ethiopian Orthodox Canon/Geez Translation Additions';
            $bookTranslations['CO'] = 'Coptic Orthodox Canon Additions';
            $bookTranslations['AO'] = 'Armenian Orthodox Canon Additions';
            $bookTranslations['PE'] = 'Peshitta';
            $bookTranslations['CS'] = 'Codex Sinaiticus';
            return [$bookTranslations];
        });

        /**
         * @OA\Schema (
         *  type="object",
         *  schema="v2_library_bookName",
         *  description="The Books Response",
         *  title="v2_library_bookName",
         *  @OA\Xml(name="v2_library_bookName"),
         *     @OA\Property(type="string", title="Alternative", description="", property="AL", example=""),
         *     @OA\Property(type="string", title="Old and New Testament", description="The translated string for the combined testaments of the bible", property="ON", example=""),
         *     @OA\Property(type="string", title="Old Testament", description="The translated string for the old testament of the bible", property="OT", example="Vieux Testament"),
         *     @OA\Property(type="string", title="New Testament", description="The translated string for the new testament of the bible", property="NT", example="Nouveau Testament"),
         *     @OA\Property(type="string", title="Apocrypha", description="The translated string for the collected apocryphal books", property="AP", example=""),
         *     @OA\Property(type="string", title="Vulgate", description="The translation string for the Vulgate", property="VU", example=""),
         *     @OA\Property(type="string", title="Ethiopian Orthodox Canon/Geez Translation Additions", description="The translation string for the Ethiopian Orthodox order for books of the bible", property="ET", example=""),
         *     @OA\Property(type="string", title="Coptic Orthodox Canon Additions", description="The translation string for the Coptic Orthodox order for books of the bible", property="CO", example=""),
         *     @OA\Property(type="string", title="Armenian Orthodox Canon Additions", description="The translation string for the Armenian Orthodox order for books of the bible", property="AO", example=""),
         *     @OA\Property(type="string", title="Peshitta", description="The translation string for Peshitta", property="PE", example=""),
         *     @OA\Property(type="string", title="Codex Sinaiticus", description="The translation string for Codex Sinaiticus", property="CS", example=""),
         *     @OA\Property(type="string", title="Genesis", property="Gen", description="The translation string for the book Genesis", example="Genèse"),
         *     @OA\Property(type="string", title="Exodus", property="Exod", example="Exode"),
         *     @OA\Property(type="string", title="Leviticus", property="Lev", example="Lévitique"),
         *     @OA\Property(type="string", title="Numbers", property="Num", example="Nombres"),
         *     @OA\Property(type="string", title="Deuteronomy", property="Deut", example="Deutéronome"),
         *     @OA\Property(type="string", title="Joshua", property="Josh", example="Josué"),
         *     @OA\Property(type="string", title="Judges", property="Judg", example="Juges"),
         *     @OA\Property(type="string", title="Ruth", property="Ruth", example="Ruth"),
         *     @OA\Property(type="string", title="1 Samuel", property="1Sam", example="Iier Samuel"),
         *     @OA\Property(type="string", title="2 Samuel", property="2Sam", example="IIième Samuel"),
         *     @OA\Property(type="string", title="1 Kings", property="1Kgs", example="Iier Rois"),
         *     @OA\Property(type="string", title="2 Kings", property="2Kgs", example="IIième Rois"),
         *     @OA\Property(type="string", title="1 Chronicles", property="1Chr", example="Iier Chroniques"),
         *     @OA\Property(type="string", title="2 Chronicles", property="2Chr", example="IIième Chroniques"),
         *     @OA\Property(type="string", title="Ezra", property="Ezra", example="Esdras"),
         *     @OA\Property(type="string", title="Nehemiah", property="Neh", example="Néhémie"),
         *     @OA\Property(type="string", title="Esther", property="Esth", example="Esther"),
         *     @OA\Property(type="string", title="Job", property="Job", example="Job"),
         *     @OA\Property(type="string", title="Psalm", property="Ps", example="Psaumes"),
         *     @OA\Property(type="string", title="Proverbs", property="Prov", example="Proverbes"),
         *     @OA\Property(type="string", title="Ecclesiastes", property="Eccl", example="Ecclésiaste"),
         *     @OA\Property(type="string", title="Song of Solomon", property="Song", example="Cantique des Cantiques"),
         *     @OA\Property(type="string", title="Isaiah", property="Isa", example="Esaïe"),
         *     @OA\Property(type="string", title="Jeremiah", property="Jer", example="Jérémie"),
         *     @OA\Property(type="string", title="Lamentations", property="Lam", example="Lamentation"),
         *     @OA\Property(type="string", title="Ezekiel", property="Ezek", example="Ezékiel"),
         *     @OA\Property(type="string", title="Daniel", property="Dan", example="Daniel"),
         *     @OA\Property(type="string", title="Hosea", property="Hos", example="Osée"),
         *     @OA\Property(type="string", title="Joel", property="Joel", example="Joël"),
         *     @OA\Property(type="string", title="Amos", property="Amos", example="Amos"),
         *     @OA\Property(type="string", title="Obadiah", property="Obad", example="Abdias"),
         *     @OA\Property(type="string", title="Jonah", property="Jonah", example="Jonas"),
         *     @OA\Property(type="string", title="Micah", property="Mic", example="Michée"),
         *     @OA\Property(type="string", title="Nahum", property="Nah", example="Nahum"),
         *     @OA\Property(type="string", title="Habakkuk", property="Hab", example="Habacuc"),
         *     @OA\Property(type="string", title="Zephaniah", property="Zeph", example="Sophonie"),
         *     @OA\Property(type="string", title="Haggai", property="Hag", example="Aggée"),
         *     @OA\Property(type="string", title="Zechariah", property="Zech", example="Zacharie"),
         *     @OA\Property(type="string", title="Malachi", property="Mal", example="Malachie"),
         *     @OA\Property(type="string", title="Matthew", property="Matt", example="Matthieu"),
         *     @OA\Property(type="string", title="Mark", property="Mark", example="Marc"),
         *     @OA\Property(type="string", title="Luke", property="Luke", example="Luc"),
         *     @OA\Property(type="string", title="John", property="John", example="Jean"),
         *     @OA\Property(type="string", title="Acts", property="Acts", example="Actes"),
         *     @OA\Property(type="string", title="Romans", property="Rom", example="Romains"),
         *     @OA\Property(type="string", title="1 Corinthians", property="1Cor", example="Iier Corinthiens"),
         *     @OA\Property(type="string", title="2 Corinthians", property="2Cor", example="IIième Corinthiens"),
         *     @OA\Property(type="string", title="Galatians", property="Gal", example="Galates"),
         *     @OA\Property(type="string", title="Ephesians", property="Eph", example="Ephésiens"),
         *     @OA\Property(type="string", title="Philippians", property="Phil", example="Philippiens"),
         *     @OA\Property(type="string", title="Colossians", property="Col", example="Colossiens"),
         *     @OA\Property(type="string", title="1 Thessalonians", property="1Thess", example="Iier Thessaloniciens"),
         *     @OA\Property(type="string", title="2 Thessalonians", property="2Thess", example="IIième Thessaloniciens"),
         *     @OA\Property(type="string", title="1 Timothy", property="1Tim", example="Iier Timothée"),
         *     @OA\Property(type="string", title="2 Timothy", property="2Tim", example="IIième Timothée"),
         *     @OA\Property(type="string", title="Titus", property="Titus", example="Tite"),
         *     @OA\Property(type="string", title="Philemon", property="Phlm", example="Philémon"),
         *     @OA\Property(type="string", title="Hebrews", property="Heb", example="Hébreux"),
         *     @OA\Property(type="string", title="James", property="Jas", example="Jacques"),
         *     @OA\Property(type="string", title="1 Peter", property="1Pet", example="Iier Pierre"),
         *     @OA\Property(type="string", title="2 Peter", property="2Pet", example="IIième Pierre"),
         *     @OA\Property(type="string", title="1 John", property="1John", example="Iier Jean"),
         *     @OA\Property(type="string", title="2 John", property="2John", example="IIième Jean"),
         *     @OA\Property(type="string", title="3 John", property="3John", example="IIIième Jean"),
         *     @OA\Property(type="string", title="Jude", property="Jude", example=""),
         *     @OA\Property(type="string", title="Revelation", property="Rev", example="Apocalypse"),
         *     @OA\Property(type="string", title="Tobit", property="Tob", example=""),
         *     @OA\Property(type="string", title="Judith", property="Jdt", example=""),
         *     @OA\Property(type="string", title="Sirach", property="Sir", example="Siracide"),
         *     @OA\Property(type="string", title="Baruch", property="Bar", example="Baruc"),
         *     @OA\Property(type="string", title="Prayer of Azariah", property="PrAzar", example=""),
         *     @OA\Property(type="string", title="Susanna", property="Sus", example=""),
         *     @OA\Property(type="string", title="Bel and the Dragon", property="Bel", example=""),
         *     @OA\Property(type="string", title="1 Maccabees", property="1Macc", example="1 Maccabées"),
         *     @OA\Property(type="string", title="2 Maccabees", property="2Macc", example="2 Maccabées"),
         *     @OA\Property(type="string", title="3 Maccabees", property="3Macc", example=""),
         *     @OA\Property(type="string", title="4 Maccabees", property="4Macc", example=""),
         *     @OA\Property(type="string", title="Prayer of Manasseh", property="PrMan", example=""),
         *     @OA\Property(type="string", title="1 Esdras", property="1Esd", example=""),
         *     @OA\Property(type="string", title="2 Esdras", property="2Esd", example=""),
         *     @OA\Property(type="string", title="Greek Daniel", property="DanGr", example="")
         *   )
         * )
         */
        return $this->reply($libraryBookName);
    }

    /**
     * This lists the chapters for a book or all books in a standard bible volume.
     *
     * @version 2
     * @category v2_library_chapter
     * @link http://dbt.io/library/chapter - V2 Access
     * @link https://api.dbp.test/library/chapter?key=1234&v=2&dam_id=AMKWBT&book_id=MAT&pretty - V2 Test Access
     * @link https://dbp.test/eng/docs/swagger/v2#/Library/v2_library_chapter - V2 Test Docs
     *
     * @OA\Get(
     *     path="/library/chapter/",
     *     tags={"Library Catalog"},
     *     summary="Returns chapters for a book",
     *     description="Lists the chapters for a book or all books in a standard bible volume.",
     *     operationId="v2_library_chapter",
     *     @OA\Parameter(name="dam_id",in="query",description="The bible_id",required=true, @OA\Schema(ref="#/components/schemas/Bible/properties/id")),
     *     @OA\Parameter(name="asset_id",in="query",description="The asset_id", @OA\Schema(ref="#/components/schemas/Asset/properties/id")),
     *     @OA\Parameter(name="book_id",in="query",description="The book_id. For a complete list see the `book_id` field in the `/bibles/books` route.",required=true, @OA\Schema(ref="#/components/schemas/Book/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(type="object",example={"GEN"="Genesis","EXO"="Exodus"}))
     *     )
     * )
     *
     * @param dam_id - The Fileset ID to filter by
     * @param book_id - The USFM 2.4 or OSIS Book ID code
     * @param asset_id - The optional asset ID of the resource, if not given the API will assume FCBH origin
     *
     * @return mixed $chapters string - A JSON string that contains the status code and error messages if applicable.
     *
     */
    public function chapters()
    {
        if (!$this->api) {
            return view('docs.books.chapters');
        }

        $id        = checkParam('dam_id');
        $asset_id  = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $book_id   = checkParam('book_id');

        $cache_string = strtolower('v2_library_chapter:' . $asset_id . ':' . $id . '_' . $book_id);
        $chapters = \Cache::remember($cache_string, now()->addDay(), function () use ($id, $asset_id, $book_id) {

            $fileset = BibleFileset::with('bible')->uniqueFileset($id, $asset_id, 'text_plain')->first();
            if (!$fileset) {
                return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $id]));
            }

            $book = Book::where('id_osis', $book_id)->orWhere('id', $book_id)->first();
            if (!$book) {
                return $this->setStatusCode(404)->replyWithError(trans('api.bible_books_errors_404', ['id' => $id]));
            }

            $chapters = BibleVerse::where('hash_id', $fileset->hash_id)
                ->when($book, function ($q) use ($book) {
                    $q->where('book_id', $book->id);
                })
                ->select(['chapter', 'book_id'])->distinct()->orderBy('chapter')->get()
                ->map(function ($chapter) use ($id, $book) {
                    $chapter->book_id  = $book->id_osis;
                    $chapter->bible_id = $id;
                    $chapter->source_id = $id;
                    return $chapter;
                });

            return fractal($chapters, new BookTransformer(), $this->serializer);
        });

        return $this->reply($chapters);
    }

    private function getTestamentString($id)
    {
        $testament = false;
        switch ($id[\strlen($id) - 2]) {
            case 'O':
                $testament = ['OT','C'];
                break;

            case 'N':
                $testament = ['NT','C'];
                break;

            case 'P':
                $testament = ['NTOTP', 'NTP', 'NTPOTP', 'OTNTP', 'OTP', 'P'];
                break;
        }
        return [];
    }

}
