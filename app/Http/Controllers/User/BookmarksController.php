<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\Bible\Book;
use App\Models\User\Study\Bookmark;
use App\Traits\CheckProjectMembership;
use App\Transformers\UserBookmarksTransformer;
use App\Transformers\V2\Annotations\BookmarkTransformer;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Traits\AnnotationTags;

class BookmarksController extends APIController
{

    use AnnotationTags;
    use CheckProjectMembership;

    /**
     * Display a listing of the bookmarks.
     *
     * @OA\Get(
     *     path="/users/{user_id}/bookmarks/",
     *     tags={"Annotations"},
     *     summary="List a user's bookmarks",
     *     description="",
     *     operationId="v4_user_annotation_bookmarks.index",
     *     @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          required=true,
     *          description="The user_id",
     *          @OA\Schema(ref="#/components/schemas/User/properties/id")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(ref="#/components/schemas/v4_user_bookmarks")
     *         )
     *     )
     * )
     *
     * @param int $user_id
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $user_is_member = $this->compareProjects($user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $bible_id     = checkParam('bible_id');
        $book_id      = checkParam('book_id');
        $chapter      = checkParam('chapter|chapter_id');
        $limit        = (int) (checkParam('limit') ?? 25);

        $bookmarks = Bookmark::with('tags')->where('user_id', $user_id)
            ->when($book_id, function ($q) use ($bible_id) {
                $q->where('bible_id', $bible_id);
            })->when($book_id, function ($q) use ($book_id) {
                $q->where('book_id', $book_id);
            })->when($chapter, function ($q) use ($chapter) {
                $q->where('chapter', $chapter);
            })->paginate($limit);

        $bookmarkCollection = $bookmarks->getCollection();
        $bookmarkPagination = new IlluminatePaginatorAdapter($bookmarks);
        return $this->reply(fractal($bookmarkCollection, UserBookmarksTransformer::class, $this->serializer)->paginateWith($bookmarkPagination));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/users/{user_id}/bookmarks/",
     *     tags={"Annotations"},
     *     summary="Create a bookmark",
     *     description="Returns filtered permissions for a fileset dependent upon your authorization level and API key",
     *     operationId="v4_user_annotation_bookmarks.store",
     *     @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          required=true,
     *          description="The user_id",
     *          @OA\Schema(ref="#/components/schemas/User/properties/id")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(ref="#/components/schemas/v4_user_bookmarks")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $user_is_member = $this->compareProjects(request()->user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $book = Book::where('id', request()->book_id)->first();
        request()->book_id = $book->id;
        request()->bible_id = request()->dam_id ?? request()->bible_id;

        $invalidBookmark = $this->validateBookmark();
        if ($invalidBookmark) {
            return $this->setStatusCode(422)->replyWithError($invalidBookmark);
        }

        $bookmark = Bookmark::create(request()->all());

        $this->handleTags($bookmark);

        return $this->reply(fractal($bookmark, BookmarkTransformer::class)->addMeta(['success' => 'Bookmark Created successfully']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/users/{user_id}/bookmarks/{bookmark_id}",
     *     tags={"Annotations"},
     *     summary="Update a bookmark",
     *     description="",
     *     operationId="v4_user_annotation_bookmarks.update",
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(name="bookmark_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(ref="#/components/schemas/v4_user_bookmarks")
     *         )
     *     )
     * )
     *
     * @param  int $user_id
     * @param  int $bookmark_id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($user_id, $bookmark_id)
    {
        $user_is_member = $this->compareProjects($user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $invalidBookmark = $this->validateBookmark();
        if ($invalidBookmark) {
            return $this->setStatusCode(422)->replyWithError($invalidBookmark);
        }

        $bookmark = Bookmark::where('id', $bookmark_id)->where('user_id', $user_id)->first();
        if (!$bookmark) {
            return $this->setStatusCode(404)->replyWithError('Bookmark not found');
        }
        $bookmark->fill(request()->all());
        $bookmark->save();

        $this->handleTags($bookmark);

        return $this->reply(fractal($bookmark, new BookmarkTransformer())->addMeta(['success' => 'Bookmark Successfully updated']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/users/{user_id}/bookmarks/{bookmark_id}",
     *     tags={"Annotations"},
     *     summary="Delete a bookmark",
     *     description="",
     *     operationId="v4_user_annotation_bookmarks.delete",
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(name="bookmark_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(ref="#/components/schemas/v4_user_bookmarks")
     *         )
     *     )
     * )
     *
     * @param  int  $user_id
     * @param  int  $bookmark_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $bookmark_id)
    {
        $user_is_member = $this->compareProjects($user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $bookmark = Bookmark::where('id', $bookmark_id)->where('user_id', $user_id)->first();
        $bookmark->delete();

        return $this->reply('bookmark successfully deleted');
    }

    private function validateBookmark()
    {
        $validator = Validator::make(request()->all(), [
            'bible_id'    => ((request()->method() === 'POST') ? 'required|' : ''). 'exists:dbp.bibles,id',
            'user_id'     => ((request()->method() === 'POST') ? 'required|' : ''). 'exists:dbp_users.users,id',
            'book_id'     => ((request()->method() === 'POST') ? 'required|' : ''). 'exists:dbp.books,id',
            'chapter'     => ((request()->method() === 'POST') ? 'required|' : ''). 'max:150|min:1|integer',
            'verse_start' => ((request()->method() === 'POST') ? 'required|' : ''). 'max:177|min:1|integer'
        ]);
        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
    }
}
