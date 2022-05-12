<?php

namespace App\Policies;

use App\Article;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;


// --model=Articleを指定しポリシーを作成したため、ArticleControllerの各メソッド名に対応したメソッドが用意されている

// ポリシーのメソッド    - コントローラーのアクションメソッド
// viewAny              - index
// view	                - show
// create	            - create, store
// update	            - edit, update
// destroy	            - destroy

// policyの使用では本来AuthServiceProviderに登録する必要があるが、以下の条件下に限りLaravelが自動検知する
// モデルがappディレクトリ配下にある
// ポリシーがapp/Policesディレクトリ配下にある
// ポリシー名がモデル名Policyという名前である



class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any articles.
     *
     * @param  \App\User  $user
     * @return mixed
     */

    // policy内のメソッドはUserモデルを引数に取る。このモデルはログイン済みユーザーのモデルであるため、未ログインの場合引数はNullとなる。
    // メソッド(?型 変数名)の形取ることで、引数がNullでも許容され、未ログインでもアクションが実行される
    public function viewAny(?User $user)
    {
        // policyのメソッドでtrueを返さないと、コントローラーの対応したメソッドは403ステータスコードを返す
        // 特に制限をかけない
        return true;
    }

    /**
     * Determine whether the user can view the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function view(?User $user, Article $article)
    {
        return true;
    }

    /**
     * Determine whether the user can create articles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // Delete,Updateは既にArticleモデルが作成されているが
        // Createはこれから作成される(まだ作成前)のため引数にモデルを受け取ることは出来ない。
        return true;
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */

    // ログイン済みユーザーのUserモデルと、選択した記事のArticleモデルを引数にとる
    public function update(User $user, Article $article)
    {
        // この2つが一致しない限りアクションを実行しないよう設定
        // これによりURLに直接入力する形でEditやDeleteを実行できない
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function delete(User $user, Article $article)
    {
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can restore the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function restore(User $user, Article $article)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function forceDelete(User $user, Article $article)
    {
        //
    }
}
