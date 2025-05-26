@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
        <h4 class="text-center mb-4">Login</h4>
        <form id="loginForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <div id="loginError" class="text-danger mt-3 d-none"></div>
        </form>
        <div class="mt-3 text-center">
            <small>Don't have an account? <a href="/register">Register</a></small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#loginForm').submit(function (e) {
        e.preventDefault();
        $('#loginError').addClass('d-none');

        $.ajax({
            url: '/api/login',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                window.location.href = '/dashboard';
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Login failed';
                $('#loginError').removeClass('d-none').text(message);
            }
        });
    });
</script>
@endsection
