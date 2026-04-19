<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $featuredBlog = Blog::featuredWithHardcoded(1)->first();
        $blogs = Blog::paginatePublishedWithHardcoded(9, $featuredBlog?->slug);

        return view('pages.blog.index', compact('blogs', 'featuredBlog'));
    }

    public function show(string $slug)
    {
        $blog = Blog::findPublishedBySlugWithHardcoded($slug);

        abort_unless($blog, 404);

        $relatedBlogs = Blog::publishedWithHardcoded()
            ->reject(fn (Blog $related) => $related->slug === $blog->slug)
            ->take(3)
            ->values();

        return view('pages.blog.show', compact('blog', 'relatedBlogs'));
    }
}
