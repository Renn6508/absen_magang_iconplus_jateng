@extends('layouts.app')
@section('title', 'Login Admin')

@section('content')
<div class="row justify-content-center">
<div class="col-md-5 col-lg-4">
    <form method="POST" action="{{ route('admin.login') }}" class="card shadow-sm">
        @csrf
        <div class="card-body p-4">
            <h5 class="mb-3">Login Admin Pembimbing</h5>
            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" autofocus required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-pln w-100">Masuk</button>
            <div class="text-center mt-3"><a href="{{ route('absen.create') }}" class="small text-muted">← Kembali ke form absen</a></div>
        </div>
    </form>
</div></div>
@endsection