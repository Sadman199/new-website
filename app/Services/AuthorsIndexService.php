<?php

namespace App\Services;

use App\Models\Author;

class AuthorsIndexService
{
    /** @return array<string, mixed> */
    public function buildIndex(): array
    {
        $authors = Author::query()
            ->withCount([
                'postsWritten as written_posts_count',
                'postsEdited as edited_posts_count',
                'postsFactChecked as fact_checked_posts_count',
            ])
            ->orderBy('name')
            ->get();

        $serialized = $authors->map(fn (Author $author) => $this->serializeAuthor($author))->values()->all();

        return [
            'page' => [
                'title' => 'Our Editorial Team',
                'subtitle' => 'Meet the writers, editors, and fact-checkers behind every broker review and market article on BrokersCourt.',
            ],
            'authors' => $serialized,
            'stats' => $this->stats($authors),
        ];
    }

    /** @return array<string, mixed> */
    private function serializeAuthor(Author $author): array
    {
        $contributions = ($author->written_posts_count ?? 0)
            + ($author->edited_posts_count ?? 0)
            + ($author->fact_checked_posts_count ?? 0);

        return [
            'id' => $author->id,
            'name' => $author->name,
            'email' => $author->email,
            'bio' => $author->bio,
            'photo' => $author->photoUrl(),
            'roles' => $author->roleLabels(),
            'contributions' => [
                'written' => (int) ($author->written_posts_count ?? 0),
                'edited' => (int) ($author->edited_posts_count ?? 0),
                'fact_checked' => (int) ($author->fact_checked_posts_count ?? 0),
                'total' => $contributions,
            ],
        ];
    }

    /** @return array<string, int> */
    private function stats($authors): array
    {
        return [
            'total_authors' => $authors->count(),
            'writers' => $authors->where('can_write', true)->count(),
            'editors' => $authors->where('can_edit', true)->count(),
            'fact_checkers' => $authors->where('can_fact_check', true)->count(),
            'total_contributions' => $authors->sum(function (Author $author) {
                return ($author->written_posts_count ?? 0)
                    + ($author->edited_posts_count ?? 0)
                    + ($author->fact_checked_posts_count ?? 0);
            }),
        ];
    }
}
