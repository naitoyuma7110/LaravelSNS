<?php

namespace App\Http\Controllers;

// tagモデルを使用
use App\Tag;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(string $name)
    {
        // 受け取ったタグ名でテーブルを検索し最初に一致するカラムのモデル(同名のタグはないので唯一)
        $tag = Tag::where('name', $name)->first();

        return view('tags.show', ['tag' => $tag]);
    }
}
