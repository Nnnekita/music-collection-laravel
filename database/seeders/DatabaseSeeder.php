<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $genres = [
            ['name' => 'Рок', 'slug' => 'rock', 'description' => 'Рок-музыка всех направлений'],
            ['name' => 'Поп', 'slug' => 'pop', 'description' => 'Популярная музыка'],
            ['name' => 'Джаз', 'slug' => 'jazz', 'description' => 'Джазовая музыка'],
            ['name' => 'Электроника', 'slug' => 'electronic', 'description' => 'Электронная музыка'],
            ['name' => 'Хип-хоп', 'slug' => 'hip-hop', 'description' => 'Хип-хоп и рэп'],
            ['name' => 'Классика', 'slug' => 'classical', 'description' => 'Классическая музыка'],
            ['name' => 'R&B', 'slug' => 'rnb', 'description' => 'R&B и соул'],
            ['name' => 'Метал', 'slug' => 'metal', 'description' => 'Метал всех поджанров'],
        ];

        foreach ($genres as $genre) {
            Category::query()->firstOrCreate(
                ['slug' => $genre['slug']],
                $genre
            );
        }

        Post::query()->firstOrCreate(
            ['slug' => 'primer-materiala'],
            [
                'category_id' => Category::where('slug', 'rock')->first()?->id,
                'title' => 'Пример материала',
                'excerpt' => 'Короткое описание материала.',
                'content' => 'Это демонстрационный материал.',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}
