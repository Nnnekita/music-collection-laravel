<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        $posts = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => Str::slug($request->string('title')->toString())]);
        }

        $data = $this->validatedData($request);
        $data['is_published'] = $request->boolean('is_published');
        $data['excerpt'] = $this->resolveExcerpt($data);
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        try {
            Post::create($data);
        } catch (UniqueConstraintViolationException) {
            return back()->withErrors(['slug' => 'Такой slug уже существует.'])->withInput();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Материал создан.');
    }

    public function edit(Post $post): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        if (! $request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => Str::slug($request->string('title')->toString())]);
        }

        $data = $this->validatedData($request, $post->id);
        $data['is_published'] = $request->boolean('is_published');
        $data['excerpt'] = $this->resolveExcerpt($data);
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            if ($post->image && ! Str::startsWith($post->image, ['http://', 'https://'])) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        try {
            $post->update($data);
        } catch (UniqueConstraintViolationException) {
            return back()->withErrors(['slug' => 'Такой slug уже существует.'])->withInput();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Материал обновлен.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->image && ! Str::startsWith($post->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Материал удален.');
    }

    private function validatedData(Request $request, ?int $postId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug,' . $postId],
            'excerpt' => ['nullable', 'string', 'max:400'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }

    private function resolveExcerpt(array $data): string
    {
        if (! empty($data['excerpt'])) {
            return $data['excerpt'];
        }

        return Str::of(strip_tags((string) ($data['content'] ?? '')))
            ->squish()
            ->limit(400, '...')
            ->toString();
    }
}
