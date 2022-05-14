@csrf
<div class="md-form">
  <label>タイトル</label>
  {{-- editアクションで呼び出された際、元の投稿内容を表示する --}}
  {{-- $articleインスタンスを持つテンプレート(edit.blade)に呼び出された場合タイトルと記事を表示 --}}
  <input type="text" name="title" class="form-control" required value="{{ $article->title ?? old('title') }}">
</div>
{{-- vueコンポーネントの使用 --}}
{{-- このテンプレートはform内で呼び出されるが、ArticleTagsImportはpostメソッドでのリクエストに対応していない --}}
{{-- ここではコンポーネント内に隠しinputを新しく設置し、フォーム機能を持たせる --}}
<div class="form-group">
  <article-tags-input>
  </article-tags-input>
</div>
<div class="form-group">
  <label></label>
  <textarea name="body" required class="form-control" rows="16" placeholder="本文">{{ $article->body ?? old('body') }}</textarea>
</div>