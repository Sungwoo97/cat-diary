<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Diary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'mood',
        'weather',
        'location',
        'photo',
        'diary_date',
        'likes',
    ];

    protected $casts = [
        'diary_date' => 'date',
    ];

    // 유저와의 관계
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}