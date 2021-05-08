<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('complaint', function (Blueprint $table) {
            $table->increments('id');
            $table->string('complaint_type',50);
            $table->integer('resource_id')->index('complaint_resource_id');
            $table->integer('user_id')->index('complaint_user_id')->nullable();
            $table->text('message');
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
        Schema::dropIfExists('complaint');
    }
}
