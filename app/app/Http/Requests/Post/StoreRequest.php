<?php

namespace App\Http\Requests\Post;

use App\Rules\Post\DailyScheduledLimit;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Post\ContentLengthPerPlatform;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'content' => [
                'required',
                new ContentLengthPerPlatform($this->input('platform_ids', [])),
            ],
            'image_url' => 'nullable|url',
            'scheduled_time' => ['required', 'date', 'after:now', new DailyScheduledLimit()],
            'status' => 'required|in:draft,scheduled',
            'platform_ids' => 'required|array|min:1',
            'platform_ids.*' => 'exists:platforms,id',
        ];
    }
}
