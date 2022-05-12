@extends('app')

@section('title', '記事更新')

@include('nav')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card mt-3">
          <div class="card-body pt-0">
            @include('error_card_list')
            <div class="card-text">
              
              {{-- 個別記事のページに対応したidをもつArticleモデルを更新処理のアクションに渡し、ルート名を使ってリダイレクトする --}}
              {{-- この時Update処理のRouteは('/article/{article}')であるが、第2引数にidをもつarticleモデルを指定することで更新対象を指定する --}}
              {{-- モデルからidを取得する際は$article->idとするが、モデル自体を渡してもLaravelはURLを作成してくれる --}}
              <form method="POST" action="{{ route('articles.update', ['article' => $article]) }}">
                
                {{-- HTMLはget/post以外のメソッドをサポートしていないため、FormタグのPOSTメソッドとしつつ --}}
                {{-- @method()でルーティングメソッドを指定する --}}
                {{-- ↓のタグがLaravelによって作成され、Laravel側はValue:"PATCH"を受けルーティングが行われる --}}
                {{-- <input type="hidden" name="_method" value="PATCH"> --}}
                @method('PATCH')

                {{-- 投稿と同じフォームを使用する --}}
                @include('articles.form')
                <button type="submit" class="btn blue-gradient btn-block">更新する</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection