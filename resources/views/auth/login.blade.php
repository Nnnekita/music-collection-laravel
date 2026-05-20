@extends('layouts.app')

@section('title', 'Вход — Музыкальная коллекция')

@section('content')
    <div class="w-full max-w-md mx-auto">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900 neon-text flex items-center justify-center gap-2">🎵 Музыкальная коллекция</a>
            <p class="text-gray-500 mt-2">Войдите, чтобы управлять своей медиатекой</p>
        </div>
        
        <form class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-200 space-y-5" method="post" action="{{ route('login.attempt') }}">
            @csrf    
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
                <input type="email" id="email" placeholder="you@example.com" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('email') border-rose-400 @enderror" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="password">Пароль</label>
                <input id="password" type="password" placeholder="••••••••" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('password') border-rose-400 @enderror" name="password" required>
                @error('password')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-gray-600">Запомнить меня</span>
                </label>
            </div>
            <button type="submit" class="w-full py-2.5 bg-primary text-white rounded-lg font-semibold hover:opacity-90 transition neon-glow">Войти</button>
        </form>
        <p class="text-center text-sm text-slate-500 mt-6">По умолчанию: admin@example.com / password</p>
    </div>
@endsection
