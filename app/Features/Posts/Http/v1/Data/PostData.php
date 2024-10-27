<?php

namespace App\Features\Posts\Http\v1\Data;

use App\Features\Posts\Domains\Models\Post;
use App\Features\Posts\Domains\Models\PostLike;
use App\Features\Users\Domains\Models\User;
use App\Features\Users\Http\v1\Data\UserData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class PostData extends Data
{
    /**
     * @param Collection<int,PostCommentData> $post_comments
     */
    public function __construct(
        public ?string $identifier,

        public ?int $user_id,

        #[Max(255)]
        public string $title,

        #[Max(500)]
        public string $post_body,

        public ?string $media_path,
        public ?string $media_type,

        public ?int $status = 1,

        public ?string $created_at,
        public ?string $updated_at,
        public ?string $display_status,
        public ?string $video_url,
        public ?User $user,
        public ?Collection $post_comments,
        public ?int $post_like_count,
        public ?int $post_dislike_count,
        public ?int $post_comments_count = 0,
    ) {
        /**
         * @var User $user
         */
        $user = Auth::guard('api')->user();
        if (!empty($user)) {
            $this->user_id = $user->id;
        }
    }

    public function with(): array
    {
        $post = Post::with([
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
