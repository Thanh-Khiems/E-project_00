@php
    $blogs = $blogs
        ?? $featuredBlogs
        ?? \App\Models\Blog::published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(3)
            ->get();
@endphp

<section class="news-section">
    <div class="container">
        <div class="section-heading" data-reveal="zoom" data-delay="0">
            <h2>Tin nổi bật tại MediConnect</h2>
            <span class="section-line"></span>
            <p class="news-subtitle">
                Những bài blog nổi bật do admin đăng sẽ hiển thị ở đây để mọi người đều có thể theo dõi nhanh.
            </p>
        </div>

        @if($blogs->count())
            <div class="news-grid">
                @foreach($blogs as $index => $blog)
                    <article class="news-card" data-reveal="up" data-delay="{{ ($index + 1) * 100 }}">
                        <div class="news-image-wrap">
                            <img
                                src="{{ $blog->thumbnail_url }}"
                                alt="{{ $blog->title }}"
                                class="news-image"
                            >
                        </div>

                        <div class="news-content">
                            <div class="blog-meta">{{ $blog->published_at?->format('d/m/Y') ?? 'Mới cập nhật' }}</div>
                            <h3>{{ $blog->title }}</h3>
                            <p>{{ $blog->excerpt_text }}</p>

                            @auth
                                <a href="{{ route('blog.show', $blog->slug) }}" class="news-btn">Xem bài viết</a>
                            @else
                                <span class="news-btn auth-locked" aria-disabled="true" title="Vui lòng đăng nhập hoặc đăng ký để tiếp tục">Xem bài viết</span>
                            @endauth
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="blog-more-wrap">
                <a href="{{ route('blog.index') }}" class="blog-outline-btn">Xem tất cả blog</a>
            </div>
        @else
            <div class="empty-blog-state">Hiện chưa có bài blog nổi bật nào được đăng.</div>
        @endif
    </div>
</section>
