<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 32)->unique()->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->date('birthday')->nullable();
            $table->string('password', 100)->nullable();
            $table->integer('gender')->default(User::GENDER_MALE);
            $table->timestamp('birthdate')->nullable();
            $table->jsonb('contacts')->nullable();
            $table->jsonb('settings')->nullable();
            $table->rememberToken();
            $table->timestamp('banned_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
