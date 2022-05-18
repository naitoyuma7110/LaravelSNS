@extends('app')

@section('title', $user->name)

@section('content')
  @include('nav')
  <div class="container">

    @include('users.user')

    {{-- 記事といいねの切替タブ --}}
    {{-- includeの際にもパラムを渡せる --}}
    {{-- Bladeからの呼び出し方でタブのアクティブを使い分ける --}}
    @include('users.tabs', ['hasArticles' => true, 'hasLikes' => false])

    {{-- ユーザーが投稿した記事一覧 --}}
    {{-- アクションメソッドから受け取った投稿記事のコレクションから各記事内容をcard.bladeに渡す --}}
    @foreach($articles as $article)
      @include('articles.card')
    @endforeach
  </div>
@endsection
