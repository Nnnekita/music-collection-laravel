<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $posts = Post::with('category')
            ->published()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($request->string('sort') === 'title', function ($query) {
                $query->orderBy('title', 'asc');
            }, function ($query) {
                $query->latest('published_at');
            })
            ->paginate(10)
            ->withQueryString();

        $categories = Category::withCount('posts')->orderBy('name')->get();

        return view('posts.index', compact('posts', 'search', 'categories'));
    }

    public function show(Post $post): View
    {
        abort_unless($post->is_published, 404);

        return view('posts.show', compact('post'));
    }
}
