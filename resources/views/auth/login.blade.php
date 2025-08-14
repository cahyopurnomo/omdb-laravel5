@extends('layouts.app')

@push('styles')
<style>
    body {
        background-color: #f8f9fa;
    }
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card {
        width: 100%;
        max-width: 400px;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="text-center mb-4">Login</h4>
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ url('login') }}" autocomplete="off">
                {{ csrf_field() }}
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" maxlength="10" name="name" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" maxlength="20" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection
