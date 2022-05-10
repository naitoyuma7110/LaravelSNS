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
            'body' => 'required|max:500'
        ];
    }

    // バリデーションエラーのメッセージ内容の変更
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '本文'
        ];
    }
}
