<label class="label mt-4">Категория</label>
<select class="field" name="category_id" required>
    <option value="">-- выберите --</option>
    @foreach($categories as $category)
        <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id ?? null) == $category->id)>
            {{ $category->name }}
        </option>
    @endforeach
</select>

<label class="label mt-3">Заголовок</label>
<input class="field" type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required>

<label class="label mt-3">Slug (необязательно)</label>
<input class="field" type="text" name="slug" value="{{ old('slug', $post->slug ?? '') }}">

<label class="label mt-3">Краткое описание</label>
<textarea class="field" name="excerpt" rows="3">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>

<label class="label mt-3">Полный текст</label>
<textarea class="field" name="content" rows="8" required>{{ old('content', $post->content ?? '') }}</textarea>

<label class="label mt-3">Изображение</label>
<input class="field" type="file" name="image" accept="image/*">
@if(!empty($post?->image_url))
    <p class="mt-2 text-sm text-slate-500">Текущее изображение:</p>
    <p class="mt-2"><img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="max-w-[220px] rounded-xl"></p>
@endif

<label class="label mt-3">Дата публикации</label>
<input class="field" type="datetime-local" name="published_at" value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\\TH:i') : '') }}">

<label class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-slate-700">
    <input class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500" type="checkbox" name="is_published" value="1" @checked(old('is_published', isset($post) ? $post->is_published : true))>
    Опубликовать
</label>
