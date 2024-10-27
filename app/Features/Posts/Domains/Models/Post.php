<?php

namespace App\Features\Posts\Domains\Models;

use App\Features\Posts\Domains\Constants\PostConstants;
use App\Features\Posts\Http\v1\Data\PostData;
use App\Features\Users\Domains\Models\User;
use App\Helpers\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $identifier
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $post_body
 * @property string $media_path
 * @property string $media_type
 * @property int $status
 * @property ?string $created_at
 * @property ?string $updated_at
 */

class Post extends BaseModel implements PostConstants
{

    protected $appends = [
        'display_status',
        'video_url'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphMany<PostLike>
     */
    public function postLikes(): MorphMany
    {
        return $this->morphMany(PostLike::class, 'likeable')->where('likeable_type', Post::class);
    }

    /**
     * @return HasMany
     */
    public function postComments(): HasMany
    {
        return $this->hasMany(PostComment::class)->orderBy('id', 'DESC');
    }

    /**
     * @return string
     */
    public function getDisplayStatusAttribute(): string
    {
        return self::STATUES[$this->status];
    }

    /**
     * @return ?string
     */
    public function getVideoUrlAttribute(): ?string
    {
        return !empty($this->media_path) ? url('storage/' . $this->media_path) : null;
    }

    /**
     * @param PostData $postData
     *
     * @return Post
     */
    public static function persistPost(PostData $postData): Post
    {
        return Post::create([
            'title' => $postData->title,
            'post_body' => $postData->post_body,
            'status' => self::CREATED,
            'user_id' => $postData->user_id,
        ]);
    }

    /**
     * @param PostData $postData
     *
     * @return bool
     */
    public function updatePost(PostData $postData): bool
    {
        return $this->update([
            'title' => $postData->title,
            'post_body' => $postData->post_body,
        ]);
    }
}
