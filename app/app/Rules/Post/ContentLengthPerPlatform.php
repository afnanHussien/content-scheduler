<?php

namespace App\Rules\Post;

use Closure;
use App\Models\Platform;
use Illuminate\Contracts\Validation\ValidationRule;

class ContentLengthPerPlatform implements ValidationRule
{
    protected $platformIds;

    public function __construct(array $platformIds = [])
    {
        $this->platformIds = $platformIds;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $platforms = Platform::whereIn('id', $this->platformIds)->get();
        foreach ($platforms as $platform) {
            $limit = match ($platform->type) {
                'twitter' => 280,
                'linkedin' => 3000,
                'instagram' => 2200,
                default => 3500,
            };

            if (mb_strlen($value) > $limit) {
                $fail("The content exceeds the maximum length for {$platform->name} ({$limit} characters).");
                return;
            }
        }
    }
}
