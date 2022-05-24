<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// 他テーブルとの紐づけ(リレーション)のためDBとModelを紐づけるEloquentの継承クラスを読み込む
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        // $article->user;          Userモデルのインスタンスが返る
        // $article->user->name;    Userモデルのインスタンスのnameプロパティの値が返る
        // $article->user->hoge();  Userモデルのインスタンスのhogeメソッドの戻り値が返る
        // $article->user();        BelongsToクラスのインスタンスが返る
    }

    public function likes(): BelongsToMany
    {
        // いいねテーブルと記事の紐づけは多対多
        // 記事モデルに紐づくlikesテーブルの情報からUserモデルをコレクション(配列拡張)で得る
        // Articleテーブル(ID)->likesテーブル(article_id)-(user_id)->Userテーブル(id)
        // この場合、中間のlikesテーブルを第2引数へ記述する。これだけでArticle-Likes-Usersの紐づけ情報を参照できる。
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    // $article->isLikedBy(Auth::user())のようログイン中のUserモデルを渡す
    public function isLikedBy(?User $user): bool
    {
        //  thisは記事のid情報を持つarticleモデル
        // この時はっきり認識したが、モデルはカラム毎に作成されるもののようだ…

        // this->likesで記事idに紐付いたlikesカラム(article_idが外部キー)を取得し
        // その中に、渡されたUserモデルのidが含まれるかcount()で調べる
        // count()は一致するレコード数を返すが、メソッドの返り値の型宣言：boolによってTrue or Falseに変換される
        return $user
            ? (bool)$this->likes->where('id', $user->id)->count()
            : false;
    }

    // get...Attributeという形式の名前のメソッドをLaravelではアクセサと呼ぶ。
    // この命名規則に従うと $article->getCountLikesAttribute()ではなく
    // $article->count_likesで呼び出せる(getとAttributeを除くスネークケース+()なし)

    // articlesテーブルにはcount_likesというカラムはないが、まるてそうしたカラムがあるかのように
    // $article->count_likesといった呼び出し方ができるのがアクセサの特徴(get-attributeを除きスネークケース)
    public function getCountLikesAttribute(): int
    {
        // 記事のIDに対応したlilesテーブルを取得し数を取得（これがいいね数）
        return $this->likes->count();
    }

    public function tags(): BelongsToMany
    {
        // article-tags-tagのリレーションを作成
        // 本来は中間テーブルのarticle_tagを第2引数に記述する必要があるが今回は
        // 命名規則にのっとるため省略可能
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
}
