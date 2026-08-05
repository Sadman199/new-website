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
                $relation = self::authorRelationName($role);
                $author = ($relation && $model->relationLoaded($relation) && $model->{$relation})
                    ? $model->{$relation}
                    : Author::query()->find($authorId);

                if ($author) {
                    $team[] = [
                        'role' => $role,
                        'label' => $label,
                        'name' => $author->name,
                        'bio' => $author->bio,
                        'photo' => $author->photoUrl(),
                    ];
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
