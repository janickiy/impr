<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::create('imageables', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id');
            $table->foreign('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade');
            $table->morphs('imageable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('imageables');
        Schema::dropIfExists('images');
    }
}
