@extends('app')

@section('title', '記事一覧')

@section('content')
  @include('nav')
  <div class="container">
    @foreach($articles as $article)
      {{-- 投稿記事のcardはテンプレートを作成して読み込む --}}
      @include('articles.card')  
    @endforeach
  </div>
@endsection