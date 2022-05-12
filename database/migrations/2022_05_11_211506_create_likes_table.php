<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('users')) {
            // テーブルが存在していればリターン
            return;
        }

        Schema::create('likes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id');

            // forgin:外部キー指定
            // references(id)->on(users):参照先はusersテーブルのidカラム
            // onDelete:参照先のテーブル(users)からidが削除された場合、対応するlikesテーブルのカラムも削除する
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');

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
        Schema::dropIfExists('likes');
    }
}
