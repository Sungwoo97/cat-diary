<?php
// 글-태그 연결 테이블: 글 하나에 태그 여러 개 가능 (다대다 관계)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']); // 중복 연결 방지
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
