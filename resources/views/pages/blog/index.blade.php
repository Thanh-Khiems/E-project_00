@extends('layouts.app')

@section('title', 'Blog | MediConnect')

@section('content')
    <section class="blog-page-hero">
        <div class="container blog-page-hero-inner">
            <div>
                <span class="blog-kicker">MediConnect Blog</span>
                <h1>Health Guide &amp; Medical News</h1>
                <p>Explore the latest articles on healthcare, prevention tips, and notable updates from the MediConnect team.</p>
            </div>
        </div>
    </section>

    <section class="blog-page-section">
        <div class="container">
            @if($featuredBlog)
                <a href="{{ route('blog.show', $featuredBlog->slug) }}" class="featured-blog-card">
                    <div class="featured-blog-content">
                        <span class="featured-badge">Featured article</span>
                        <h2>{{ $featuredBlog->title }}</h2>
                        <p>{{ $featuredBlog->excerpt_text }}</p>
                        <span class="featured-readmore">Read article</span>
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
                        <div class="blog-meta">{{ $blog->published_at?->format('d/m/Y') ?? 'Recently updated' }}</div>
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ $blog->excerpt_text }}</p>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="news-btn">Read more</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="blog-pagination-wrap">
            {{ $blogs->links() }}
        </div>
    @endif
@else
    <div class="empty-blog-state">No blog posts have been published yet.</div>
@endif
        </div>
    </section>
@endsection
