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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

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
     * Handles the file upload
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws UploadMissingFileException
     * @throws UploadFailedException
     */
    public function upload(Post $post, Request $request)
    {
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            return $this->uploadVideo($save->getFile(), $post, $request);
        }

        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }


    /**
     * Saves the file
     *
     * @param UploadedFile $file
     *
     * @return JsonResponse
     */
    protected function uploadVideo(UploadedFile $file, Post $post, Request $request)
    {
        $postVideoUpload = new PostVideoUpload(video_file: $file);
        $this->userPostService->uploadPostVideo(post: $post, postVideoUpload: $postVideoUpload);
        return ResponseHelper::updated(details: PostData::from($post));
    }

    public function delete(Post $post)
    {
        $this->userPostService->deleteOldVideoFile(mediaPath: $post->media_path);
        $post->delete();
        return ResponseHelper::success();
    }
}
