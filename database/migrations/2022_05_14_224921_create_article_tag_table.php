<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_tag', function (Blueprint $table) {
            // BigInteger:符号なしBIGINT
            // Unsigned:-128~127の正負整数から0~255に設定
            $table->bigIncrements('id');
            $table->bigInteger('article_id');
            // 外部キー制約
            // articleテーブル(id)-article_tagテーブル(article_id)-tagテーブル(id)の紐づけ
            $table->foreign('article_id')
                ->references('id')
                ->on('articles')
                ->onDelete('cascade');
            $table->bigInteger('tag_id');
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                // onDeleteにcascadeを設定すると、article,tag双方のカラムが削除された時、上記の紐づけに対応するカラムが削除される
                ->onDelete('cascade');
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
        Schema::dropIfExists('article_tag');
    }
}
