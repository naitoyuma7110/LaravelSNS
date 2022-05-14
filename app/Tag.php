<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // tagモデルがDB操作のメソッドを許可するカラムを指定する
    protected $fillable = [
        'name',
    ];
}
