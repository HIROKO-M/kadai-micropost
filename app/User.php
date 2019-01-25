<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function microposts(){
        return $this->hasMany(Micropost::class);
    }


    // User がフォローしている User 達
    public function followings(){
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    // User をフォローしている User 達
    public function followers(){
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }


    // フォロー（中間テーブルへ保存）メソッドを定義
    public function follow($userId){
        
        $exist = $this->is_following($userId);      // 既にフォローしているかの確認
        $its_me = $this->id == $userId;             // 自分自身ではないかの確認
        
        if($exist || $its_me){                      // 既にフォローしている? or 自分自身?
            return false;                           // 既にフォローしていれば何もしない
        }
        else{
            $this->followings()->attach($userId);   // 未フォローであればフォローする
            return true;
        }
    }
    
    // アンフォロー（中間テーブルから削除）メソッドを定義
    public function unfollow($userId){
        
        $exist = $this->is_following($userId);      // 既にフォローしているかの確認
        $its_me = $this->id == $userId;             // 自分自身ではないかの確認
        
        if($exist && !$its_me){                     // 既にフォローしている? and それは自分自身でない?
            $this->followings()->detach($userId);   // 既にフォローしてあればフォローをはずす
            return true;
        }
        else{
            return false;                           // 未フォローであれば何もしない
        }
    }
    
    // User がフォローしている User 達(followings())の中から'follow_id'が存在する$userIdを抽出 ??
    public function is_following($userId){
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
}
