<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // ── 공개 피드 (메인 페이지) ────────────────────────────────
    public function index()
    {
        $page    = request()->get('page', 1);
        $version = Cache::get('home_feed_version', 1);
        $cacheKey = "home.feed.v{$version}.page.{$page}";

        $posts = Cache::remember($cacheKey, 3600, function () {
            return Post::with(['user', 'category', 'tags'])
                ->select([
                    'id', 'user_id', 'category_id', 'title',
                    'cover_image', 'visibility', 'views',
                    'created_at', 'updated_at',
                    DB::raw('LEFT(content, 300) as content'),
                ])
                ->where('visibility', 'public')
                ->latest()
                ->paginate(12);
        });

        return view('home', compact('posts'));
    }

    // ── 글 작성 폼 ─────────────────────────────────────────────
    // 로그인한 사용자의 카테고리 목록도 함께 전달 (폼에서 선택하도록)
    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('posts.create', compact('categories'));
    }

    // ── 글 저장 ────────────────────────────────────────────────
    public function store(Request $request)
    {
        // 유효성 검사: 제목과 본문은 반드시 입력해야 함
        $request->validate([
            'title'       => 'required|max:255',
            'content'     => 'required',
            'visibility'  => 'required|in:public,private',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        // 대표 이미지가 있으면 storage/public/covers 폴더에 저장
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        // posts 테이블에 글 저장
        $post = Post::create([
            'user_id'     => Auth::id(),
            'category_id' => $request->category_id ?: null,
            'title'       => $request->title,
            'content'     => $request->content,
            'cover_image' => $coverPath,
            'visibility'  => $request->visibility,
        ]);

        $this->syncTags($post, $request->tags ?? '');
        Cache::increment('home_feed_version');

        return redirect()->route('posts.show', $post)->with('success', '글이 등록됐습니다.');
    }

    // ── 글 상세 보기 ───────────────────────────────────────────
    public function show(Post $post)
    {
        // 비공개 글은 작성자 본인만 볼 수 있음
        if ($post->visibility === 'private' && $post->user_id !== Auth::id()) {
            abort(403, '비공개 글입니다.');
        }

        // 태그 정보도 함께 불러옴
        $post->load(['user', 'category', 'tags']);

        return view('posts.show', compact('post'));
    }

    // ── 글 수정 폼 ─────────────────────────────────────────────
    public function edit(Post $post)
    {
        // 본인 글이 아니면 403 에러 (권한 없음)
        abort_if($post->user_id !== Auth::id(), 403);

        $categories = Category::where('user_id', Auth::id())->get();
        return view('posts.create', compact('post', 'categories'));
    }

    // ── 글 수정 저장 ───────────────────────────────────────────
    public function update(Request $request, Post $post)
    {
        abort_if($post->user_id !== Auth::id(), 403);

        $request->validate([
            'title'       => 'required|max:255',
            'content'     => 'required',
            'visibility'  => 'required|in:public,private',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        // 새 이미지가 올라오면 기존 이미지 삭제 후 새로 저장
        $coverPath = $post->cover_image;
        if ($request->hasFile('cover_image')) {
            if ($coverPath) Storage::disk('public')->delete($coverPath);
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $post->update([
            'category_id' => $request->category_id ?: null,
            'title'       => $request->title,
            'content'     => $request->content,
            'cover_image' => $coverPath,
            'visibility'  => $request->visibility,
        ]);

        $this->syncTags($post, $request->tags ?? '');
        Cache::increment('home_feed_version');

        return redirect()->route('posts.show', $post)->with('success', '글이 수정됐습니다.');
    }

    // ── 글 삭제 ────────────────────────────────────────────────
    public function destroy(Post $post)
    {
        abort_if($post->user_id !== Auth::id(), 403);

        // 대표 이미지도 함께 삭제
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->delete();
        Cache::increment('home_feed_version');

        return redirect()->route('posts.my')->with('success', '글이 삭제됐습니다.');
    }

    // ── 내 글 목록 ─────────────────────────────────────────────
    // 공개/비공개 모두 보여줌 (본인만 접근 가능)
    public function my()
    {
        $posts = Post::with(['category', 'tags'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('posts.my', compact('posts'));
    }

    // ── AJAX: 조회수 증가 ──────────────────────────────────────
    // 글 상세 페이지 진입 시 JavaScript에서 자동 호출됨
    public function incrementViews(Post $post)
    {
        $post->increment('views');
        return response()->json(['views' => $post->views]);
    }

    // ── 태그 동기화 (내부 전용 메서드) ────────────────────────
    // "Laravel, PHP, 일상" 같은 문자열을 받아서
    // tags 테이블에 없으면 새로 추가하고, post_tag 테이블에 연결
    private function syncTags(Post $post, string $tagString): void
    {
        if (empty(trim($tagString))) {
            $post->tags()->detach(); // 태그 전부 제거
            return;
        }

        $tagIds   = [];
        $tagNames = array_filter(array_map('trim', explode(',', $tagString)));

        foreach ($tagNames as $name) {
            // "Laravel PHP" → "laravel-php" 형태의 slug 생성
            $slug = Str::slug($name);
            if (empty($slug)) continue;

            // 이미 있는 태그면 가져오고, 없으면 새로 만듦
            $tag = Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'slug' => $slug]
            );
            $tagIds[] = $tag->id;
        }

        // post_tag 테이블 동기화 (기존 연결 제거 후 새로 연결)
        $post->tags()->sync($tagIds);
    }
}
