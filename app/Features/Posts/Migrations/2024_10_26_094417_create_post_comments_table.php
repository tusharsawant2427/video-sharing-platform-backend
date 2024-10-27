<?php

use App\Features\Posts\Domains\Models\Post;
use App\Features\Users\Domains\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();
            $table->uuid('identifier');
            $table->foreignIdFor(Post::class)->constrained('posts')->onDelete('cascade')->onDelete('cascade');
            $table->foreignIdFor(User::class)->constrained('users');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_comments');
    }
};
