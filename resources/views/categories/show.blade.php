@extends('layouts.app')

@section('title', $category->name . ' — Музыкальная коллекция')

@section('content')
    <div class="mb-8">
        <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-primary transition mb-4">← Назад</a>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $category->name }}</h1>
        @if($category->description)
            <p class="mt-2 text-gray-600">{{ $category->description }}</p>
        @endif
        <span class="inline-block mt-3 text-sm text-gray-500">{{ $posts->total() }} релизов</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($posts as $post)
        <article class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:border-primary hover:shadow-md transition group cursor-pointer">
            <a href="{{ route('posts.show', $post->slug) }}" class="block">
                <div class="relative aspect-square bg-gray-100 rounded-lg mb-3 overflow-hidden">
                    @if($post->image_url)
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                    @else
                        <img src="https://placehold.co/300x300/ff1a40/ffffff?text={{ urlencode(mb_substr($post->title, 0, 2)) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                    @endif
                </div>
                <h3 class="font-semibold text-lg truncate group-hover:text-primary transition">{{ $post->title }}</h3>
                <p class="text-sm text-gray-500">{{ $post->published_at ? $post->published_at->format('Y') : '' }}</p>
            </a>
        </article>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">В этой категории пока нет материалов.</p>
            </div>
        @endforelse
    </div>

    <div class="flex items-center justify-center gap-2 mt-10 flex-wrap">
        {{ $posts->links() }}
    </div>
@endsection
