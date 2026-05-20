@extends('layouts.app')

@section('content')
    <div class="panel mx-auto max-w-2xl">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h1 class="text-2xl font-bold">Новая категория</h1>
            <a class="btn-muted" href="{{ route('admin.categories.index') }}">Назад</a>
        </div>
        <form method="post" action="{{ route('admin.categories.store') }}">
            @csrf
            <label class="label mt-4">Название</label>
            <input class="field" type="text" name="name" value="{{ old('name') }}" required>

            <label class="label mt-3">Slug (необязательно)</label>
            <input class="field" type="text" name="slug" value="{{ old('slug') }}">

            <label class="label mt-3">Описание</label>
            <textarea class="field" name="description" rows="4">{{ old('description') }}</textarea>

            <button class="btn-primary mt-5" type="submit">Сохранить</button>
        </form>
    </div>
@endsection
