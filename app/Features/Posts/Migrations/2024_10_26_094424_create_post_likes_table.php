<?php

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
        Schema::create('post_likes', function (Blueprint $table) {
            $table->id();
            $table->uuid('identifier');
            $table->foreignIdFor(User::class)->constrained('users')->onDelete('cascade')->onDelete('cascade');
            $table->morphs('likeable');
            $table->tinyInteger('is_liked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_likes');
    }
};
