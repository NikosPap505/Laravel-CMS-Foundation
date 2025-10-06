<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required|string',
            'body' => 'required|string',
            'featured_image_id' => 'nullable|exists:media,id',
            'status' => 'required|in:draft,published,scheduled',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ];

        // If status is scheduled, published_at is required and must be in the future
        if ($this->status === 'scheduled') {
            $rules['published_at'] = 'required|date|after:now';
        } else {
            $rules['published_at'] = 'nullable|date';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The post title is required.',
            'title.max' => 'The post title may not be greater than 255 characters.',
            'slug.required' => 'The post slug is required.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'category_id.required' => 'Please select a category for this post.',
            'category_id.exists' => 'The selected category does not exist.',
            'excerpt.required' => 'The post excerpt is required.',
            'body.required' => 'The post content is required.',
            'featured_image_id.exists' => 'The selected featured image does not exist.',
            'status.required' => 'Please select a status for this post.',
            'status.in' => 'The status must be either draft, published, or scheduled.',
            'published_at.required' => 'A publish date is required for scheduled posts.',
            'published_at.date' => 'The publish date must be a valid date.',
            'published_at.after' => 'The publish date must be in the future for scheduled posts.',
            'meta_title.max' => 'The meta title may not be greater than 255 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-set published_at if status is published and no date is provided
        if ($this->status === 'published' && empty($this->published_at)) {
            $this->merge([
                'published_at' => now(),
            ]);
        }
    }
}