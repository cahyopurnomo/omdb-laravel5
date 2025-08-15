@extends('layouts.app')

@push('styles')
<style>
    .movie-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .movie-card img {
        height: 350px;
        object-fit: cover;
    }
    .movie-card .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
    }
    .movie-title {
        font-size: 1rem;
        font-weight: bold;
        line-height: 1.2em;
        height: 2.4em;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .movie-year {
        color: #6c757d;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6 mb-2">
            <input type="text" id="titleInput" class="form-control" placeholder="{{ trans('messages.search_title') }}">
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="4" id="yearInput" class="form-control" placeholder="{{ __('messages.search_year') }}">
        </div>
        <div class="col-md-3 mb-2">
            <select id="typeSelect" class="form-control">
                <option value="">{{ trans('messages.search_type') }}</option>
                <option value="movie">{{ trans('messages.type_movie') }}</option>
                <option value="series">{{ trans('messages.type_series') }}</option>
                <option value="episode">{{ trans('messages.type_episode') }}</option>
            </select>
        </div>
    </div>

    <div class="row" id="movieList"></div>
    <div id="loading" class="text-center my-4" style="display:none;">
        <div class="spinner-border" role="status"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let page = 1;
    let keywords = ['batman', 'avengers', 'spider', 'love', 'war', 'space', 'hero', 'dream', 'king', 'queen'];
    let titleQuery = '';
    let yearQuery = '';
    let typeQuery = '';
    let loading = false;
    let defaultPoster = "{{ asset('images/no-image.png') }}";
    let favList = [];

    // Ambil data filter terakhir dari localStorage
    if (localStorage.getItem('lastTitle')) {
        titleQuery = localStorage.getItem('lastTitle');
        yearQuery = localStorage.getItem('lastYear') || '';
        typeQuery = localStorage.getItem('lastType') || '';
        page = parseInt(localStorage.getItem('lastPage')) || 1;
    } else {
        titleQuery = keywords[Math.floor(Math.random() * keywords.length)];
    }

    function loadFavourites(callback) {
        $.get("{{ route('favourites.list') }}", function(res) {
            favList = res.success ? res.ids : [];
            if (callback) callback();
        });
    }

    function loadMovies(reset = false) {
        if (!titleQuery.trim()) return;
        if (loading) return;
        loading = true;
        $('#loading').show();

        $.get("{{ url('movies/search') }}", { q: titleQuery, y: yearQuery, type: typeQuery, page: page }, function(data) {
            if (data.Search) {
                let moviesHTML = '';
                data.Search.forEach(movie => {
                    let poster = (movie.Poster && movie.Poster !== 'N/A') ? movie.Poster : defaultPoster;
                    let isFav = favList.includes(movie.imdbID);
                    let btnClass = isFav ? 'btn-outline-danger' : 'btn-outline-primary';
                    let btnText = isFav ? '❌ ' + "{{ trans('messages.delete_favourite') }}" : '⭐ ' + "{{ trans('messages.add_favourite') }}";

                    moviesHTML += `
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card movie-card">
                                <a href="{{ url('movies') }}/${movie.imdbID}">
                                    <img src="${poster}" class="card-img-top" alt="${movie.Title}"
                                         onerror="this.onerror=null;this.src='${defaultPoster}';">
                                </a>
                                <div class="card-body">
                                    <h6 class="card-title movie-title">${movie.Title}</h6>
                                    <p class="text-muted movie-year">${movie.Year}</p>
                                    <button class="btn ${btnClass} btn-sm fav-btn"
                                            data-id="${movie.imdbID}"
                                            data-title="${movie.Title.replace(/"/g, '&quot;')}"
                                            data-year="${movie.Year}"
                                            data-poster="${poster}">
                                        ${btnText}
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                if (reset) {
                    $('#movieList').html(moviesHTML);
                } else {
                    $('#movieList').append(moviesHTML);
                }

                localStorage.setItem('lastTitle', titleQuery);
                localStorage.setItem('lastYear', yearQuery);
                localStorage.setItem('lastType', typeQuery);
                localStorage.setItem('lastPage', page);

                page++;
            }
            $('#loading').hide();
            loading = false;
        });
    }

    // Toggle favorit
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
        $('#titleInput').val(titleQuery);
        $('#yearInput').val(yearQuery);
        $('#typeSelect').val(typeQuery);

        loadFavourites(function() {
            loadMovies(true);
        });

        $('#titleInput, #yearInput').on('keyup', function(e) {
            if (e.keyCode === 13) {
                titleQuery = $('#titleInput').val().trim() || keywords[Math.floor(Math.random() * keywords.length)];
                yearQuery = $('#yearInput').val().trim();
                typeQuery = $('#typeSelect').val();
                page = 1;
                loadMovies(true);
            }
        });

        $('#typeSelect').on('change', function() {
            titleQuery = $('#titleInput').val().trim() || keywords[Math.floor(Math.random() * keywords.length)];
            yearQuery = $('#yearInput').val().trim();
            typeQuery = $(this).val();
            page = 1;
            loadMovies(true);
        });

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                loadMovies();
            }
        });
    });
</script>
@endpush
