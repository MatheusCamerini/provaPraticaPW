<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Filmes')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <header class="navbar">
        <a href="{{ route('filmes.index') }}" class="navbar-brand"> Filmes Muito bons do Matheus</a>

        <!-- <form action="{{ route('filmes.search') }}" method="GET" class="navbar-search">
            <input type="text" name="nome" placeholder="Buscar filmes..." value="{{ request('nome') }}">
            <button type="submit">Buscar</button>
        </form> -->

        <nav class="navbar-links">
            <a href="{{ route('filmes.index') }}">Filmes</a>

            @auth
                <a href="{{ route('filmes.create') }}">Adicionar</a>
                <a href="{{ route('filmes.trash') }}">Lixeira</a>
                <a href="{{ route('usuario') }}">{{ auth()->user()->name }}</a>
                <form action="{{ route('user.logout') }}" method="POST" class="inline-form">
                    @csrf
                    <button type="submit" class="link-button">Sair</button>
                </form>
            @else
                <a href="{{ route('login') }}">Entrar</a>
                <a href="{{ route('registro') }}">Registrar</a>
            @endauth
        </nav>
    </header>

    <main class="container">
        @if (session('success'))
            <div class="alert alert-success" id="flash-alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" id="flash-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
