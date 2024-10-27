<?php

namespace App\Features\Posts\Http\v1\Controllers\Services;

use App\Features\Posts\Domains\Models\Post;
use App\Features\Posts\Domains\Models\PostComment;
use App\Features\Posts\Domains\Models\PostLike;
use App\Features\Posts\Http\v1\Data\PostCommentData;
use App\Features\Posts\Http\v1\Data\PostData;
use App\Features\Posts\Http\v1\Data\PostLikeData;
use App\Features\Posts\Http\v1\Data\PostVideoUpload;
use App\Features\Users\Domains\Models\User;
use App\Jobs\MigratePostVideoToS3Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class UserPostService
{
    /**
     * @param User $user
     * @param int $page
     * @param int $length
     *
     *@return LengthAwarePaginator<Product>
     */
    public function getPost(User $user, int $page = 1, int $length = 3): LengthAwarePaginator
    {
        return Post::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate($length, ['*'], 'page', $page);
    }

    /**
     * @param PostData $postData
     *
     * @return Post
     */
    public function createPost(PostData $postData): Post
    {
        return Post::persistPost(postData: $postData);
    }

    /**
     * @param Post $post
     * @param PostData $postData
     *
     * @return bool
     */
    public function updatePost(Post $post, PostData $postData): bool
    {
        return $post->updatePost(postData: $postData);
    }

    /**
     * @param Post $post
     * @param PostVideoUpload $postVideoUpload
     *
     * @return bool
     */
    public function uploadPostVideo(Post $post, PostVideoUpload $postVideoUpload): bool
    {
        $oldPath = $post->media_path;
        $mediaType = $postVideoUpload->video_file->getMimeType();
        $mediaPath = $postVideoUpload->video_file->store('post/video');
        $postResponse = $post->update([
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'status' => Post::INPROGRESS
        ]);

        if ($postResponse && !empty($oldPath)) {
            $this->deleteOldVideoFile(mediaPath: $oldPath);
        }

        MigratePostVideoToS3Job::dispatch($post->id);

        return $postResponse;
    }

    /**
     * @param string $mediaPath
     *
     * @return bool
     */
    private function deleteOldVideoFile(string $mediaPath): bool
    {
        return Storage::delete($mediaPath);
    }

    /**
     * @param PostLikeData $postLikeData
     *
     * @return PostLike
     */
    public function likePost(PostLikeData $postLikeData): PostLike
    {
        return  PostLike::updateOrCreatePostLike(postLikeData: $postLikeData);
    }


    /**
     * @param PostCommentData $postCommentData
     *
     * @return PostComment
     */
    public function commentPost(PostCommentData $postCommentData): PostComment
    {
        return  PostComment::createPostComment(postCommentData: $postCommentData);
    }
}
