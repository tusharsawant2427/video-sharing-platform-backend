<?php

namespace App\Features\Posts\Http\v1\Data;

use App\Features\Posts\Domains\Models\PostComment;
use App\Features\Users\Domains\Models\User;
use App\Features\Users\Http\v1\Data\UserData;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class PostCommentData extends Data
{

    public function __construct(
        public ?string $identifier,
        #[Max(255)]
        public string $comment,
        public ?int $post_id,
        public ?int $user_id,
        public ?User $user
    ) {}

    /**
     * @param int $postId
     */
    public function setPostId(int $postId): void
    {
        $this->post_id = $postId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }


    public function with(): array
    {
        $post = PostComment::with([
            'postLikes' => function ($q) {
                $q->where('user_id', $this->user_id);
            }
        ])->where('identifier', $this->identifier)->first();
        $isLiked =  (!empty($post->postLikes) && !empty($post->postLikes->where('is_liked', 1)->count())) ? 1 : null;
        $isDisliked = (!empty($post->postLikes) && !empty($post->postLikes->where('is_liked', 0)->count())) ? 1 : null;
        return [
            'is_liked' => (isset($isLiked) && $isLiked == 1) ?  1 : ((isset($isDisliked) && $isDisliked == 1) ?  0 : null),
        ];
    }
}
