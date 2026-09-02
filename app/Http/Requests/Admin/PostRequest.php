<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        if ($this->filled('slug')) {
            $this->merge(['slug' => trim((string) $this->input('slug'))]);
        }

        if ($this->exists('excerpt')) {
            $clean = trim((string) preg_replace('/\s+/u', ' ', strip_tags((string) $this->input('excerpt'))));
            $this->merge(['excerpt' => $clean !== '' ? mb_substr($clean, 0, 500) : null]);
        }

        foreach ([
            'is_share', 'is_featured', 'is_breaking', 'featured_homepage',
            'featured_blog', 'is_editors_pick', 'is_popular', 'show_author', 'show_related_posts',
            'robots_index', 'robots_follow',
        ] as $flag) {
            if (! $this->exists($flag)) {
                $this->merge([$flag => 0]);
            }
        }
    }

    public function rules(): array
    {
        $postId = $this->route('id');
        $isCreate = $this->routeIs('admin_post_store');

        $imageRules = ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,avif', 'max:4096'];

        return [
            'post_title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:191',
                Rule::unique('posts', 'slug')->ignore($postId),
            ],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'post_detail' => ['required', 'string'],
            'sub_category_id' => ['required', 'integer', 'exists:sub_categories,id'],
            'language_id' => ['required', 'integer', 'exists:languages,id'],
            'content_type' => ['nullable', 'string', 'max:80'],
            'status' => ['nullable', 'string', Rule::in(array_keys(Post::statuses()))],
            'publish_at' => ['nullable', 'date'],
            'scheduled_at' => ['nullable', 'date', 'required_if:status,scheduled'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'author' => ['nullable', 'string', 'max:255'],
            'written_assignee' => ['nullable', 'string', 'max:50'],
            'edited_assignee' => ['nullable', 'string', 'max:50'],
            'fact_checked_assignee' => ['nullable', 'string', 'max:50'],
            'post_photo' => array_merge($isCreate ? ['required'] : ['nullable'], array_slice($imageRules, 1)),
            'og_image' => $imageRules,
            'social_image' => $imageRules,
            'image_alt' => ['nullable', 'string', 'max:255'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'focus_keyword' => ['nullable', 'string', 'max:120'],
            'canonical_url' => ['nullable', 'url', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'schema_type' => ['nullable', 'string', Rule::in(array_keys(Post::schemaTypes()))],
            'robots_index' => ['nullable', 'boolean'],
            'robots_follow' => ['nullable', 'boolean'],
            'is_share' => ['nullable', 'boolean'],
            'is_comment' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_breaking' => ['nullable', 'boolean'],
            'featured_homepage' => ['nullable', 'boolean'],
            'featured_blog' => ['nullable', 'boolean'],
            'is_editors_pick' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'show_author' => ['nullable', 'boolean'],
            'show_related_posts' => ['nullable', 'boolean'],
            'broker_ids' => ['nullable', 'array'],
            'broker_ids.*' => ['integer', 'exists:brokers,id'],
            'related_post_ids' => ['nullable', 'array', 'max:6'],
            'related_post_ids.*' => array_values(array_filter([
                'integer',
                'exists:posts,id',
                $postId ? Rule::notIn([(int) $postId]) : null,
            ])),
            'related_category_ids' => ['nullable', 'array'],
            'related_category_ids.*' => ['integer', 'exists:categories,id'],
            'subscriber_send_option' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'post_photo.required' => 'Please upload a featured image.',
            'post_photo.mimes' => 'Featured image must be JPG, PNG, GIF, WebP, or AVIF.',
            'scheduled_at.required_if' => 'Set a scheduled date when the status is Scheduled.',
            'related_post_ids.max' => 'You can attach at most 6 related articles.',
            'related_post_ids.*.not_in' => 'A blog cannot be related to itself.',
            'canonical_url.url' => 'Canonical URL must be a valid URL.',
        ];
    }
}
