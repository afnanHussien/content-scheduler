@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Edit Post</h3>

    <form id="editPostForm" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5" required></textarea>
            <small class="text-muted"><span id="charCount">0</span> characters</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Image (optional)</label>
            <div id="existingImageContainer"></div>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Platforms</label><br>
            <div id="platformCheckboxes"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Schedule Time</label>
            <input type="datetime-local" name="scheduled_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="draft">Draft</option>
                <option value="scheduled">Scheduled</option>
            </select>
        </div>

        <div id="formErrors" class="text-danger mb-3 d-none"></div>

        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    const postId = {{ $postId }};
    
    // Load platforms first
    $.ajax({
        url: '/api/platforms',
        method: 'GET',
        success: function (res) {
            const container = $('#platformCheckboxes');
            res.platforms.forEach(function (platform) {
                container.append(`
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="platform_ids[]" value="${platform.id}" id="platform-${platform.id}">
                        <label class="form-check-label" for="platform-${platform.id}">${platform.name}</label>
                    </div>
                `);
            });

            // After platforms loaded, load the post data
            loadPostData();
        },
        error: function () {
            $('#platformCheckboxes').html('<span class="text-danger">Failed to load platforms.</span>');
        }
    });

    function loadPostData() {
        $.ajax({
            url: `/api/posts/${postId}`,
            method: 'GET',
            success: function (post) {
                $('input[name="title"]').val(post.title);
                $('textarea[name="content"]').val(post.content).trigger('input');
                $('input[name="scheduled_time"]').val(post.scheduled_time.replace(' ', 'T'));
                $('select[name="status"]').val(post.status);
                if (post.image_url) {
                    $('#existingImageContainer').html(`
                        <div class="mb-3">
                            <label>Current Image:</label><br>
                            <img src="${post.image_url}" alt="Post Image" width="200">
                        </div>
                        <input type="checkbox" name="remove_image" id="remove_image" value="1">
                        <label for="remove_image">Remove Image</label>
                    `);
                }
                post.platforms.forEach(function (p) {
                    $(`#platform-${p.id}`).prop('checked', true);
                });
            },
            error: function () {
                alert('Failed to load post data.');
            }
        });
    }

    $('textarea[name="content"]').on('input', function () {
        $('#charCount').text($(this).val().length);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        xhrFields: {
            withCredentials: true
        }
    });

    $('#editPostForm').submit(function (e) {
        e.preventDefault();
        $('#formErrors').addClass('d-none').empty();

        const formData = new FormData(this);

        $.ajax({
            url: `/api/posts/${postId}`,
            method: 'POST', // use POST + _method=PATCH for Laravel
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                alert('Post updated successfully!');
                window.location.href = '/posts';
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    let errorMessages = Object.values(errors).flat().join('\n');
                    $('#formErrors').removeClass('d-none').text(errorMessages);
                } else {
                    $('#formErrors').removeClass('d-none').text('Failed to update post.');
                }
            }
        });
    });
});
</script>
@endsection
