<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('posts')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category): View
    {
        $posts = $category->posts()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->paginate(10);

        return view('categories.show', compact('category', 'posts'));
    }
}
