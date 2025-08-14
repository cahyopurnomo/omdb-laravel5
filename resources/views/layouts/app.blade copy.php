<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('movies.index') }}">Movie App</a>
            <div class="ml-auto">
                <form action="{{ route('logout') }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-outline-light btn-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    @endauth

    <main>
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>