@php
    $blogs = $blogs
        ?? $featuredBlogs
        ?? \App\Models\Blog::featuredWithHardcoded(3);
@endphp

<section class="news-section">
    <div class="container">
        <div class="section-heading" data-reveal="zoom" data-delay="0">
            <h2>Featured News at MediConnect</h2>
            <span class="section-line"></span>
            <p class="news-subtitle">
                Featured blog posts published by the admin will appear here for everyone to follow quickly.
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
                            <div class="blog-meta">{{ $blog->published_at?->format('d/m/Y') ?? 'Recently updated' }}</div>
                            <h3>{{ $blog->title }}</h3>
                            <p>{{ $blog->excerpt_text }}</p>

                            <a href="{{ route('blog.show', $blog->slug) }}" class="news-btn">View article</a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="blog-more-wrap">
                <a href="{{ route('blog.index') }}" class="blog-outline-btn">View all blogs</a>
            </div>
        @else
            <div class="empty-blog-state">There are no featured blog posts yet.</div>
        @endif
    </div>
</section>
