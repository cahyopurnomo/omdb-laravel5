@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Favorites</h1>
    <div class="row">
        @foreach($favourites as $fav)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="{{ $fav['poster'] }}" class="card-img-top" alt="{{ $fav['title'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $fav['title'] }} ({{ $fav['year'] }})</h5>
                        @if($fav['omdb_detail'])
                            <p>Director: {{ $fav['omdb_detail']['Director'] }}</p>
                            <p>Genre: {{ $fav['omdb_detail']['Genre'] }}</p>
                            <p>IMDB Rating: {{ $fav['omdb_detail']['imdbRating'] }}</p>
                            <button data-id="{{ $fav['imdb_id'] }}" class="btn btn-outline-danger fav-btn btn-sm w-100 mt-2">❌ {{ trans('messages.delete_favourite') }}</button>
                        @else
                            <p>Detail OMDb tidak tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.fav-btn', function() {
        let btn = $(this);
        let imdb_id = btn.data('id');
        
        $.ajax({
            url: "{{ url('favourites') }}/" + imdb_id,
            method: 'DELETE',
            data: { _token: "{{ csrf_token() }}" },
            success: function(res) {
                console.log(res)
                if (res.success) {
                    location.reload();
                }
            }
        });
    });
</script>
@endpush