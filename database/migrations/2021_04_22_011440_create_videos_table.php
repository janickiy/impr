<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('videoalbum_id')->index('videos_videoalbum_id');
            $table->string('src');
            $table->json('properties')->nullable();
            $table->integer('user_id')->index('videos_user_id');
            $table->binary('preview1')->nullable();
            $table->binary('preview2')->nullable();
            $table->binary('preview3')->nullable();
            $table->timestamp('moderate_at')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
