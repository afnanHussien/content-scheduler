<?php

namespace App\Rules\Post;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class DailyScheduledLimit implements ValidationRule
{
    protected $postId;

    public function __construct($postId = null)
    {
        $this->postId = $postId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();

        if (!$user) {
            $fail('Unauthorized.');
            return;
        }

        $count = $user->posts()
            ->where('status', 'scheduled')
            ->whereDate('scheduled_time', $value)
            ->when($this->postId, fn($query) => $query->where('id', '!=', $this->postId))
            ->count();

        if ($count >= 10) {
            $fail('You have reached the limit of 10 scheduled posts for this day.');
            return;
        }
    }
}
