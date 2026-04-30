<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    // DB에 저장 허용할 컬럼 목록
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'cover_image',
        'visibility',
        'views',
    ];

    // 글 작성자 (users 테이블과 연결)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 카테고리 (categories 테이블과 연결)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 태그 목록 (post_tag 연결 테이블을 통해 다대다 관계)
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
