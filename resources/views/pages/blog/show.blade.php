@extends('layouts.app')

@section('title', $blog->title . ' | MediConnect')

@section('content')
    <section class="blog-detail-section">
        <div class="container blog-detail-layout">
            <article class="blog-detail-card">
                <div class="blog-detail-thumb-wrap">
                    <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}" class="blog-detail-thumb">
                </div>

                <div class="blog-detail-body">
                    <div class="blog-meta">{{ $blog->published_at?->format('d/m/Y H:i') ?? 'Recently updated' }}</div>
                    <h1>{{ $blog->title }}</h1>
                    @if($blog->excerpt)
                        <p class="blog-detail-excerpt">{{ $blog->excerpt }}</p>
                    @endif

                    <div class="blog-detail-content">
                        {!! nl2br(e($blog->content)) !!}
                    </div>
                </div>
            </article>

            <aside class="blog-sidebar-card">
                <h3>Related articles</h3>
                <div class="blog-sidebar-list">
                    @forelse($relatedBlogs as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="blog-side-item">
                            <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}">
                            <div>
                                <strong>{{ $related->title }}</strong>
                                <span>{{ $related->published_at?->format('d/m/Y') ?? 'Recently updated' }}</span>
                            </div>
                        </a>
                    @empty
                        <p>There are no related articles.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </section>
@endsection
