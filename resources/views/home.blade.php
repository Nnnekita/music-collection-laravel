@extends('layouts.app')

@section('title', 'Главная — Музыкальная коллекция')

@section('content')
    <button id="mobile-filter-btn" class="md:hidden w-full mb-4 flex items-center justify-between px-4 py-3 bg-white rounded-xl shadow-sm border border-gray-200 hover:border-primary transition active:scale-[0.99]">
        <span class="font-medium text-gray-800">Фильтры и сортировка</span>
        <span id="filter-arrow" class="text-primary transition-transform duration-300">▼</span>
    </button>

    <div class="flex flex-col lg:grid lg:grid-cols-12 gap-6">
        
        <aside id="filters-panel" class="lg:col-span-3 lg:block" style="max-height:none;overflow:visible;">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 h-fit lg:sticky lg:top-24">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Фильтры</h3>
                
                <div class="mb-6">
                    <h4 class="font-semibold text-xs text-gray-500 uppercase tracking-wider mb-2">Жанры</h4>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="{{ route('home') }}" class="block py-1.5 px-2 rounded hover:bg-red-50 hover:text-primary text-gray-700 transition text-sm {{ !$activeCategory ? 'bg-red-50 text-primary font-semibold' : '' }}">
                                Все <span class="text-gray-400">({{ $categories->sum('posts_count') }})</span>
                            </a>
                        </li>
                        @forelse($categories as $category)
                            <li>
                                <a href="{{ route('home', ['category' => $category->slug]) }}" class="block py-1.5 px-2 rounded hover:bg-red-50 hover:text-primary text-gray-700 transition text-sm {{ $activeCategory === $category->slug ? 'bg-red-50 text-primary font-semibold' : '' }}">
                                    {{ $category->name }} <span class="text-gray-400">({{ $category->posts_count }})</span>
                                </a>
                            </li>
                        @empty
                            <li class="text-sm text-gray-400">Жанры пока не добавлены</li>
                        @endforelse
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-xs text-gray-500 uppercase tracking-wider mb-2">Сортировка</h4>
                    <select onchange="window.location.href=this.value" class="w-full p-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white text-sm transition">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ !request('sort') || request('sort') === 'latest' ? 'selected' : '' }}>По дате добавления ↓</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'title']) }}" {{ request('sort') === 'title' ? 'selected' : '' }}>По названию А-Я</option>
                    </select>
                </div>
            </div>
        </aside>

        <section class="lg:col-span-9">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    @if($activeCategory)
                        @php $activeCat = $categories->firstWhere('slug', $activeCategory); @endphp
                        {{ $activeCat?->name ?? 'Все релизы' }}
                    @else
                        Все релизы
                    @endif
                </h1>
                @if($activeCategory)
                    <a href="{{ route('home') }}" class="text-sm text-primary hover:underline">Сбросить фильтр</a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @forelse($latestPosts as $post)
                <article class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:border-primary hover:neon-glow-hover transition-all duration-300 group cursor-pointer">
                    <a href="{{ route('posts.show', $post->slug) }}" class="block">
                        <div class="relative aspect-square bg-gray-100 rounded-lg mb-3 overflow-hidden">
                            @if($post->image_url)
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <img src="https://placehold.co/300x300/ff1a40/ffffff?text={{ urlencode($post->title) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @endif
                            <button class="absolute bottom-3 right-3 bg-primary/90 backdrop-blur p-2.5 rounded-full shadow-lg opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition duration-300 hover:neon-glow text-white">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#ffffff"><path d="M8 5v14l11-7z"/></svg>
                            </button>
                        </div>
                        <h3 class="font-semibold text-lg truncate text-gray-900 group-hover:text-primary transition">{{ $post->title }}</h3>
                        <p class="text-sm text-gray-500 truncate">{{ $post->category->name ?? 'Без жанра' }}{{ $post->published_at ? ' • ' . $post->published_at->format('Y') : '' }}</p>
                        <div class="flex items-center justify-between mt-2 text-xs text-gray-400">
                            <span>{{ Str::limit(strip_tags($post->excerpt), 50) }}</span>
                        </div>
                    </a>
                </article>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">
                            @if($activeCategory)
                                В этом жанре пока нет материалов.
                            @else
                                Пока нет опубликованных материалов.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
