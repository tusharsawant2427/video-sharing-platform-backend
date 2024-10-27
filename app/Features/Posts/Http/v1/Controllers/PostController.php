<?php

namespace App\Features\Posts\Http\v1\Controllers;

use App\Features\Posts\Domains\Models\Post;
use App\Features\Posts\Domains\Models\PostComment;
use App\Features\Posts\Http\v1\Controllers\Services\PostService;
use App\Features\Posts\Http\v1\Data\PostCommentData;
use App\Features\Posts\Http\v1\Data\PostData;
use App\Features\Posts\Http\v1\Data\PostLikeData;
use App\Features\Users\Domains\Models\User;
use App\Helpers\Services\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
        $this->postService = $postService;
    }


    public function index(Request $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::guard('api')->user();
        /**
         * @var int $page
         */
        $page = $request->get('page', 1);
        $posts = $this->postService->getPost(user: $user, page: $page);
        return ResponseHelper::success(data: PostData::collect($posts->items()));
    }

    /**
     * @param Post $post
     * @param PostLikeData $postLikeData
     *
     * @return JsonResponse
     */
    public function likePost(Post $post, PostLikeData $postLikeData): JsonResponse
    {

        /**
         * @var User $user
         */
        $user = Auth::guard('api')->user();

        $postLikeData->setLikeableId($post->id);
        $postLikeData->setLikeableType(Post::class);
        $postLikeData->setUserId($user->id);
        $postLike = $this->postService->likePost(postLikeData: $postLikeData);
        return ResponseHelper::success(data: [
            "is_liked" => $postLike->is_liked
        ]);
    }

    /**
     * @param PostComment $postComment
     * @param PostLikeData $postLikeData
     *
     * @return JsonResponse
     */
    public function likeComment(PostComment $postComment, PostLikeData $postLikeData): JsonResponse
    {

        /**
         * @var User $user
         */
        $user = Auth::guard('api')->user();

        $postLikeData->setLikeableId($postComment->id);
        $postLikeData->setLikeableType(PostComment::class);
        $postLikeData->setUserId($user->id);
        $postLike = $this->postService->likePost(postLikeData: $postLikeData);
        return ResponseHelper::success(data: [
            "is_liked" => $postLike->is_liked
        ]);
    }


    /**
     * @param Post $post
     * @param PostCommentData $postCommentData
     *
     * @return JsonResponse
     */
    public function commentPost(Post $post, PostCommentData $postCommentData): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::guard('api')->user();

        $postCommentData->setPostId($post->id);
        $postCommentData->setUserId($user->id);

        $postComment = $this->postService->commentPost(postCommentData: $postCommentData);
        return ResponseHelper::success(data: PostCommentData::from($postComment->load('user')));
    }
}
