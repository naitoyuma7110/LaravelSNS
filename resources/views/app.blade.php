<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  {{-- CSRF token not foundエラーに対して↓を追加 --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
    @yield('title')
  </title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- Bootstrap core CSS -->
  {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"> --}}
  <!-- Material Design Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet"> 


  <!-- Bootstrap core CSS -->
  {{-- laravel-uiでpublic/app.cssにビルド --}}
  <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
  {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet"> --}}
  <!-- Material Design Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">


</head>

<body>

  {{-- Vueインスタンスのマウント設定el:#appにより各BladeでVueコンポーネントが使用可能となる --}}
  <div id="app">
    @yield('content')
  </div>

  {{-- Vue-componentの読み込み --}}
  {{-- mix():コンポーネントのビルド先であるlaravel/public/フォルダを参照するためのメソッド --}}
  {{-- またコンパイルされたjsファイルにIDを振り、ファイル更新の度にIDを変更し読み込む事で、キャッシュに残った古いjsファイルの使用を防ぐ --}}
  {{-- ↑webpack.mixにて.version()オプションを記述する必要あり --}}

  {{-- <script src="{{ url(mix('js/app.js')) }}"></script> --}}
  {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
  <script src="{{ asset('js/app.js') }}"></script>


  <!-- JQuery -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
  
  {{-- bootstrapはlaravel-uiでapp.jsにビルド --}}
  <!-- Bootstrap core JavaScript -->
  {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script> --}}
  
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js"></script>

</body>

</html>