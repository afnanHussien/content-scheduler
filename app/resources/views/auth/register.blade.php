@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
        <h4 class="text-center mb-4">Register</h4>
        <form id="registerForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <div id="registerError" class="text-danger mt-3 d-none"></div>
        </form>
        <div class="mt-3 text-center">
            <small>Already have an account? <a href="/login">Login</a></small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#registerForm').submit(function (e) {
        e.preventDefault();
        $('#registerError').addClass('d-none');

        $.ajax({
            url: '/api/register',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                localStorage.setItem('token', response.token);
                window.location.href = '/dashboard';
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Registration failed';
                $('#registerError').removeClass('d-none').text(message);
            }
        });
    });
</script>
@endsection
