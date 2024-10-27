<?php

namespace App\Features\Posts\Http\v1\Controllers\Services;

use App\Features\Posts\Domains\Models\Post;
use App\Features\Posts\Domains\Models\PostComment;
use App\Features\Posts\Domains\Models\PostLike;
use App\Features\Posts\Http\v1\Data\PostCommentData;
use App\Features\Posts\Http\v1\Data\PostLikeData;
use App\Features\Users\Domains\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService
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
        return Post::where('status', Post::ACTIVE)->with([
            'user',
            'postLikes' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            },
            'postComments.user'
        ])->withCount([
            'postLikes as post_like_count' => function ($q) {
                return $q->where('is_liked', 1);
            },
            'postLikes as post_dislike_count' => function ($q) {
                return $q->where('is_liked', 0);
            },
            'postComments'
        ])->orderBy('id', 'DESC')->paginate($length, ['*'], 'page', $page);
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
