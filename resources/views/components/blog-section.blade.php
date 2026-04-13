<section class="news-section">
    <div class="container">
        <div class="section-heading" data-reveal="zoom" data-delay="0">
            <h2>Blog sức khỏe mới nhất</h2>
            <span class="section-line"></span>
            <p class="news-subtitle">
                Những bài viết, kiến thức và cập nhật nổi bật từ MediConnect dành cho bệnh nhân và gia đình.
            </p>
        </div>

        @if(($blogs ?? collect())->count())
            <div class="news-grid">
                @foreach($blogs as $index => $blog)
                    <article class="news-card" data-reveal="up" data-delay="{{ ($index + 1) * 100 }}">
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
            <div class="blog-more-wrap">
                <a href="{{ route('blog.index') }}" class="blog-outline-btn">Xem tất cả blog</a>
            </div>
        @else
            <div class="empty-blog-state">Hiện chưa có bài blog nào được đăng.</div>
        @endif
    </div>
</section>
