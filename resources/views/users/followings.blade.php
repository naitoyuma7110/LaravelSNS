@extends('app')

@section('title', $user->name . 'のフォロー中')

@section('content')
  @include('nav')
  <div class="container">
    @include('users.user')
    {{-- どちらも選択していない状態としてタブを呼び出す --}}
    @include('users.tabs', ['hasArticles' => false, 'hasLikes' => false])
    @foreach($followings as $person)
      @include('users.person')
    @endforeach
  </div>
@endsection
