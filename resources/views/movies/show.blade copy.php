@extends('layouts.app')

@push('styles')
<style>
    .movie-poster {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .movie-detail {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .movie-meta p {
        margin-bottom: 6px;
        font-size: 15px;
    }
</style>
@endpush

@section('content')
<div class="container my-4">
    <div class="row">
        <!-- Poster -->
        <div class="col-md-4 mb-3">
            <img src="{{ $movie['Poster'] !== 'N/A' ? $movie['Poster'] : asset('images/no-image.png') }}"
                 class="movie-poster" alt="{{ $movie['Title'] }}">
        </div>

        <!-- Detail -->
        <div class="col-md-8 movie-detail">
            <h2>{{ $movie['Title'] }} <small class="text-muted">({{ $movie['Year'] }})</small></h2>
            <p class="text-muted mb-3"><strong>Rated:</strong> {{ $movie['Rated'] }} | <strong>Runtime:</strong> {{ $movie['Runtime'] }}</p>
            
            <div class="movie-meta">
                <p><strong>Genre:</strong> {{ $movie['Genre'] }}</p>
                <p><strong>Director:</strong> {{ $movie['Director'] }}</p>
                <p><strong>Writer:</strong> {{ $movie['Writer'] }}</p>
                <p><strong>Actors:</strong> {{ $movie['Actors'] }}</p>
                <p><strong>Released:</strong> {{ $movie['Released'] }}</p>
                <p><strong>IMDB Rating:</strong> ⭐ {{ $movie['imdbRating'] }} / 10</p>
            </div>

            <hr>

            <h5>Plot</h5>
            <p>{{ $movie['Plot'] }}</p>

            <a href="{{ route('movies.index') }}" class="btn btn-secondary mt-3" onclick="goBackToSearch()">⬅ Back to Search Result</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function goBackToSearch() {
    window.location.href = "{{ route('movies.index') }}";
}
</script>
@endpush
