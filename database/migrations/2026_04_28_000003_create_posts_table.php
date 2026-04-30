<?php
// 글 테이블: 블로그의 핵심 테이블

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            // 작성자 (users 테이블과 연결, 사용자 삭제 시 글도 삭제)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // 카테고리 (없을 수도 있으므로 nullable)
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');                                            // 글 제목
            $table->longText('content');                                        // 본문 (Quill HTML)
            $table->string('cover_image')->nullable();                          // 대표 이미지 경로
            $table->enum('visibility', ['public', 'private'])->default('public'); // 공개/비공개
            $table->unsignedInteger('views')->default(0);                       // 조회수
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
