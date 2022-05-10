{{-- app.blade.phpの継承 --}}
@extends('app')

{{-- @yield('title')部分を指定 --}}
@section('title', '記事一覧')

{{-- 'content'部分のテンプレートを記述 --}}
@section('content')
  {{-- content内でナビーゲーションを読み込む --}}
  @include('nav')
  <div class="container">
    @foreach ($articles as $article)
    <div class="card mt-3">
      <div class="card-body d-flex flex-row">
        <i class="fas fa-user-circle fa-3x mr-1"></i>
        <div>
          <div class="font-weight-bold">
            {{ $article->user->name }}
          </div>
          <div class="font-weight-lighter">
            {{ $article->created_at->format('Y/m/d H:i') }}
          </div>
        </div>
      </div>
      <div class="card-body pt-0 pb-2">
        <h3 class="h4 card-title">
          {{ $article->title }}
        </h3>
        <div class="card-text">
          {{-- nl2br:改行コード --}}
          {{ nl2br(e( $article->body ))  }}
        </div>
      </div>
    </div>
    @endforeach
  </div>
@endsection