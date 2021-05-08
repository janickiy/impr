<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('hashtag');
            $table->boolean('is_commentable')->default(false);
            $table->integer('gender')->default(User::GENDER_UNISEX);
            $table->boolean('is_paid')->default(false);
            $table->float('amount')->default(0);
            $table->bigInteger('shows')->default(0);
            $table->unsignedBigInteger('video_id');
            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
}
