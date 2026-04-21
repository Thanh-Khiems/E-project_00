<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($builder) use ($keyword) {
                $builder->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('excerpt', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%');
            });
        }

        $blogs = $query->latest()->paginate(9)->withQueryString();

        return view('admin.blogs.index', [
            'pageTitle' => 'Blog management',
            'blogs' => $blogs,
            'stats' => [
                'total' => Blog::count(),
                'published' => Blog::where('status', Blog::STATUS_PUBLISHED)->count(),
                'draft' => Blog::where('status', Blog::STATUS_DRAFT)->count(),
                'featured' => Blog::where('is_featured', true)->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateBlog($request);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->storeThumbnail($request->file('thumbnail'));
        }

        $validated = $this->normalizePublishPayload($validated);

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'New blog created successfully.');
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $this->validateBlog($request, $blog);

        if ($request->hasFile('thumbnail')) {
            $this->deleteThumbnail($blog->thumbnail);
            $validated['thumbnail'] = $this->storeThumbnail($request->file('thumbnail'));
        }

        $validated = $this->normalizePublishPayload($validated, $blog);

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $this->deleteThumbnail($blog->thumbnail);

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted.');
    }

    protected function validateBlog(Request $request, ?Blog $blog = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug')->ignore($blog?->id)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', Rule::in([Blog::STATUS_DRAFT, Blog::STATUS_PUBLISHED])],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        return [
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug'] ?? $validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'status' => $validated['status'],
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'published_at' => $validated['published_at'] ?? null,
        ];
    }

    protected function normalizePublishPayload(array $validated, ?Blog $blog = null): array
    {
        if ($validated['status'] === Blog::STATUS_DRAFT) {
            $validated['published_at'] = null;

            return $validated;
        }

        $publishedAt = isset($validated['published_at']) && filled($validated['published_at'])
            ? Carbon::parse($validated['published_at'])
            : ($blog?->published_at ?? now());

        if ($publishedAt->isFuture()) {
            $publishedAt = now();
        }

        $validated['published_at'] = $publishedAt;

        return $validated;
    }

    protected function storeThumbnail(UploadedFile $thumbnail): string
    {
        $directory = public_path('uploads/blogs');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = 'blog_' . now()->format('YmdHis') . '_' . Str::random(8) . '.' . $thumbnail->getClientOriginalExtension();

        $thumbnail->move($directory, $filename);

        return 'uploads/blogs/' . $filename;
    }

    protected function deleteThumbnail(?string $thumbnailPath): void
    {
        if (blank($thumbnailPath)) {
            return;
        }

        $normalizedPath = ltrim($thumbnailPath, '/');

        $publicCandidates = [
            public_path($normalizedPath),
            public_path(Str::startsWith($normalizedPath, 'storage/') ? $normalizedPath : 'storage/' . $normalizedPath),
        ];

        foreach ($publicCandidates as $candidate) {
            if (File::exists($candidate) && File::isFile($candidate)) {
                File::delete($candidate);
            }
        }

        $storageCandidates = [$normalizedPath];

        if (Str::startsWith($normalizedPath, 'storage/')) {
            $storageCandidates[] = Str::after($normalizedPath, 'storage/');
        }

        foreach ($storageCandidates as $candidate) {
            if (Storage::disk('public')->exists($candidate)) {
                Storage::disk('public')->delete($candidate);
            }
        }
    }
}
