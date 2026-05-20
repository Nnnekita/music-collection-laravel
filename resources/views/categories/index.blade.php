@extends('layouts.app')

@section('title', 'Исполнители — Музыкальная коллекция')

@section('content')
    <h1 class="text-2xl md:text-3xl font-bold mb-6">Исполнители</h1>
    
    <div class="flex overflow-x-auto gap-2 pb-3 mb-8 no-scrollbar">
        <a href="{{ route('categories.index') }}" class="px-3 py-1.5 bg-primary text-white rounded-full text-sm whitespace-nowrap">Все</a>
        @foreach($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-full text-sm whitespace-nowrap hover:border-primary hover:text-primary transition">{{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}</a>
        @endforeach
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
        @forelse($categories as $category)
        <a href="{{ route('categories.show', $category->slug) }}" class="group flex flex-col items-center gap-3 p-4 bg-white rounded-xl border border-gray-200 hover:border-primary hover:shadow-md transition">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-pink-600 flex items-center justify-center text-white text-3xl font-bold group-hover:scale-105 transition">
                {{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}
            </div>
            <h3 class="font-semibold text-center text-sm group-hover:text-primary transition">{{ $category->name }}</h3>
            <p class="text-xs text-gray-500">{{ $category->posts_count }} альбомов</p>
        </a>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">Исполнители пока не добавлены.</p>
            </div>
        @endforelse
    </div>
@endsection
