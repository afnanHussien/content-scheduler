<?php
namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function getUserPosts(User $user, array $filters)
    {
        $query = $user->posts()->with('platforms');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('scheduled_time', $filters['date']);
        }

        return $query->latest()->paginate(10);
    }
    public function createPost(User $user, array $data): Post
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image_url'] = $data['image']->store('posts', 'public');
            unset($data['image']);
        }
        $post = $user->posts()->create([
            'title' => $data['title'],
            'content' => $data['content'],
            'image_url' => $data['image_url'] ?? null,
            'scheduled_time' => $data['scheduled_time'] ?? null,
            'status' => $data['status'],
        ]);
        
        $post->platforms()->attach(
            collect($data['platform_ids'])->mapWithKeys(fn($id) => [$id => ['platform_status' => 'pending']])->toArray()
        );

        return $post;
    }

    public function updatePost(Post $post, array $data): Post
    {
        if (!empty($data['remove_image']) && $post->image_url) {
            Storage::disk('public')->delete($post->image_url);
            $data['image_url'] = null;
        }
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image_url'] = $data['image']->store('posts', 'public');
            unset($data['image']);
        }
        $post->update($data);

        if (isset($data['platform_ids'])) {
            $post->platforms()->syncWithPivotValues($data['platform_ids'], ['platform_status' => 'pending']);
        }

        return $post;
    }
    public function deletePost(Post $post): void
    {
        $post->delete();
    }
}
