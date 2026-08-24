<?php

namespace App\Support;

class JsonList
{
    /**
     * Normalize legacy JSON list values into a flat string array.
     *
     * @return array<int, string>
     */
    public static function normalize(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return [];
            }

            if (self::looksLikeJson($trimmed)) {
                $decoded = json_decode($trimmed, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return self::normalize($decoded);
                }
            }

            if (str_contains($trimmed, ',')) {
                return array_values(array_filter(array_map('trim', explode(',', $trimmed))));
            }

            return [$trimmed];
        }

        if (! is_array($value)) {
            $text = trim((string) $value);

            return $text === '' ? [] : [$text];
        }

        $items = [];

        foreach ($value as $item) {
            if (is_string($item) && self::looksLikeJson($item)) {
                $items = array_merge($items, self::normalize($item));
                continue;
            }

            if (is_array($item)) {
                $items = array_merge($items, self::normalize($item));
                continue;
            }

            $text = trim((string) $item);
            if ($text !== '') {
                $items[] = $text;
            }
        }

        return array_values(array_unique($items));
    }

    public static function toPlainText(mixed $value, string $separator = ', '): ?string
    {
        $items = self::normalize($value);
        if ($items === []) {
            return null;
        }

        return implode($separator, $items);
    }

    public static function looksLikeJson(string $value): bool
    {
        $first = $value[0] ?? '';

        return $first === '[' || $first === '{' || $first === '"';
    }
}