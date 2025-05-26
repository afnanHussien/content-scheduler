<?php

namespace App\Http\Requests\Post;

use App\Rules\Post\DailyScheduledLimit;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Post\ContentLengthPerPlatform;

class UpdateRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'content' => [
                'sometimes',
                'required',
                new ContentLengthPerPlatform($this->input('platform_ids', [])),
            ],
            // 'image_url' => 'nullable|url',
            'remove_image' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
            'scheduled_time' => ['sometimes', 'required', 'date', 'after:now', new DailyScheduledLimit($this->route('post')->id ?? null)],
            'status' => 'sometimes|required|in:draft,scheduled',
            'platform_ids' => 'sometimes|required|array|min:1',
            'platform_ids.*' => 'exists:platforms,id',
        ];
    }
}
