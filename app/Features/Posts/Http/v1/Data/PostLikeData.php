<?php

namespace App\Features\Posts\Http\v1\Data;

use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Data;

class PostLikeData extends Data
{
    public function __construct(
        #[Between(0, 1)]
        public int $is_liked = 1,
        public ?int $user_id,
        public ?string $likeable_type,
        public ?int $likeable_id,
    ) {}


    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    /**
     * @param string $likeableType
     */
    public function setLikeableType(string $likeableType): void
    {
        $this->likeable_type = $likeableType;
    }

    /**
     * @param int $likeableId
     */
    public function setLikeableId(int $likeableId): void
    {
        $this->likeable_id = $likeableId;
    }
}
