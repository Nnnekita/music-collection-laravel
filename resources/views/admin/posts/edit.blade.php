@extends('layouts.app')

@section('content')
    <div class="panel mx-auto max-w-3xl">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h1 class="text-2xl font-bold">Редактирование материала</h1>
            <a class="btn-muted" href="{{ route('admin.posts.index') }}">Назад</a>
        </div>
        <form method="post" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('put')
            @include('admin.posts.partials.form')
            <button class="btn-primary mt-5" type="submit">Сохранить</button>
        </form>
    </div>
@endsection
