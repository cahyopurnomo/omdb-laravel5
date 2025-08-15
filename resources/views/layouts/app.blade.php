<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') ?: trans('messages.app_name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container"> {{-- px-0 agar padding kiri-kanan sama dengan content --}}
            <a class="navbar-brand" href="{{ route('movies.index') }}">{{ trans('messages.app_name') }}</a>

            {{-- Menu navigasi --}}
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('movies.index') ? 'active' : '' }}" href="{{ route('movies.index') }}">
                        🎬 {{ __('Movies') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('favourites.index') ? 'active' : '' }}" href="{{ route('favourites.index') }}">
                        ⭐ {{ __('Favourites') }}
                    </a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center ms-auto gap-2">
                {{-- Switch Language --}}
                <div class="btn-group me-2">
                    <a href="{{ route('lang.switch', 'en') }}" class="btn btn-sm btn-outline-light {{ app()->getLocale() === 'en' ? 'active' : '' }}">🇬🇧 EN</a>
                    <a href="{{ route('lang.switch', 'id') }}" class="btn btn-sm btn-outline-light {{ app()->getLocale() === 'id' ? 'active' : '' }}">🇮🇩 ID</a>
                </div>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        {{ trans('messages.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </nav>
    @endauth

    <main class="container px-0 mt-4"> {{-- px-0 supaya lebar konten sama persis --}}
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
