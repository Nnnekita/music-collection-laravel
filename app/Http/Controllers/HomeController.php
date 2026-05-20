<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = Post::with('category')->published();

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->string('category')->toString());
            });
        }

        if ($request->string('sort') === 'title') {
            $query->orderBy('title', 'asc');
        } else {
            $query->latest('published_at');
        }

        return view('home', [
            'latestPosts' => $query->take(6)->get(),
            'categories' => Category::withCount('posts')->orderBy('name')->get(),
            'activeCategory' => $request->string('category')->toString(),
        ]);
    }
}
