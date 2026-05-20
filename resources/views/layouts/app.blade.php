<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Музыкальная коллекция')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col">

<header class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-200">
    <nav class="container mx-auto px-4 py-3 md:py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-xl md:text-2xl font-bold text-gray-900 flex items-center gap-2 hover:text-primary transition duration-300">
            🎵 Музыкальная коллекция
        </a>
        
        <div class="hidden md:flex items-center gap-8 font-medium">
            <a href="{{ route('home') }}" class="relative group py-2 text-gray-700 hover:text-primary transition {{ request()->routeIs('home') ? 'text-primary' : '' }}">
                Главная
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary group-hover:w-full transition-all duration-300 {{ request()->routeIs('home') ? 'w-full' : '' }}"></span>
            </a>
            <a href="{{ route('posts.index') }}" class="relative group py-2 text-gray-700 hover:text-primary transition {{ request()->routeIs('posts.*') ? 'text-primary' : '' }}">
                Альбомы
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary group-hover:w-full transition-all duration-300 {{ request()->routeIs('posts.*') ? 'w-full' : '' }}"></span>
            </a>
            <a href="{{ route('categories.index') }}" class="relative group py-2 text-gray-700 hover:text-primary transition {{ request()->routeIs('categories.*') ? 'text-primary' : '' }}">
                Исполнители
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary group-hover:w-full transition-all duration-300 {{ request()->routeIs('categories.*') ? 'w-full' : '' }}"></span>
            </a>
        </div>

        <div class="flex items-center gap-3">
            <form method="get" action="{{ route('posts.index') }}" class="hidden md:block">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Поиск..." 
                       class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-56 lg:w-72 bg-white/80 transition">
            </form>
            @auth
                <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 text-sm bg-gray-700 text-white rounded-lg hover:bg-gray-600 font-medium transition">Админка</a>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primaryHover neon-glow-hover font-medium transition" type="submit">Выход</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primaryHover neon-glow-hover font-medium transition">Войти</a>
            @endauth
            
            <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-600 hover:text-primary transition rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </nav>

    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200 px-4 py-4 space-y-3 shadow-lg">
        <a href="{{ route('home') }}" class="block py-2 hover:text-primary font-medium border-b border-gray-100 {{ request()->routeIs('home') ? 'text-primary' : '' }}">Главная</a>
        <a href="{{ route('posts.index') }}" class="block py-2 hover:text-primary font-medium border-b border-gray-100 {{ request()->routeIs('posts.*') ? 'text-primary' : '' }}">Альбомы</a>
        <a href="{{ route('categories.index') }}" class="block py-2 hover:text-primary font-medium {{ request()->routeIs('categories.*') ? 'text-primary' : '' }}">Исполнители</a>
        <form method="get" action="{{ route('posts.index') }}">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Поиск..." class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        </form>
    </div>
</header>

<main class="flex-1 container mx-auto px-4 py-6 md:py-10 pb-16">
    @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
            <ul class="list-inside list-disc space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<footer class="bg-gray-900 text-gray-400 py-10 border-t border-gray-800 mt-auto">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="md:col-span-2">
            <h4 class="text-white font-semibold mb-3 text-lg neon-text">🎵 Музыкальная коллекция</h4>
            <p class="text-sm leading-relaxed max-w-md text-gray-400">Управляйте своей медиатекой, создавайте плейлисты и открывайте новые треки. Проект построен на Laravel с фокусом на скорость и удобство.</p>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Разделы</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-primary transition">Главная</a></li>
                <li><a href="{{ route('posts.index') }}" class="hover:text-primary transition">Альбомы</a></li>
                <li><a href="{{ route('categories.index') }}" class="hover:text-primary transition">Исполнители</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Информация</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:text-primary transition">О проекте</a></li>
                <li><a href="#" class="hover:text-primary transition">FAQ</a></li>
                <li><a href="#" class="hover:text-primary transition">Конфиденциальность</a></li>
                <li><a href="#" class="hover:text-primary transition">Контакты</a></li>
            </ul>
        </div>
    </div>
    <div class="container mx-auto px-4 mt-8 pt-6 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center text-xs">
        <p>&copy; {{ date('Y') }} Музыкальная коллекция. Все права защищены.</p>
        <div class="flex gap-4 mt-3 md:mt-0">
            <a href="#" class="hover:text-primary transition">Telegram</a>
            <a href="#" class="hover:text-primary transition">GitHub</a>
            <a href="#" class="hover:text-primary transition">VK</a>
        </div>
    </div>
</footer>

</body>
</html>
