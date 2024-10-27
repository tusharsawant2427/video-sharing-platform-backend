<?php

namespace App\Features\Posts\Domains\Models;

use App\Features\Posts\Http\v1\Data\PostLikeData;
use App\Features\Users\Domains\Models\User;
use App\Helpers\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $identifier
 * @property string $likeable_type
 * @property string $likeable_id
 * @property int $is_liked
 */
class PostLike extends BaseModel
{

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
     * Get the owning mediable model.
     *
     * @return MorphTo<Model,Media>
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param PostLikeData $postLikeData
     *
     * @return PostLike
     */
    public static function updateOrCreatePostLike(PostLikeData $postLikeData): PostLike
    {
        return PostLike::updateOrCreate(
            [
                'user_id' => $postLikeData->user_id,
                'likeable_type' => $postLikeData->likeable_type,
                'likeable_id' => $postLikeData->likeable_id
            ],
            [
                'user_id' => $postLikeData->user_id,
                'likeable_type' => $postLikeData->likeable_type,
                'likeable_id' => $postLikeData->likeable_id,
                'is_liked' => $postLikeData->is_liked
            ]
        );
    }
}
