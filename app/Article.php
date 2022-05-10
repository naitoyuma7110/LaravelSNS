<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// 他テーブルとの紐づけ(リレーション)のためDBとModelを紐づけるEloquentの継承クラスを読み込む
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{

    protected $fillable = [
        'title',
        'body',
    ];



    // 関数()：型  で関数の返り値を宣言できる。この場合はBelongToオブジェクト
    public function user(): BelongsTo
    {

        /*リレーションの実行
        一人のユーザーが複数の記事を書くこともあるため、記事とユーザーとの紐づけは多対1
        この場合紐づけはbelongTo(モデル名)を使う
        belongTo()はarticleテーブルの外部キーを元に、紐づけ先のモデルのリレーションクラスを返す
        この場合はuserモデルのBelongToクラス*/
        return $this->belongsTo('App\User');
        // リレーション元のPrimaryキーの外部キーが、リレーション先の単数形_idとなっている場合この構文で可能

        // 注意：リレーション先のModelを受け取る場合 Article->user
    }
}
