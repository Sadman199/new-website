<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class EditorialAssignmentService
{
    public const ROLE_WRITTEN = 'written';
    public const ROLE_EDITED = 'edited';
    public const ROLE_FACT_CHECKED = 'fact_checked';

    /** @return array<string, string> */
    public static function roleLabels(): array
    {
        return [
            self::ROLE_WRITTEN => 'Written',
            self::ROLE_EDITED => 'Edited',
            self::ROLE_FACT_CHECKED => 'Fact-Checked',
        ];
    }

    /**
     * @return array<int, array{value: string, label: string, group: string}>
     */
    public static function assigneeOptions(string $role): array
    {
        $options = [];
        $authorQuery = Author::query()->orderBy('name');

        if ($role === self::ROLE_WRITTEN) {
            $authorQuery->writers();
        } elseif ($role === self::ROLE_EDITED) {
            $authorQuery->editors();
        } else {
            $authorQuery->factCheckers();
        }

        foreach ($authorQuery->get(['id', 'name']) as $author) {
            $options[] = [
                'value' => 'author:' . $author->id,
                'label' => $author->name,
                'group' => 'Authors',
            ];
        }

        foreach (Admin::query()->orderBy('name')->get(['id', 'name']) as $admin) {
            $options[] = [
                'value' => 'admin:' . $admin->id,
                'label' => $admin->name,
                'group' => 'Admins',
            ];
        }

        return $options;
    }

    /** @return array<string, array<int, array{value: string, label: string, group: string}>> */
    public static function allAssigneeOptions(): array
    {
        return [
            self::ROLE_WRITTEN => self::assigneeOptions(self::ROLE_WRITTEN),
            self::ROLE_EDITED => self::assigneeOptions(self::ROLE_EDITED),
            self::ROLE_FACT_CHECKED => self::assigneeOptions(self::ROLE_FACT_CHECKED),
        ];
    }

    public static function assigneeValueForPost(Post $post, string $role): ?string
    {
        return self::assigneeValueFor($post, $role);
    }

    public static function assigneeValueFor(Model $model, string $role): ?string
    {
        $authorId = $model->{$role . '_by_author_id'} ?? null;
        $adminId = $model->{$role . '_by_admin_id'} ?? null;

        if ($authorId) {
            return 'author:' . $authorId;
        }

        if ($adminId) {
            return 'admin:' . $adminId;
        }

        return null;
    }

    public static function applyAssignee(Post $post, string $role, ?string $assignee): void
    {
        self::applyAssigneeTo($post, $role, $assignee);
    }

    public static function applyAssigneeTo(Model $model, string $role, ?string $assignee): void
    {
        $authorColumn = $role . '_by_author_id';
        $adminColumn = $role . '_by_admin_id';

        $model->{$authorColumn} = null;
        $model->{$adminColumn} = null;

        if (! $assignee) {
            return;
        }

        [$type, $id] = array_pad(explode(':', $assignee, 2), 2, null);
        $id = $id ? (int) $id : null;

        if ($type === 'author' && $id) {
            $model->{$authorColumn} = $id;
        } elseif ($type === 'admin' && $id) {
            $model->{$adminColumn} = $id;
        }
    }

    public static function applyFromRequest(Model $model, \Illuminate\Http\Request $request): void
    {
        self::applyAssigneeTo($model, self::ROLE_WRITTEN, $request->input('written_assignee'));
        self::applyAssigneeTo($model, self::ROLE_EDITED, $request->input('edited_assignee'));
        self::applyAssigneeTo($model, self::ROLE_FACT_CHECKED, $request->input('fact_checked_assignee'));
    }

    /** @return array<int, array{role: string, label: string, name: string}> */
    public static function creditsForPost(Post $post): array
    {
        return self::creditsFor($post);
    }

    /** @return array<int, array{role: string, label: string, name: string}> */
    public static function creditsFor(Model $model): array
    {
        $credits = [];

        foreach (self::roleLabels() as $role => $label) {
            $name = self::resolveName($model, $role);
            if ($name) {
                $credits[] = [
                    'role' => $role,
                    'label' => $label,
                    'name' => $name,
                ];
            }
        }

        return $credits;
    }

    /** @return array<int, array{role: string, label: string, name: string, bio: ?string, photo: ?string}> */
    public static function teamFor(Model $model): array
    {
        $team = [];

        foreach (self::roleLabels() as $role => $label) {
            $authorId = $model->{$role . '_by_author_id'} ?? null;
            $adminId = $model->{$role . '_by_admin_id'} ?? null;

            if ($authorId) {
                $author = self::authorWithCounts((int) $authorId);

                if ($author) {
                    $team[] = self::serializeGuideAuthor($author, $role, $label);
                    continue;
                }
            }

            if ($adminId) {
                $relation = self::adminRelationName($role);
                $admin = ($relation && $model->relationLoaded($relation) && $model->{$relation})
                    ? $model->{$relation}
                    : Admin::query()->find($adminId);

                if ($admin) {
                    $team[] = [
                        'role' => $role,
                        'label' => $label,
                        'name' => $admin->name,
                        'bio' => null,
                        'photo' => null,
                        'contributions' => [
                            'written' => 0,
                            'edited' => 0,
                            'fact_checked' => 0,
                        ],
                        'social' => [],
                    ];
                }
            }
        }

        return $team;
    }

    public static function resolveName(Model $model, string $role): ?string
    {
        $authorId = $model->{$role . '_by_author_id'} ?? null;
        $adminId = $model->{$role . '_by_admin_id'} ?? null;

        if ($authorId) {
            $relation = self::authorRelationName($role);
            if ($relation && $model->relationLoaded($relation) && $model->{$relation}) {
                return $model->{$relation}->name;
            }

            return Author::query()->whereKey($authorId)->value('name');
        }

        if ($adminId) {
            $relation = self::adminRelationName($role);
            if ($relation && $model->relationLoaded($relation) && $model->{$relation}) {
                return $model->{$relation}->name;
            }

            return Admin::query()->whereKey($adminId)->value('name');
        }

        if ($role === self::ROLE_WRITTEN && $model instanceof Post) {
            if ($model->author_id && $model->author_id != 0) {
                return $model->author?->name;
            }
            if ($model->admin_id) {
                return Admin::query()->whereKey($model->admin_id)->value('name');
            }
        }

        return null;
    }

    public static function primaryWriterName(Model $model): ?string
    {
        return self::resolveName($model, self::ROLE_WRITTEN);
    }

    /** @return array<int, array{role: string, label: string, name: string, bio: ?string, photo: ?string}> */
    public static function guideTeamFor(?Model $broker = null): array
    {
        if ($broker instanceof Model) {
            $team = self::teamFor($broker);

            if ($team !== []) {
                return $team;
            }
        }

        return self::defaultGuideTeam();
    }

    /** @return array<int, array{role: string, label: string, name: string}> */
    public static function guideCreditsFor(?Model $broker = null): array
    {
        if ($broker instanceof Model) {
            $credits = self::creditsFor($broker);

            if ($credits !== []) {
                return $credits;
            }
        }

        return self::defaultGuideCredits();
    }

    /** @return array{role: string, label: string, name: string, bio: ?string, photo: ?string}|null */
    public static function primaryGuideAuthor(?Model $broker = null): ?array
    {
        $team = self::guideTeamFor($broker);

        foreach ($team as $member) {
            if (($member['role'] ?? '') === self::ROLE_WRITTEN) {
                return $member;
            }
        }

        return $team[0] ?? null;
    }

    /** @return array<int, array{role: string, label: string, name: string, bio: ?string, photo: ?string}> */
    public static function defaultGuideTeam(): array
    {
        $team = [];

        foreach (self::roleLabels() as $role => $label) {
            $author = self::defaultAuthorForRole($role);

            if ($author) {
                $team[] = self::serializeGuideAuthor($author, $role, $label);
            }
        }

        return $team;
    }

    /** @return array<int, array{role: string, label: string, name: string}> */
    public static function defaultGuideCredits(): array
    {
        $credits = [];

        foreach (self::roleLabels() as $role => $label) {
            $author = self::defaultAuthorForRole($role);

            if ($author) {
                $credits[] = [
                    'role' => $role,
                    'label' => $label,
                    'name' => $author->name,
                ];
            }
        }

        return $credits;
    }

    protected static function defaultAuthorForRole(string $role): ?Author
    {
        $query = Author::query()->orderBy('name');

        if ($role === self::ROLE_WRITTEN) {
            $query->writers();
        } elseif ($role === self::ROLE_EDITED) {
            $query->editors();
        } else {
            $query->factCheckers();
        }

        return $query->withCount([
            'postsWritten as written_posts_count',
            'postsEdited as edited_posts_count',
            'postsFactChecked as fact_checked_posts_count',
        ])->first();
    }

    protected static function authorWithCounts(int $authorId): ?Author
    {
        return Author::query()
            ->withCount([
                'postsWritten as written_posts_count',
                'postsEdited as edited_posts_count',
                'postsFactChecked as fact_checked_posts_count',
            ])
            ->find($authorId);
    }

    /** @return array{role: string, label: string, name: string, bio: ?string, photo: ?string, contributions: array{written: int, edited: int, fact_checked: int}} */
    protected static function serializeGuideAuthor(Author $author, string $role, string $label): array
    {
        return [
            'role' => $role,
            'label' => $label,
            'name' => $author->name,
            'bio' => $author->bio,
            'photo' => $author->photoUrl(),
            'contributions' => [
                'written' => (int) ($author->written_posts_count ?? 0),
                'edited' => (int) ($author->edited_posts_count ?? 0),
                'fact_checked' => (int) ($author->fact_checked_posts_count ?? 0),
            ],
            'social' => $author->socialLinks(),
        ];
    }

    protected static function authorRelationName(string $role): ?string
    {
        return match ($role) {
            self::ROLE_WRITTEN => 'writtenByAuthor',
            self::ROLE_EDITED => 'editedByAuthor',
            self::ROLE_FACT_CHECKED => 'factCheckedByAuthor',
            default => null,
        };
    }

    protected static function adminRelationName(string $role): ?string
    {
        return match ($role) {
            self::ROLE_WRITTEN => 'writtenByAdmin',
            self::ROLE_EDITED => 'editedByAdmin',
            self::ROLE_FACT_CHECKED => 'factCheckedByAdmin',
            default => null,
        };
    }
}
