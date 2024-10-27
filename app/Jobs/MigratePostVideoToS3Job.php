<?php

namespace App\Jobs;

use App\Features\Posts\Domains\Models\Post;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class MigratePostVideoToS3Job implements ShouldQueue
{
    use Queueable;

    public int $postId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $postId)
    {
        $this->postId = $postId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $post = Post::find($this->postId);

        try {
            if (Storage::disk('public')->exists($post->media_path)) {
                $fileContents = Storage::disk('public')->get($post->media_path);
                if (!empty($fileContents)) {
                    $response = Storage::disk('s3')->put($post->media_path, $fileContents);
                    if ($response) {
                        Storage::disk('public')->delete($post->media_path);
                    } else {
                        throw new Exception("Not Moved: {$post->media_path} to R2 as {$post->media_path}");
                    }
                } else {
                    throw new Exception("File not found: {$post->media_path}");
                }
            } else {
                throw new Exception("Video File Not Exists");
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
