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
            
            <!-- Tombol Tambah ke Favorit -->
            <button class="btn fav-btn mb-3"></button>

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

            <button type="button" class="btn btn-outline-secondary mt-3" onclick="goBackToSearch()">⬅ {{ trans('messages.back_to_search') }}</button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    let favList = [];
    $('.btn-favourite').hide();

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

    function loadFavourites(callback) {
        $.get("{{ route('favourites.list') }}", function(res) {
            favList = res.success ? res.ids : [];
            if (callback) callback();
        });
    }

    $(document).on('click', '.fav-btn', function() {
        let btn = $(this);
        let imdb_id = btn.data('id');
        let title = btn.data('title');
        let year = btn.data('year');
        let poster = btn.data('poster');

        if (btn.hasClass('btn-outline-primary')) {
            $.post("{{ route('favourites.store') }}", {
                _token: "{{ csrf_token() }}",
                imdb_id: imdb_id,
                title: title,
                year: year,
                poster: poster
            }, function(res) {
                if (res.success) {
                    btn.removeClass('btn-outline-primary').addClass('btn-outline-danger').text('❌ Hapus dari Favorit');
                    favList.push(imdb_id);
                }
            });
        } else {
            $.ajax({
                url: "{{ url('favourites') }}/" + imdb_id,
                method: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function(res) {
                    if (res.success) {
                        btn.removeClass('btn-outline-danger').addClass('btn-outline-primary').text('⭐ Tambah ke Favorit');
                        favList = favList.filter(id => id !== imdb_id);
                    }
                }
            });
        }
    });

    $(document).ready(function() {
        loadFavourites(function() {
            let imdbID = "{{ $movie['imdbID'] }}";
            let title = "{{ $movie['Title'] }}";
            let year = "{{ $movie['Year'] }}";
            let poster = "{{ $movie['Poster'] }}";

            let isFav = favList.includes(imdbID);
            let btnClass = isFav ? 'btn-outline-danger' : 'btn-outline-primary';
            let btnText = isFav ? '❌ ' + "{{ trans('messages.delete_favourite') }}" : '⭐ ' + "{{ trans('messages.add_favourite') }}";
            
            $('.fav-btn').addClass(btnClass).text(btnText);
            $('.fav-btn').attr('data-id', imdbID);
            $('.fav-btn').attr('data-title', title);
            $('.fav-btn').attr('data-year', year);
            $('.fav-btn').attr('data-poster', poster);
            $('.fav-btn').show();
        });
    });
</script>
@endpush
