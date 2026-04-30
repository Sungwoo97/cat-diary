<?php
// 카테고리 테이블: 사용자마다 자신의 카테고리를 관리

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // 이 카테고리를 만든 사용자 (users 테이블과 연결)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');     // 표시 이름 (예: 일상)
            $table->string('slug');     // URL용 이름 (예: daily)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
