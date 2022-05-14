<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    // リクエスト対象（ここではArticlesデータベース）の更新を許可するかどうか
    // 例えば他人の記事の更新は許可しないなどの設定
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    // リクエスト内容（ここでは投稿記事）のバリデーションルール
    public function rules()
    {
        return [
            'title' => 'required|max:50',
            'body' => 'required|max:500',
            // Json形式のチェックと正規表現によるバリデーション:'/'とスペースを含まない
            'tags' => 'json|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u'
        ];
    }

    // バリデーションエラーのメッセージ内容の変更
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '本文',
            'tags' => 'タグ',
        ];
    }

    // Json形式で受け取ったタグ情報を整形する
    // passedValidationはバリデーション後に自動的に呼ばれるメソッド
    public function passedValidation()
    {
        // $thisはフォームリクエスト情報を持つこのクラス
        // $this->name属性で各値にアクセスできる
        // collectでコレクションに変換(便利なメソッドを使用できる拡張された配列)
        $this->tags = collect(json_decode($this->tags))
            // 5個以上のタグは最初の5個以外は削除
            ->slice(0, 5)
            // コレクションを$requestTagとしてmapメソッドに渡す
            // mapはコレクションの各配列に順次処理を行い、結果として得た新しい配列を返す
            ->map(function ($requestTag) {
                // 各配列からKey=textの値を取り出す
                return $requestTag->text;
            });
    }
}
