<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('postal_code', 8)->nullable(); // 例: 123-4567
            $table->string('address')->nullable();
            $table->string('building')->nullable();
            $table->string('icon_path')->nullable(); // 画像パス（保存方法は後で確定）
            $table->timestamps();

            $table->unique('user_id'); // ユーザーにプロフィールは1つ
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
