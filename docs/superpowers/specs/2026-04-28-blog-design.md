# 블로그 설계 문서
작성일: 2026-04-28

## 개요
Laravel 12 + Supabase(PostgreSQL) 기반 멀티유저 블로그.
사용자마다 글을 작성하고, 공개 설정 시 전체 피드에 노출된다.
에디터는 Quill(WYSIWYG), 분류는 카테고리 + 태그를 지원한다.
구현 방식은 Blade 서버 렌더링 + 부분 AJAX(MVC + Partial AJAX).

---

## DB 구조

### posts (글)
| 컬럼 | 타입 | 설명 |
|---|---|---|
| id | bigint PK | 자동 증가 |
| user_id | bigint FK | 작성자 (users 테이블) |
| category_id | bigint FK nullable | 카테고리 (categories 테이블) |
| title | string | 글 제목 |
| content | longText | 글 본문 (Quill HTML) |
| cover_image | string nullable | 대표 이미지 경로 |
| visibility | enum(public, private) | 공개/비공개 |
| views | integer default 0 | 조회수 |
| created_at / updated_at | timestamp | 자동 관리 |

### categories (카테고리)
| 컬럼 | 타입 | 설명 |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK | 카테고리 소유자 |
| name | string | 표시 이름 (예: 일상) |
| slug | string | URL용 (예: daily) |

### tags (태그)
| 컬럼 | 타입 | 설명 |
|---|---|---|
| id | bigint PK | |
| name | string unique | 태그명 (예: Laravel) |
| slug | string unique | URL용 (예: laravel) |

### post_tag (글-태그 다대다 연결)
| 컬럼 | 타입 |
|---|---|
| post_id | bigint FK |
| tag_id | bigint FK |

---

## 페이지 구성

| URL | 설명 | 로그인 필요 |
|---|---|---|
| `/` | 공개 글 전체 피드 (최신순) | ✗ |
| `/write` | 글 작성 (Quill 에디터) | ✓ |
| `/post/{id}` | 글 상세 보기 | ✗ |
| `/post/{id}/edit` | 글 수정 | ✓ 본인만 |
| `/my` | 내 글 목록 (공개+비공개) | ✓ |
| `/category/{slug}` | 카테고리별 글 목록 | ✗ |
| `/tag/{slug}` | 태그별 글 목록 | ✗ |

---

## 라우트 설계

```php
// 공개 (비로그인 접근 가능)
GET  /                     PostController@index
GET  /post/{id}            PostController@show
GET  /category/{slug}      CategoryController@show
GET  /tag/{slug}           TagController@show

// 로그인 필요
GET  /write                PostController@create
POST /write                PostController@store
GET  /post/{id}/edit       PostController@edit
PUT  /post/{id}            PostController@update
DELETE /post/{id}          PostController@destroy
GET  /my                   PostController@my

// AJAX
POST /post/{id}/views      PostController@incrementViews
```

---

## 파일 구조

```
app/Http/Controllers/
├── AuthController.php       기존 유지
├── PostController.php       글 CRUD + 피드 + 내 글
├── CategoryController.php   카테고리별 글 목록
└── TagController.php        태그별 글 목록

app/Models/
├── Post.php
├── Category.php
└── Tag.php

database/migrations/
├── create_posts_table.php
├── create_categories_table.php
├── create_tags_table.php
└── create_post_tag_table.php

resources/views/
├── layouts/app.blade.php    블로그 공통 레이아웃 (티스토리 스타일)
├── home.blade.php           공개 피드
├── posts/
│   ├── create.blade.php     작성/수정 폼 (Quill 포함)
│   ├── show.blade.php       글 상세
│   └── my.blade.php         내 글 목록
├── categories/
│   └── show.blade.php
└── tags/
    └── show.blade.php
```

---

## 글 작성 흐름 (초보자용 설명)

```
1. 사용자가 /write 접속
2. PostController@create 실행 → create.blade.php 렌더링
3. 사용자가 Quill 에디터로 글 작성 후 제출
4. PostController@store 실행
   - 유효성 검사 (제목, 본문 필수)
   - posts 테이블에 글 저장
   - 태그 입력값을 파싱 → tags 테이블에 없으면 생성 → post_tag 연결
   - /post/{id} 로 이동
```

---

## 기술 결정

- **에디터**: Quill.js (CDN 사용, 별도 설치 불필요)
- **이미지 업로드**: 1단계에서는 대표 이미지만 서버 로컬 저장 (storage/public)
- **태그 입력**: 쉼표 구분 텍스트 입력 → 서버에서 파싱
- **조회수**: 글 상세 진입 시 AJAX POST로 증가 (새로고침 중복 방지)
- **카테고리**: 사용자별로 독립 관리 (A유저의 "일상"과 B유저의 "일상"은 별개)
