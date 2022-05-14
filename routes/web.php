<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ユーザー登録はLaravel-uiを使うとコマンド一発だが今回は自作
// ↓の記述でユーザー登録(Authorize:認証)に関するLaravelのデフォルトのアクション、ルーティングが登録される
Auth::routes();

// 個別のルーティング、コントローラーの記述
// ルート名を追加したデフォルトのArticleに合わせる
Route::get('/', 'ArticleController@index')->name('articles.index');

// 一覧,個別表示,新規登録,更新,削除機能のデフォルトルーティング、アクションの登録
// ↑の個別で作製したアクションと重複するのでexceptメソッドで無効にする
// またMiddlewareのAuthによるルート制限を設定し、URL入力による未ログイン者のアクセスを防ぐ
Route::resource('/articles', 'ArticleController')->except(['index', 'show'])->middleware('auth');

// ↑で除いたshowアクションをAuthを適応せず使用するため、showのみ再び定義
Route::resource('/articles', 'ArticleController')->only(['show']);



// いいねボタンのルーティング
// prefixで引数をURLに付加しnameでURL名を'article/'に指定
// group()はそこまでの一連の処理を無名関数として、後述のput/deleteのlikeとunlikeメソッドの登録コードを短縮している

Route::prefix('articles')->name('articles.')->group(function () {
  // put/deleteメソッドで'article(prefix付加部分)/{article(記事id)/like}'へリクエストを送る事でlike/unlikeアクション
  Route::put('/{article}/like', 'ArticleController@like')->name('like')->middleware('auth');
  Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike')->middleware('auth');
});
