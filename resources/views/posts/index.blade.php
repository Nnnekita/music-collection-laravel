@extends('layouts.app')

@section('title', 'Альбомы — Музыкальная коллекция')

@section('content')
    <button id="mobile-filter-btn" class="md:hidden w-full mb-4 flex items-center justify-between px-4 py-3 bg-white rounded-xl shadow-sm border border-gray-200 hover:border-primary transition active:scale-[0.99]">
        <span class="font-medium text-gray-800">Фильтры и сортировка</span>
        <span id="filter-arrow" class="text-primary transition-transform duration-300">▼</span>
    </button>

    <div class="flex flex-col lg:grid lg:grid-cols-12 gap-6">
        <aside id="filters-panel" class="filter-panel lg:col-span-3 lg:max-h-none lg:overflow-visible lg:block">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 lg:sticky lg:top-24">
                <h3 class="font-bold text-lg mb-4 neon-text">Фильтры</h3>
                
                <div class="space-y-1.5 mb-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Жанры</p>
                    @forelse($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" class="block py-1.5 px-2 rounded hover:bg-red-50 hover:text-primary text-gray-700 text-sm">{{ $category->name }}</a>
                    @empty
                        <p class="text-sm text-gray-400">Жанры не добавлены</p>
                    @endforelse
                </div>

                <select onchange="window.location.href=this.value" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary text-sm">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ !request('sort') || request('sort') === 'latest' ? 'selected' : '' }}>Сначала новые</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'title']) }}" {{ request('sort') === 'title' ? 'selected' : '' }}>По названию А-Я</option>
                </select>
            </div>
        </aside>

        <section class="lg:col-span-9">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                <h1 class="text-2xl md:text-3xl font-bold">Каталог альбомов</h1>
                <span class="text-sm text-gray-500">Найдено: {{ $posts->total() }}</span>
            </div>

            @if($search)
                <div class="mb-4 text-sm text-gray-600">
                    Результаты поиска: <strong>{{ $search }}</strong>
                    <a href="{{ route('posts.index') }}" class="ml-2 text-primary hover:underline">Сбросить</a>
                </div>
            @endif

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
                        <p class="text-sm text-gray-500">{{ $post->category->name ?? 'Без жанра' }}{{ $post->published_at ? ' • ' . $post->published_at->format('Y') : '' }}</p>
                    </a>
                </article>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">Ничего не найдено.</p>
                    </div>
                @endforelse
            </div>

            <div class="flex items-center justify-center gap-2 mt-10 flex-wrap">
                {{ $posts->links() }}
            </div>
        </section>
    </div>
@endsection
