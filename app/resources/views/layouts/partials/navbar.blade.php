<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Content Scheduler</a>

        @auth
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/posts') }}">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/posts/create') }}">Create Post</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" id="logoutBtn">Logout</a>
                    </li>
                </ul>
            </div>
        @endauth

        @guest
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
            </ul>
        @endguest
    </div>
</nav>
@section('scripts')
<script>
$(document).ready(function () {
    $('#logoutBtn').click(function (e) {
        e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        xhrFields: {
            withCredentials: true
        }
    });
        $.ajax({
            url: '/api/logout',
            type: 'POST',
            xhrFields: { withCredentials: true },
            success: function () {
                window.location.href = '/login'; // or any redirect
            },
            error: function () {
                alert('Logout failed.');
            }
        });
    });
});
</script>
@endsection
