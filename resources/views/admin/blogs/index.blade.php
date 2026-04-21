@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Total blogs', 'value' => $stats['total'], 'icon' => 'bi-journal-text'],
        ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'bi-eye'],
        ['label' => 'Draft', 'value' => $stats['draft'], 'icon' => 'bi-file-earmark'],
        ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'bi-stars'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Blog list</h5>
                <p>Manage articles, thumbnail images, and homepage display status.</p>
            </div>
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#blogCreateForm">+ Create blog</button>
        </div>

        <div class="collapse mb-4 {{ $errors->any() ? 'show' : '' }}" id="blogCreateForm">
            <div class="info-card">
                <h5 class="mb-3">Create new blog</h5>
                <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="de-trong-se-tu-tao-tu-dong">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Thumbnail image</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="published">Publish now</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Publish date <small class="text-muted">(leave blank or choose a future date to publish immediately)</small></label>
                        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Short description</label>
                        <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Message</label>
                        <textarea name="content" class="form-control" rows="8" required>{{ old('content') }}</textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured">
                            <label class="form-check-label" for="is_featured">Show as featured</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Save blog</button>
                    </div>
                </form>
            </div>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-10">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Search by title, description, or blog content...">
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Search</button></div>
        </form>

        <div class="row g-4 mt-1">
            @forelse($blogs as $blog)
                <div class="col-md-6 col-xl-4">
                    <div class="info-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                            <div class="icon-soft"><i class="bi bi-journal-richtext"></i></div>
                            <span class="status-badge {{ $blog->status === 'published' ? 'active' : 'inactive' }}">
                                {{ $blog->status === 'published' ? 'Published' : 'Draft' }}
                            </span>
                        </div>

                        <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}" class="img-fluid rounded-4 mb-3" style="height: 180px; width: 100%; object-fit: cover;">

                        <h5>{{ $blog->title }}</h5>
                        <p>{{ $blog->excerpt_text }}</p>

                        <div class="meta-grid">
                            <div>
                                <strong>{{ $blog->published_at?->format('d/m/Y') ?? '--/--/----' }}</strong>
                                <span>Publish date</span>
                            </div>
                            <div>
                                <strong>{{ $blog->is_featured ? 'Yes' : 'No' }}</strong>
                                <span>Featured</span>
                            </div>
                        </div>

                        <div class="card-actions mt-3 d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editBlog{{ $blog->id }}">Edit</button>
                            <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Are you sure you want to delete this blog?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>

                        <div class="collapse mt-3" id="editBlog{{ $blog->id }}">
                            <div class="border-top pt-3">
                                <h6 class="mb-3">Edit blog</h6>
                                <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-12">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ $blog->title }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Slug</label>
                                        <input type="text" name="slug" class="form-control" value="{{ $blog->slug }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Current thumbnail image</label>
                                        <div class="mb-2">
                                            <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}" class="img-fluid rounded-3 border" style="max-height: 180px; object-fit: cover; width: 100%;">
                                        </div>
                                        <label class="form-label">New thumbnail image</label>
                                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="draft" @selected($blog->status === 'draft')>Draft</option>
                                            <option value="published" @selected($blog->status === 'published')>Published</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Publish date <small class="text-muted">(if set in the future, the system will change it to the current time so the post appears immediately)</small></label>
                                        <input type="datetime-local" name="published_at" class="form-control" value="{{ $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Short description</label>
                                        <textarea name="excerpt" class="form-control" rows="2">{{ $blog->excerpt }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Message</label>
                                        <textarea name="content" class="form-control" rows="8" required>{{ $blog->content }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured_{{ $blog->id }}" @checked($blog->is_featured)>
                                            <label class="form-check-label" for="featured_{{ $blog->id }}">Show as featured</label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex gap-2">
                                        <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#editBlog{{ $blog->id }}">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="empty-box">There are no blogs yet.</div></div>
            @endforelse
        </div>

        <div class="mt-4">{{ $blogs->links() }}</div>
    </div>
@endsection
