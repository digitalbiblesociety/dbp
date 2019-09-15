<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\Study\Note;
use App\Transformers\UserNotesTransformer;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Validator;
use App\Traits\CheckProjectMembership;
use App\Traits\AnnotationTags;

class NotesController extends APIController
{
    use AnnotationTags;
    use CheckProjectMembership;

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/users/{user_id}/notes",
     *     tags={"Annotations"},
     *     summary="List a user's notes",
     *     description="Query information about a user's notes",
     *     operationId="v4_notes.index",
     *     @OA\Parameter(name="user_id",     in="path", required=true, description="The user who created the note", @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(name="bible_id",    in="query", description="If provided the fileset_id will filter results to only those related to the Bible", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="book_id",     in="query", description="If provided the USFM 2.4 book id will filter results to only those related to the book. For a complete list see the `book_id` field in the `/bibles/books` route.", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
     *     @OA\Parameter(name="chapter_id",  in="query", description="The starting chapter", @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
     *     @OA\Parameter(name="limit",       in="query", description="The number of highlights to return", @OA\Schema(type="integer",example=25)),
     *     @OA\Parameter(name="paginate",    in="query", description="When set to false will disable pagination", @OA\Schema(type="boolean",example=false)),
     *     @OA\Parameter(name="page",  in="query", description="The current page of the results",
     *          @OA\Schema(type="integer",default=1)),
     *     @OA\Parameter(ref="#/components/parameters/sort_by"),
     *     @OA\Parameter(ref="#/components/parameters/sort_dir"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_notes_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_notes_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_notes_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_notes_index"))
     *     )
     * )
     *
     * @param null|int $user_id
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $user_is_member = $this->compareProjects($user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $bible_id   = checkParam('bible_id');
        $book_id    = checkParam('book_id');
        $chapter_id = checkParam('chapter|chapter_id');
        $sort_by    = checkParam('sort_by');
        $sort_dir   = checkParam('sort_dir') ?? 'asc';
        $limit      = (int) checkParam('limit') ?? 25;
        $limit      = ($limit > 50) ? 50 : $limit;

        $notes = Note::with('tags')->where('user_id', $user_id)
            ->when($bible_id, function ($q) use ($bible_id) {
                $q->where('bible_id', $bible_id);
            })->when($book_id, function ($q) use ($book_id) {
                $q->where('book_id', $book_id);
            })->when($sort_by, function ($q) use ($sort_by, $sort_dir) {
                $q->orderBy($sort_by, $sort_dir);
            })->when($chapter_id, function ($q) use ($chapter_id) {
                $q->where('chapter', $chapter_id);
            })->paginate($limit);

        if (!$notes) {
            return $this->setStatusCode(404)->replyWithError('No User found for the specified ID');
        }
        return $this->reply(fractal($notes->getCollection(), UserNotesTransformer::class)->paginateWith(new IlluminatePaginatorAdapter($notes)));
    }

    /**
     * Show a single note.
     *
     * @OA\Get(
     *     path="/users/{user_id}/notes/{note_id}",
     *     tags={"Annotations"},
     *     summary="Get a Note",
     *     description="",
     *     operationId="v4_notes.show",
     *     @OA\Parameter(name="user_id",     in="path",required=true, description="The user who created the note", @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(name="note_id",     in="path",required=true, description="The note currently being altered", @OA\Schema(ref="#/components/schemas/Note/properties/id")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
     *     )
     * )
     *
     * @param $user_id
     * @param $note_id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $note_id)
    {
        if (!$this->compareProjects($user_id, $this->key)) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $note = Note::where('user_id', $user_id)->where('id', $note_id)->first();
        if (!$note) {
            return $this->setStatusCode(404)->replyWithError(trans('api.errors_404'));
        }

        return $this->reply($note);
    }

    /**
     * Create a single note.
     *
     * @OA\Post(
     *     path="/users/{user_id}/notes",
     *     tags={"Annotations"},
     *     summary="Store a Note",
     *     description="",
     *     operationId="v4_notes.store",
     *     @OA\Parameter(name="user_id",     in="path", required=true, description="The user who is creating the note", @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\RequestBody(required=true, description="Fields for Note Creation", @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="bible_id",                  ref="#/components/schemas/Bible/properties/id"),
     *              @OA\Property(property="user_id",                   ref="#/components/schemas/User/properties/id"),
     *              @OA\Property(property="book_id",                   ref="#/components/schemas/Book/properties/id"),
     *              @OA\Property(property="chapter",                   ref="#/components/schemas/Note/properties/chapter"),
     *              @OA\Property(property="verse_start",               ref="#/components/schemas/Note/properties/verse_start"),
     *              @OA\Property(property="verse_end",               ref="#/components/schemas/Note/properties/verse_end"),
     *              @OA\Property(property="notes",                     ref="#/components/schemas/Note/properties/notes"),
     *          )
     *     )),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
     *     )
     * )
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_is_member = $this->compareProjects($request->user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $invalidNote = $this->invalidNote($request);
        if ($invalidNote) {
            return $invalidNote;
        }

        $note = Note::create([
            'user_id'     => $request->user_id,
            'bible_id'    => $request->bible_id,
            'book_id'     => $request->book_id,
            'chapter'     => $request->chapter,
            'verse_start' => $request->verse_start,
            'verse_end'   => $request->verse_end ?? $request->verse_start,
            'notes'       => isset($request->notes) ? encrypt($request->notes) : null,
        ]);

        $this->handleTags($note);

        return $this->reply(fractal($note, new UserNotesTransformer())->addMeta(['success' => trans('api.user_notes_store_200')]));
    }

    /**
     * Update a single note.
     *
     * @OA\Put(
     *     path="/users/{user_id}/notes/{note_id}",
     *     tags={"Annotations"},
     *     summary="Update a Note",
     *     description="",
     *     operationId="v4_notes.update",
     *     @OA\Parameter(name="user_id", in="path", required=true, description="The user who created the note", @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(name="note_id", in="path", required=true, description="The note currently being altered", @OA\Schema(ref="#/components/schemas/Note/properties/id")),
     *     @OA\RequestBody(required=true, description="Fields for Note Creation", @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="bible_id",                  ref="#/components/schemas/Bible/properties/id"),
     *              @OA\Property(property="book_id",                   ref="#/components/schemas/Book/properties/id"),
     *              @OA\Property(property="chapter",                   ref="#/components/schemas/Note/properties/chapter"),
     *              @OA\Property(property="verse_start",               ref="#/components/schemas/Note/properties/verse_start"),
     *              @OA\Property(property="verse_end",               ref="#/components/schemas/Note/properties/verse_end"),
     *              @OA\Property(property="notes",                     ref="#/components/schemas/Note/properties/notes"),
     *          )
     *     )),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(type="string"))
     *     )
     * )
     *
     * @param Request $request
     * @param         $user_id
     * @param         $note_id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $note_id)
    {
        $user_is_member = $this->compareProjects($user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $invalidNote = $this->invalidNote($request);
        if ($invalidNote) {
            return $invalidNote;
        }

        $note = Note::where('user_id', $user_id)->where('id', $note_id)->first();
        if (!$note) {
            return $this->setStatusCode(404)->replyWithError(trans('api.user_notes_404'));
        }

        $note->fill($request->only(['bible_id','book_id','chapter','verse_start','verse_end','notes']));
        if (isset($request->notes)) {
            $note->notes = encrypt($request->notes);
        }
        $note->save();

        $this->handleTags($note);

        return $this->reply(['success' => 'Note Updated']);
    }

    /**
     * Delete a single note.
     *
     * @OA\Delete(
     *     path="/users/{user_id}/notes/{note_id}",
     *     tags={"Annotations"},
     *     summary="Delete a Note",
     *     description="",
     *     operationId="v4_notes.destroy",
     *     @OA\Parameter(name="user_id", in="path", required=true, description="The user who created the note", @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(name="note_id", in="path", required=true, description="The note currently being deleted", @OA\Schema(ref="#/components/schemas/Note/properties/id")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(type="string"))
     *     )
     * )
     *
     * @param int $user_id
     * @param int $note_id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $user_id, int $note_id)
    {
        $user_is_member = $this->compareProjects($user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $note = Note::where('user_id', $user_id)->where('id', $note_id)->first();
        if (!$note) {
            $this->setStatusCode(404)->replyWithError('Note Not Found');
        }
        $note->delete();

        return $this->reply(['success' => 'Note Deleted']);
    }

    private function invalidNote($request)
    {
        $validator = Validator::make($request->all(), [
            'bible_id'    => (($request->method === 'POST') ? 'required|' : '') . 'exists:dbp.bibles,id',
            'user_id'     => (($request->method === 'POST') ? 'required|' : '') . 'exists:dbp_users.users,id',
            'book_id'     => (($request->method === 'POST') ? 'required|' : '') . 'exists:dbp.books,id',
            'chapter'     => (($request->method === 'POST') ? 'required|' : '') . 'max:150|min:1',
            'verse_start' => (($request->method === 'POST') ? 'required|' : '') . 'max:177|min:1',
            'notes'       => (($request->method === 'POST') ? 'required|' : '') . '',
        ]);
        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        return null;
    }
}
