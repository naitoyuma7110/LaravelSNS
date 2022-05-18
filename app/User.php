<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function followers(): BelongsToMany
    {
        // user同士が中間テーブルのfollowersテーブルを介して紐づくリレーション
        // Userテーブルと結びつくのはfollowsテーブルのfollowee_id(元)からfollower_id(先)
        // フォローされている側のユーザーモデルからフォローしているユーザーモデルにアクセス
        return $this->belongsToMany('App\User', 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    public function followings(): BelongsToMany
    {
        // followersとは逆
        // フォローしている側からフォロー相手のユーザーモデルにアクセス
        return $this->belongsToMany('App\User', 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }


    public function articles(): HasMany
    {
        // 投稿した記事(多)対ユーザー(一)の時はhasManyでリレーションを作成
        // users(id)-articles(user_id)
        // リレーション先のカラムがモデル名単数+_idであれば、引数は省略可能
        return $this->hasMany('App\Article');
    }

    public function likes(): BelongsToMany
    {
        // users(id)-likes(user_id)-likes(article_id)-articles(id)
        // 中間テーブルのlikesテーブルの'article_id'とuser_id'は名前条件に当てはまるので第3，4引数は省略可能
        return $this->belongsToMany('App\Article', 'likes')->withTimestamps();
    }

    // フォロワー数をBooleanに変換(いるか、いないか)
    public function isFollowedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->followers->where('id', $user->id)->count()
            : false;
    }


    public function getCountFollowersAttribute(): int
    {
        return $this->followers->count();
    }

    public function getCountFollowingsAttribute(): int
    {
        return $this->followings->count();
    }
}
