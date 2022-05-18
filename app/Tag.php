<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    // tagモデルがDB操作のメソッドを許可するカラムを指定する
    protected $fillable = [
        'name',
    ];


    // get...Attributeメソッド
    // $this->hashtagで呼び出す(hashtagというカラムがあるかのように呼び出せる)
    public function getHashtagAttribute(): string
    {
        return '#' . $this->name;
    }

    public function articles(): BelongsToMany
    {
        // Tagモデルからもarticleモデルにリレーションを作成
        return $this->belongsToMany('App\Article')->withTimestamps();
    }
}
