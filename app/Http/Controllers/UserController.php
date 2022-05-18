<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // ユーザーページの投稿した記事タブを選択した際のリクエスト
    public function show(string $name)
    {

        // Elequentの検索メソッドの結果はコレクション型のため、単数で使用するためにfirst()で取り出す
        $user = User::where('name', $name)->first();

        // このユーザーが投稿した記事を作成順にコレクションで取得する
        $articles = $user->articles->sortByDesc('created_at');

        // タブ毎に表示するBlade自体を分ける
        return view('users.show', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    // ユーザーページのいいねした記事タブを選択した際のリクエスト
    public function likes(string $name)
    {
        // userモデルを取得
        $user = User::where('name', $name)->first();

        // リレーションからいいねした記事を新しい順にコレクション型で取得
        $articles = $user->likes->sortByDesc('created_at');

        // タブ毎に表示するBlade自体を分ける
        return view('users.likes', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    public function follow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        // フォローしたユーザーとフォロー対象が一致した場合
        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }


        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        // vueコンポーネントからの非同期リクエストに対するレスポンス(自動でJson形式)
        return ['name' => $name];
    }

    public function unfollow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);

        return ['name' => $name];
    }
}
