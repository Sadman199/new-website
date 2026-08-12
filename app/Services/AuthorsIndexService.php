<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Author;
use App\Models\Broker;
use App\Models\Post;
use Illuminate\Support\Str;

class AuthorsIndexService
{
    /** @return array<string, mixed> */
    public function buildIndex(): array
    {
        $authors = $this->baseQuery()->orderBy('name')->get();

        return [
            'page' => [
                'title' => 'Our Editorial Team',
                'subtitle' => 'Meet the writers, editors, and fact-checkers behind every broker review and market article on BrokersCourt.',
            ],
            'authors' => $authors->map(fn (Author $author) => $this->serializeAuthor($author))->values()->all(),
            'how_we_work' => [
                [
                    'step' => '01',
                    'title' => 'Research',
                    'text' => 'We analyse regulation, fees, platforms, and verified user feedback before anything is published.',
                ],
                [
                    'step' => '02',
                    'title' => 'Write & edit',
                    'text' => 'Specialist writers draft reviews and guides; editors refine clarity, structure, and compliance.',
                ],
                [
                    'step' => '03',
                    'title' => 'Fact-check',
                    'text' => 'Key claims are cross-checked against regulator databases and broker disclosures.',
                ],
                [
                    'step' => '04',
                    'title' => 'Publish & update',
                    'text' => 'Content goes live with clear attribution — and is refreshed when brokers or rules change.',
                ],
            ],
        ];
    }

    /** @return array<string, mixed> */
    public function buildShow(string $slug): array
    {
        $author = Author::findByPublicSlug($slug);
        abort_if(! $author, 404);

        $author = $this->baseQuery()->find($author->id);
        abort_if(! $author, 404);

        $articles = Post::query()
            ->with('rSubCategory')
            ->where(function ($query) use ($author) {
                $query->where('written_by_author_id', $author->id)
                    ->orWhere('author_id', $author->id);
            })
            ->latest('id')
            ->take(12)
            ->get()
            ->map(fn (Post $post) => $this->serializeArticle($post))
            ->values()
            ->all();

        $brokerReviews = Broker::query()
            ->where(function ($query) use ($author) {
                $query->where('written_by_author_id', $author->id)
                    ->orWhere('edited_by_author_id', $author->id)
                    ->orWhere('fact_checked_by_author_id', $author->id);
            })
            ->where('is_scam', false)
            ->orderByDesc('rating')
            ->orderBy('name')
            ->take(12)
            ->get()
            ->map(fn (Broker $broker) => $this->serializeBrokerReview($broker, $author))
            ->values()
            ->all();

        $team = $this->baseQuery()
            ->where('id', '!=', $author->id)
            ->orderBy('name')
            ->take(4)
            ->get()
            ->map(fn (Author $member) => $this->serializeAuthor($member))
            ->values()
            ->all();

        return [
            'author' => $this->serializeAuthor($author, true),
            'articles' => $articles,
            'broker_reviews' => $brokerReviews,
            'team' => $team,
        ];
    }

    /** @return \Illuminate\Database\Eloquent\Builder<Author> */
    private function baseQuery()
    {
        return Author::query()->withCount([
            'postsWritten as written_posts_count',
            'postsEdited as edited_posts_count',
            'postsFactChecked as fact_checked_posts_count',
            'legacyPosts as legacy_posts_count',
        ]);
    }

    /** @return array<string, mixed> */
    private function serializeAuthor(Author $author, bool $detailed = false): array
    {
        $contributions = ($author->written_posts_count ?? 0)
            + ($author->edited_posts_count ?? 0)
            + ($author->fact_checked_posts_count ?? 0)
            + ($author->legacy_posts_count ?? 0);

        $payload = [
            'id' => $author->id,
            'slug' => $author->publicSlug(),
            'name' => $author->name,
            'bio' => trim((string) ($author->bio ?: '')),
            'photo' => $author->photoUrl(),
            'roles' => $author->roleLabels(),
            'profile_url' => $author->profileUrl(),
            'social' => $author->socialLinks(),
            'contributions' => [
                'written' => (int) ($author->written_posts_count ?? 0) + (int) ($author->legacy_posts_count ?? 0),
                'edited' => (int) ($author->edited_posts_count ?? 0),
                'fact_checked' => (int) ($author->fact_checked_posts_count ?? 0),
                'total' => $contributions,
            ],
        ];

        if ($detailed) {
            $payload['primary_role'] = $this->primaryRoleLabel($author);
            $payload['broker_reviews_count'] = Broker::query()
                ->where(function ($query) use ($author) {
                    $query->where('written_by_author_id', $author->id)
                        ->orWhere('edited_by_author_id', $author->id)
                        ->orWhere('fact_checked_by_author_id', $author->id);
                })
                ->count();
        }

        return $payload;
    }

    /** @return array<string, mixed> */
    private function serializeArticle(Post $post): array
    {
        $sub = $post->rSubCategory;
        $subSlug = $sub?->slug;
        $postSlug = $post->slug;

        return [
            'title' => $post->post_title,
            'excerpt' => Str::limit(trim(strip_tags((string) $post->post_detail)), 140),
            'category' => $sub?->sub_category_name,
            'url' => ($subSlug && $postSlug)
                ? route('news_detail', ['subcategory_slug' => $subSlug, 'post_slug' => $postSlug])
                : null,
            'photo' => $post->post_photo ? asset('uploads/' . $post->post_photo) : null,
        ];
    }

    /** @return array<string, mixed> */
    private function serializeBrokerReview(Broker $broker, Author $author): array
    {
        $roles = [];
        if ((int) $broker->written_by_author_id === (int) $author->id) {
            $roles[] = 'Written';
        }
        if ((int) $broker->edited_by_author_id === (int) $author->id) {
            $roles[] = 'Edited';
        }
        if ((int) $broker->fact_checked_by_author_id === (int) $author->id) {
            $roles[] = 'Fact-checked';
        }

        return [
            'name' => $broker->name,
            'rating' => $broker->rating !== null ? round((float) $broker->rating, 1) : null,
            'logo' => $broker->logo ? asset($broker->logo) : null,
            'roles' => $roles,
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
        ];
    }

    private function primaryRoleLabel(Author $author): string
    {
        if ($author->can_write) {
            return 'Writer';
        }
        if ($author->can_edit) {
            return 'Editor';
        }
        if ($author->can_fact_check) {
            return 'Fact-checker';
        }

        return 'Contributor';
    }
}
