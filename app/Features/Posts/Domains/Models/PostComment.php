<?php

namespace App\Features\Posts\Domains\Models;

use App\Features\Posts\Http\v1\Data\PostCommentData;
use App\Features\Users\Domains\Models\User;
use App\Helpers\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $identifier
 * @property int $post_id
 * @property int $user_id
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 */
class PostComment extends BaseModel
{

    /**
     * @return MorphMany<PostLike>
     */
    public function postLikes(): MorphMany
    {
        return $this->morphMany(PostLike::class, 'likeable')->where('likeable_type', PostComment::class);
    }

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param PostCommentData $postCommentData
     *
     * @return PostComment
     */
    public static function createPostComment(PostCommentData $postCommentData): PostComment
    {
        return PostComment::firstOrCreate(
            [
                'user_id' => $postCommentData->user_id,
                'post_id' => $postCommentData->post_id,
                'comment' => $postCommentData->comment
            ],
            [
                'user_id' => $postCommentData->user_id,
                'post_id' => $postCommentData->post_id,
                'comment' => $postCommentData->comment
            ]
        );
    }
}
