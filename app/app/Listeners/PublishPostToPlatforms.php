<?php

namespace App\Listeners;

use App\Events\PostReadyToPublish;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishPostToPlatforms
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostReadyToPublish $event): void
    {
        $post = $event->post;

        foreach ($post->platforms as $platform) {
            // Mock publishing
            Log::info("Published post {$post->id} to {$platform->name}");

            $platform->pivot->update(['platform_status' => 'published']);
        }

        $post->update(['status' => 'published']);
    }
}
