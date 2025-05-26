@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

        <!-- Filters -->
        <div class="mb-4 flex gap-4">
            <select id="statusFilter" class="p-2 border rounded">
                <option value="">All Statuses</option>
                <option value="draft">Draft</option>
                <option value="scheduled">Scheduled</option>
                <option value="published">Published</option>
            </select>
            <input type="date" id="dateFilter" class="p-2 border rounded">
            <button id="filterBtn" class="bg-blue-600 text-white p-2 rounded">Filter</button>
        </div>

        <!-- Analytics -->
        <div class="mb-4">
            <h2 class="text-lg font-bold">Analytics</h2>
            <div id="analytics">Loading analytics...</div>
        </div>

        <!-- Schedule View -->
        <div class="mb-4">
            <h2 class="text-lg font-bold">Scheduled Posts</h2>
            <div id="schedule" class="grid gap-4"></div>
        </div>

        <!-- Post List -->
        <div>
            <h2 class="text-lg font-bold">Posts</h2>
            <div id="posts" class="grid gap-4"></div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Load posts
            function loadPosts(status = '', date = '') {
                const params = new URLSearchParams();
                if (status) params.append('status', status);
                if (date) params.append('date', date);
                
                $.ajax({
                    url: `/api/posts?${params.toString()}`,
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + authToken,
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (posts) {
                        const $postsDiv = $('#posts').empty();
                        const $scheduleDiv = $('#schedule').empty();
                        
                        posts.forEach(post => {
                            const postHtml = `
                                <div class="border p-4 rounded">
                                    <h3 class="font-bold">${post.title}</h3>
                                    <p>${post.content}</p>
                                    <p>Status: ${post.status}</p>
                                    <p>Scheduled: ${post.scheduled_time}</p>
                                    <button class="editBtn bg-yellow-500 text-white p-1 rounded mr-2" data-id="${post.id}">Edit</button>
                                    <button class="deleteBtn bg-red-500 text-white p-1 rounded" data-id="${post.id}">Delete</button>
                                </div>
                            `;
                            if (post.status === 'scheduled') {
                                $scheduleDiv.append(postHtml);
                            }
                            $postsDiv.append(postHtml);
                        });

                        // Event listeners
                        $('.editBtn').on('click', function () {
                            alert('Edit functionality to be implemented. Post ID: ' + $(this).data('id'));
                        });
                        $('.deleteBtn').on('click', function () {
                            if (confirm('Are you sure?')) {
                                $.ajax({
                                    url: `/api/posts/${$(this).data('id')}`,
                                    method: 'DELETE',
                                    headers: {
                                        'Authorization': 'Bearer ' + authToken,
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    success: function () {
                                        loadPosts(status, date);
                                    },
                                    error: function () {
                                        alert('Failed to delete post.');
                                    }
                                });
                            }
                        });
                    },
                    error: function () {
                        $('#posts').html('<p class="text-red-700">Failed to load posts.</p>');
                    }
                });
            }

            // Load analytics
            $.ajax({
                url: '/api/posts',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + authToken,
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (posts) {
                    const platforms = {};
                    posts.forEach(post => {
                        post.platforms.forEach(platform => {
                            platforms[platform.name] = (platforms[platform.name] || 0) + 1;
                        });
                    });
                    const $analytics = $('#analytics').empty();
                    for (const [name, count] of Object.entries(platforms)) {
                        $analytics.append(`<p>${name}: ${count} posts</p>`);
                    }
                },
                error: function () {
                    $('#analytics').html('<p class="text-red-700">Failed to load analytics.</p>');
                }
            });

            // Filter button
            $('#filterBtn').on('click', function () {
                const status = $('#statusFilter').val();
                const date = $('#dateFilter').val();
                loadPosts(status, date);
            });

            // Initial load
            loadPosts();
        });
    </script>
@endsection