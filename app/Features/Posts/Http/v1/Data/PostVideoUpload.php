<?php

namespace App\Features\Posts\Http\v1\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\MimeTypes;
use Spatie\LaravelData\Data;

class PostVideoUpload extends Data
{
    public function __construct(
        #[MimeTypes('video/mp4', 'video/avi')]
        public UploadedFile $video_file,
    ) {}
}
