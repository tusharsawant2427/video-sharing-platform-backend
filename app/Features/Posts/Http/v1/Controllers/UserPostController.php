<?php

namespace App\Features\Posts\Http\v1\Controllers;

use App\Features\Posts\Domains\Models\Post;
use App\Features\Posts\Http\v1\Controllers\Services\UserPostService;
use App\Features\Posts\Http\v1\Data\PostData;
use App\Features\Posts\Http\v1\Data\PostVideoUpload;
use App\Features\Users\Domains\Models\User;
use App\Helpers\Services\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPostController extends Controller
{
    public function __construct(private UserPostService $userPostService)
    {
        $this->userPostService = $userPostService;
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
        $posts = $this->userPostService->getPost(user: $user, page: $page);
        return ResponseHelper::success(data: PostData::collect($posts->items()));
    }

    /**
     * @param PostData $postData
     *
     * @return JsonResponse
     */
    public function store(PostData $postData): JsonResponse
    {
        $post = $this->userPostService->createPost(postData: $postData);
        return ResponseHelper::created(details: PostData::from($post));
    }

    /**
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function edit(Post $post): JsonResponse
    {
        return ResponseHelper::success(data: PostData::from($post));
    }

    /**
     * @param Post $post
     * @param PostData $postData
     *
     * @return JsonResponse
     */
    public function update(Post $post, PostData $postData): JsonResponse
    {
        $this->userPostService->updatePost(post: $post, postData: $postData);
        return ResponseHelper::updated(details: PostData::from($post));
    }

    /**
     * @param Post $post
     * @param PostVideoUpload $postVideoUpload
     *
     * @return JsonResponse
     */
    public function uploadVideo(Post $post, PostVideoUpload $postVideoUpload): JsonResponse
    {
        $this->userPostService->uploadPostVideo(post: $post, postVideoUpload: $postVideoUpload);
        return ResponseHelper::updated(details: PostData::from($post));
    }

    public function delete(Post $post)
    {
        $post->delete();
        return ResponseHelper::success();
    }

}
