<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id');
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');
            $table->morphs('taggable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
    }
}
