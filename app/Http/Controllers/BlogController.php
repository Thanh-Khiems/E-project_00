<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $featuredBlog = Blog::published()->where('is_featured', true)->latest('published_at')->first();

        $blogs = Blog::published()
            ->when($featuredBlog, fn ($q) => $q->where('id', '!=', $featuredBlog->id))
            ->latest('published_at')
            ->paginate(9);

        return view('pages.blog.index', compact('blogs', 'featuredBlog'));
    }

    public function show(string $slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();

        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.blog.show', compact('blog', 'relatedBlogs'));
    }
}
