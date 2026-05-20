@extends('layouts.app')

@section('content')
    <div class="panel">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-bold">Материалы</h1>
            <div class="flex gap-2">
                <a class="btn-primary" href="{{ route('admin.posts.create') }}">Добавить материал</a>
                <a class="btn-muted" href="{{ route('admin.categories.index') }}">К категориям</a>
            </div>
        </div>

        <form method="get" action="{{ route('admin.posts.index') }}" class="mb-4 flex items-end gap-3 flex-wrap">
            <div class="flex-1 min-w-[200px] max-w-xs">
                <label class="label" for="category">Фильтр по категории</label>
                <select id="category" name="category" onchange="this.form.submit()" class="field">
                    <option value="">Все категории</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            @if(request('category'))
                <a href="{{ route('admin.posts.index') }}" class="btn-muted self-end">Сбросить</a>
            @endif
        </form>

        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <tr class="border-b border-slate-200 text-left text-slate-500"><th class="py-2">ID</th><th class="py-2">Заголовок</th><th class="py-2">Категория</th><th class="py-2">Опубликован</th><th class="py-2">Действия</th></tr>
            @foreach($posts as $post)
                <tr class="border-b border-slate-100">
                    <td class="py-2">{{ $post->id }}</td>
                    <td class="py-2 font-medium">{{ $post->title }}</td>
                    <td class="py-2 break-words">{{ $post->category->name }}</td>
                    <td class="py-2">{{ $post->is_published ? 'Да' : 'Нет' }}</td>
                    <td class="py-2">
                        <a class="mr-2 text-teal-700 hover:underline" href="{{ route('admin.posts.edit', $post) }}">Редактировать</a>
                        <form method="post" action="{{ route('admin.posts.destroy', $post) }}" class="inline">
                            @csrf
                            @method('delete')
                            <button class="btn-danger" type="submit">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $posts->links() }}</div>
@endsection
