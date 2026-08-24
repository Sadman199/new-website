<?php

namespace App\Support;

class RichText
{
    /**
     * Convert legacy Summernote / HTML fragments to plain text for tables and labels.
     */
    public static function toPlainText(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = trim($html);
        if ($html === '') {
            return null;
        }

        if (JsonList::looksLikeJson($html)) {
            $list = JsonList::toPlainText($html);
            if ($list !== null) {
                return $list;
            }
        }

        $html = self::removeEmptyParagraphs($html);
        $html = preg_replace('/<\/?(?:p|div|br|li|h[1-6])[^>]*>/i', ' ', $html) ?? $html;

        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text) ?? '';
        $text = trim($text);

        if ($text === '' || $text === '—') {
            return null;
        }

        return $text;
    }

    /**
     * Clean HTML for safe inline display while keeping basic formatting.
     */
    public static function forDisplay(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = trim($html);
        if ($html === '') {
            return null;
        }

        if (JsonList::looksLikeJson($html)) {
            return JsonList::toPlainText($html);
        }

        $html = self::removeEmptyParagraphs($html);
        $html = preg_replace('/\s*(?:dir|style|class|data-[a-z0-9-]+)="[^"]*"/i', '', $html) ?? $html;
        $html = trim($html);

        $plain = self::toPlainText($html);
        if ($plain === null) {
            return null;
        }

        // Single wrapping paragraph (or no tags) → plain text with entities decoded.
        if (! preg_match('/<(?!\/?(?:a|b|strong|em|i|u|br)\b)[^>]+>/i', $html)) {
            return $plain;
        }

        if (preg_match('/^<p>(.*)<\/p>$/is', $html, $matches) && strip_tags($matches[1]) === $matches[1]) {
            return $plain;
        }

        return $html;
    }

    /** @return array<int, string> */
    public static function listItems(?string $html): array
    {
        if ($html === null || trim($html) === '') {
            return [];
        }

        preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $matches);
        $items = array_map(
            static fn ($item) => self::toPlainText($item) ?? '',
            $matches[1] ?? []
        );

        $items = array_values(array_filter($items));

        if ($items !== []) {
            return $items;
        }

        $jsonItems = JsonList::normalize($html);
        if ($jsonItems !== []) {
            return $jsonItems;
        }

        $plain = self::toPlainText($html);

        return $plain ? [$plain] : [];
    }

    private static function removeEmptyParagraphs(string $html): string
    {
        $previous = null;

        while ($previous !== $html) {
            $previous = $html;
            $html = preg_replace('/<p[^>]*>\s*(?:&nbsp;|\x{00A0})?\s*<\/p>/iu', '', $html) ?? $html;
        }

        return $html;
    }
}
