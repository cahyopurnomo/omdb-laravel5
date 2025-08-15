@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h3>My Favorite Movies</h3>
    <div class="row">
        @forelse($favorites as $fav)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card movie-card">
                    <a href="{{ url('movies/'.$fav->imdb_id) }}">
                        <img src="{{ $fav->poster ?: asset('images/no-image.png') }}"
                             class="card-img-top"
                             alt="{{ $fav->title }}">
                    </a>
                    <div class="card-body">
                        <h6 class="card-title movie-title">{{ $fav->title }}</h6>
                        <p class="text-muted movie-year">{{ $fav->year }}</p>
                        <form action="{{ route('favorites.destroy', $fav->imdb_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada film favorit.</p>
        @endforelse
    </div>
</div>
@endsection
