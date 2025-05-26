@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Create a New Post</h3>

    <form id="postForm" enctype="multipart/form-data">
        @csrf
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
            <select name="status" class="form-select" required>
                <option value="draft" selected>Draft</option>
                <option value="scheduled">Scheduled</option>
            </select>
        </div>

        <div id="formErrors" class="text-danger mb-3 d-none"></div>

        <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
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
    $.ajax({
        url: '/api/platforms',
        type: 'GET',
        success: function (response) {
            const platforms = response.platforms || [];
            const container = $('#platformCheckboxes');
            platforms.forEach(function (platform) {
                const checkbox = `
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="platform_ids[]" value="${platform.id}" id="platform-${platform.id}">
                        <label class="form-check-label" for="platform-${platform.id}">${platform.name}</label>
                    </div>`;
                container.append(checkbox);
            });
        },
        error: function () {
            $('#platformCheckboxes').html('<span class="text-danger">Failed to load platforms.</span>');
        }
    });
    $('#postForm').submit(function (e) {
        e.preventDefault();
        $('#formErrors').addClass('d-none').empty();

        const formData = new FormData(this);
        $('input[name="platform_ids[]"]:checked').each(function () {
            formData.append('platform_ids[]', $(this).val());
        });
        $.ajax({
            url: '/api/posts',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                alert('Post created successfully!');
                window.location.href = '/dashboard'; // or posts list
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Failed to create post.';
                $('#formErrors').removeClass('d-none').text(message);
            }
        });
    });
});
</script>
@endsection
