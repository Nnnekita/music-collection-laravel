@extends('layouts.app')

@section('title', $post->title . ' — Музыкальная коллекция')

@section('content')
    <div class="flex flex-col md:flex-row gap-8 lg:gap-12 mb-10">
        <div class="w-full md:w-72 lg:w-80 flex-shrink-0">
            @if($post->image_url)
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="rounded-xl shadow-lg neon-glow border border-gray-200 w-full aspect-square object-cover">
            @else
                <img src="https://placehold.co/400x400/ff1a40/ffffff?text={{ urlencode(mb_substr($post->title, 0, 2)) }}" alt="{{ $post->title }}" class="rounded-xl shadow-lg neon-glow border border-gray-200 w-full aspect-square object-cover">
            @endif
        </div>
        <div class="flex flex-col justify-end gap-4">
            <span class="text-xs font-bold uppercase tracking-wider text-primary">{{ $post->category->name ?? 'Без жанра' }}{{ $post->published_at ? ' • ' . $post->published_at->format('Y') : '' }}</span>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900">{{ $post->title }}</h1>
            @if($post->category)
                <a href="{{ route('categories.show', $post->category->slug) }}" class="text-lg text-gray-700 hover:text-primary transition">{{ $post->category->name }}</a>
            @endif
            <div class="flex flex-wrap gap-3 text-sm text-gray-500">
                @if($post->excerpt)
                    <span>{{ Str::limit(strip_tags($post->excerpt), 100) }}</span>
                @endif
            </div>
            <div class="flex flex-wrap gap-3 mt-4">
                <a href="{{ route('posts.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-full hover:border-primary hover:text-primary transition">← Назад к альбом</a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Содержимое</h2>
        </div>
        <div class="px-6 py-6 whitespace-pre-line break-words leading-7 text-slate-700">
            {{ $post->content }}
        </div>
    </div>
@endsection
