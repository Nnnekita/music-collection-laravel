@extends('layouts.app')

@section('content')
    <div class="panel">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-bold">Категории</h1>
            <div class="flex gap-2">
                <a class="btn-primary" href="{{ route('admin.categories.create') }}">Добавить категорию</a>
                <a class="btn-muted" href="{{ route('admin.posts.index') }}">К материалам</a>
            </div>
        </div>
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <tr class="border-b border-slate-200 text-left text-slate-500"><th class="py-2">ID</th><th class="py-2">Название</th><th class="py-2">Slug</th><th class="py-2">Действия</th></tr>
            @foreach($categories as $category)
                <tr class="border-b border-slate-100">
                    <td class="py-2">{{ $category->id }}</td>
                    <td class="py-2 font-medium">{{ $category->name }}</td>
                    <td class="py-2 text-slate-500">{{ $category->slug }}</td>
                    <td class="py-2">
                        <a class="mr-2 text-teal-700 hover:underline" href="{{ route('admin.categories.edit', $category) }}">Редактировать</a>
                        <form method="post" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                            @csrf
                            @method('delete')
                            <p class="btn-danger" type="submit">Удалить</p>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        </div>
    </div>
@endsection
