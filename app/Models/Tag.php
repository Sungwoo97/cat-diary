<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // 이 태그가 붙은 글 목록 (post_tag 연결 테이블을 통해 다대다 관계)
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
