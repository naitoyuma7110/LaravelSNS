<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tag;

// 作製したArticleRequestクラスをインポート
use App\Http\Requests\ArticleRequest;


use Illuminate\Http\Request;

class ArticleController extends Controller
{

    // __construct:クラスのインスタンス化時に初期処理として実行されるメソッド
    public function __construct()
    {
        // authorizeResource：リソースコントローラー(policy)の使用。
        // 第一引数にモデルのクラス名、第二引数にこのモデルがルーティング上でURLを作成する時に渡すIDパラメータ名(この場合'/article/{article}/'の{}の中身) 
        $this->authorizeResource(Article::class, 'article');
    }


    public function index()
    {
        // ダミーデータ
        // データベースから読み込む際、オブジェクト型で受け取るためそれに合わせる
        // （object）は型宣言、型キャスト
        $articles = [
            (object) [
                'id' => 1,
                'title' => 'タイトル1',
                'body' => '本文1',
                'created_at' => now(),
                'user' => (object) [
                    'id' => 1,
                    'name' => 'ユーザー名1',
                ],
            ],
            (object) [
                'id' => 2,
                'title' => 'タイトル2',
                'body' => '本文2',
                'created_at' => now(),
                'user' => (object) [
                    'id' => 2,
                    'name' => 'ユーザー名2',
                ],
            ],
            (object) [
                'id' => 3,
                'title' => 'タイトル3',
                'body' => '本文3',
                'created_at' => now(),
                'user' => (object) [
                    'id' => 3,
                    'name' => 'ユーザー名3',
                ],
            ],
        ];

        // N + 1問題
        // ここではArticlesテーブルを参照し全ての記事モデルを作成する(SQL＝1)
        // その後,index.bladeのcard.bladeで取得した記事モデルに対応するUserモデルをUsersテーブルから取得し作成している(SQL=n(ユーザー数))
        // 記事が1つ増える度、紐づくユーザーを所得するためのSQLがN回(ユーザー数)増えて処理が遅くなる
        // そこで全記事取得の際  ->load()を使用する事でリレーションを利用しそれぞれの記事に紐づく各モデルも同時に取得できる
        // 一度作成されたモデルはその後SQLで再度取得する必要がない
        $articles = Article::all()->sortByDesc('created_at')->load('user', 'likes', 'tags');

        return view('articles.index', ['articles' => $articles]);
    }

    public function create()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        // 登録されている全タグ名をコレクションに入れて渡す
        return view('articles.create', [
            'allTagNames' => $allTagNames,
        ]);
    }

    // 引数の型宣言：$requestはArticlesRequestクラスのインスタンス、$articleはArticleクラスのインスタンスを指定している。
    // ->引数の型を限定することでメソッドの意図しない動作を防ぐ

    // laravelコントローラーでは引数の型宣言を行うと、メソッドの外でインスタンスが自動生成される
    // メソッドを呼び出す側が引数を指定しなくても、インスタンスが渡せれる
    // そのためこの場合Articleの型宣言を行わない場合メソッドの中でインスタンス化する必要がある。
    // この状態はメソッドがクラスに依存していると表現され、テストや変更がしやすい。(メソッドの書き換えが必要ないから)
    public function store(ArticleRequest $request, Article $article)
    {
        // 以下のように個別でモデル-DBを更新しても良いがfillの使用が推奨される
        // $article->title = $request->title;
        // $article->body = $request->body;

        // fillメソッドを使用すると、DBの更新がArticleモデルで定義したfillableプロパティのパラメーター（この場合title,body）に限定される
        // model-DB側のパラメータとrequest情報のパラメータが一致している必要がある？ 
        $article->fill($request->all());

        // ArticleモデルとUserモデルは紐づけ済みであるため->user()でアクセスしidを取得している
        $article->user_id = $request->user()->id;
        $article->save();


        // ArticleRequestではタグ情報をコレクションに変換する処理を記述した
        // eachメソッド内の処理(コールバック関数)でスコープ外部で定義された$articleが使用できるようuse
        $request->tags->each(function ($tagName) use ($article) {
            // TagmモデルはEloquentモデルなのでどこでも呼び出せる
            // firstOrCreateではnameカラムに渡されたタグ名があればそのモデルを返す
            // また無ければcreateしてそのモデルを返す
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            // 取得したタグ名のモデルを使用し、紐づいているArticle_tagテーブル、Articleテーブルにattach
            $article->tags()->attach($tag);
        });


        return redirect()->route('articles.index');
    }


    // editアクションは'/article/id/edit'で呼び出されるが、このidをパラメータに持つarticleモデルが作製される
    public function edit(Article $article)
    {
        // コレクション型のtagsから名前のみを抽出して新たな配列(コレクションへ)
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        // article.bladeにarticleモデルとタグコレクションを渡す
        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        // form入力内容をArticleRequestインスタンスから取得し、モデルのfill関数でKeyチェックと保存
        $article->fill($request->all())->save();

        // fillで更新されるのはarticleモデルが担当するbody,titleのみ
        // tagテーブルの操作はリレーションで実行する

        // 一旦この記事とタグとの紐づけをすべて削除し、更新されたタグの名前で再度create
        $article->tags()->detach();
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }

    // putリクエスト
    public function like(Request $request, Article $article)
    {
        // detach/attach
        // $article->likes()で使用した多対多のリレーションで使用可能なDBのCreateメソッド
        // 呼び出し元(Article)の外部キー(多分)とdetach/attachの引数に指定したuserテーブルのid(likesテーブルの参照先だから？)を
        // 紐づけるlikesカラムを作成または削除する
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        // アクションの返り値はJson形式に自動変換され、クライアントへのレスポンスとなる
        // ここではクライアントに対して、いいねした記事idとその記事のいいね数を返す
        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    // deleteリクエスト
    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }
}
