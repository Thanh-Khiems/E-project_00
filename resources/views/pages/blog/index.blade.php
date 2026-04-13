@extends('layouts.app')

@section('title', 'Blog | MediConnect')

@section('content')
    <section class="blog-page-hero">
        <div class="container blog-page-hero-inner">
            <div>
                <span class="blog-kicker">MediConnect Blog</span>
                <h1>Cẩm nang sức khỏe &amp; tin tức y tế</h1>
                <p>Khám phá những bài viết mới nhất về chăm sóc sức khỏe, mẹo phòng bệnh và cập nhật nổi bật từ đội ngũ MediConnect.</p>
            </div>
        </div>
    </section>

    <section class="blog-page-section">
        <div class="container">
            @if($featuredBlog)
                <a href="{{ route('blog.show', $featuredBlog->slug) }}" class="featured-blog-card">
                    <div class="featured-blog-content">
                        <span class="featured-badge">Bài viết nổi bật</span>
                        <h2>{{ $featuredBlog->title }}</h2>
                        <p>{{ $featuredBlog->excerpt_text }}</p>
                        <span class="featured-readmore">Đọc bài viết</span>
                    </div>
                    <div class="featured-blog-image-wrap">
                        <img src="{{ $featuredBlog->thumbnail_url }}" alt="{{ $featuredBlog->title }}" class="featured-blog-image">
                    </div>
                </a>
            @endif

           @if($featuredBlog || $blogs->count())
    @if($blogs->count())
        <div class="news-grid blog-page-grid">
            @foreach($blogs as $blog)
                <article class="news-card">
                    <div class="news-image-wrap">
                        <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}" class="news-image">
                    </div>
                    <div class="news-content">
                        <div class="blog-meta">{{ $blog->published_at?->format('d/m/Y') ?? 'Mới cập nhật' }}</div>
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ $blog->excerpt_text }}</p>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="news-btn">Đọc thêm</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="blog-pagination-wrap">
            {{ $blogs->links() }}
        </div>
    @endif
@else
    <div class="empty-blog-state">Chưa có blog nào được đăng.</div>
@endif
        </div>
    </section>
@endsection
