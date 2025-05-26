@extends('layouts.app')

@section('title', 'Posts')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Your Posts</h3>

    <div id="postList" class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Scheduled</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="postsTableBody">
                <tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>
            </tbody>
        </table>
    </div>
    <nav>
        <ul class="pagination" id="pagination"></ul>
    </nav>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        xhrFields: {
            withCredentials: true
        }
    });
    function loadPosts(page = 1) {
        $.ajax({
            url: '/api/posts?page=' + page,
            method: 'GET',
            success: function (res) {
                const posts = res.data;
                const tbody = $('#postsTableBody');
                tbody.empty();

                if (posts.length === 0) {
                    tbody.html('<tr><td colspan="4" class="text-center">No posts found.</td></tr>');
                    return;
                }

                posts.forEach(post => {
                    tbody.append(`
                        <tr>
                            <td>${post.title}</td>
                            <td>${post.status}</td>
                            <td>${post.scheduled_time}</td>
                            <td>
                                <a href="/posts/${post.id}/edit" class="btn btn-sm btn-primary">Edit</a>
                                <button class="btn btn-danger btn-sm delete-post" data-id="${post.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                });
                
                const pagination = $('#pagination').empty();
                for (let i = 1; i <= res.last_page; i++) {
                    const active = i === res.current_page ? 'active' : '';
                    pagination.append(`
                        <li class="page-item ${active}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                }
            },
            error: function () {
                $('#postsList').html('<p class="text-danger">Failed to load posts.</p>');
            }
        });
    }

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadPosts(page);
    });
    loadPosts();

    $(document).on('click', '.delete-post', function () {
        if (!confirm('Are you sure you want to delete this post?')) return;

        const postId = $(this).data('id');

        $.ajax({
            url: `/api/posts/${postId}`,
            type: 'DELETE',
            success: function () {
                loadPosts();
            },
            error: function () {
                alert('Failed to delete post.');
            }
        });
    });
});
</script>
@endsection
