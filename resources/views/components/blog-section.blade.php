<section class="news-section">
    <div class="container">
        <div class="section-heading" data-reveal="zoom" data-delay="0">
            <h2>Latest Health Blog Posts</h2>
            <span class="section-line"></span>
            <p class="news-subtitle">
                Featured articles, insights, and updates from MediConnect for patients and families.
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
                            <div class="blog-meta">{{ $blog->published_at?->format('d/m/Y') ?? 'Recently updated' }}</div>
                            <h3>{{ $blog->title }}</h3>
                            <p>{{ $blog->excerpt_text }}</p>
                            @auth
                                <a href="{{ route('blog.show', $blog->slug) }}" class="news-btn">Read more</a>
                            @else
                                <a href="{{ route('login', ['auth_required' => 1, 'redirect' => route('blog.show', $blog->slug)]) }}" class="news-btn auth-locked" title="Please log in or register to continue">Read more</a>
                            @endauth
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="blog-more-wrap">
                <a href="{{ route('blog.index') }}" class="blog-outline-btn">View all blogs</a>
            </div>
        @else
            <div class="empty-blog-state">There are no published blog posts yet.</div>
        @endif
    </div>
</section>
