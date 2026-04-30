<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'slug'];

    // 카테고리 소유자 (users 테이블과 연결)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 이 카테고리에 속한 글 목록
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
