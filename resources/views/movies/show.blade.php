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
        width: 65.666667% !important;
    }
    .movie-meta p {
        margin-bottom: 6px;
        font-size: 15px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row mb-4">
        <!-- Poster -->
        <div class="col-md-4 mb-3">
            <img src="{{ $movie['Poster'] !== 'N/A' ? $movie['Poster'] : asset('images/no-image.png') }}"
                class="movie-poster"
                alt="{{ $movie['Title'] }}"
                onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
        </div>

        <!-- Detail -->
        <div class="col-md-8 movie-detail">
            <h2>{{ $movie['Title'] }} <small class="text-muted">({{ $movie['Year'] }})</small></h2>
            <p class="text-muted mb-3">
                <strong>Rated:</strong> {{ $movie['Rated'] }} | 
                <strong>Runtime:</strong> {{ $movie['Runtime'] }}
            </p>
            
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

            <button type="button" class="btn btn-secondary mt-3" onclick="goBackToSearch()">⬅ {{ trans('messages.back_to_search') }}</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function goBackToSearch() {
    let lastTitle = localStorage.getItem('lastTitle') || '';
    let lastYear = localStorage.getItem('lastYear') || '';
    let lastType = localStorage.getItem('lastType') || '';
    let lastPage = localStorage.getItem('lastPage') || 1;

    let params = new URLSearchParams();
    if (lastTitle) params.append('q', lastTitle);
    if (lastYear) params.append('y', lastYear);
    if (lastType) params.append('type', lastType);
    params.append('page', lastPage);

    window.location.href = "{{ route('movies.index') }}" + '?' + params.toString();
}
</script>
@endpush
