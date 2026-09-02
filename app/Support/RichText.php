<?php

namespace App\Support;

class RichText
{
    private const ALLOWED_TAGS = [
        'p' => true, 'br' => true, 'span' => true, 'div' => true,
        'h2' => true, 'h3' => true, 'h4' => true, 'h5' => true,
        'ul' => true, 'ol' => true, 'li' => true,
        'a' => true, 'img' => true,
        'table' => true, 'thead' => true, 'tbody' => true, 'tfoot' => true,
        'tr' => true, 'th' => true, 'td' => true, 'caption' => true,
        'blockquote' => true, 'pre' => true, 'code' => true,
        'strong' => true, 'b' => true, 'em' => true, 'i' => true,
        'u' => true, 's' => true, 'strike' => true, 'hr' => true,
        'sub' => true, 'sup' => true,
    ];

    private const ALLOWED_CLASSES = [
        'table' => true,
        'table-bordered' => true,
        'text-left' => true,
        'text-center' => true,
        'text-right' => true,
        'text-justify' => true,
    ];

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
     * Clean HTML for safe frontend display while keeping formatting.
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
        $html = self::sanitize($html);
        if ($html === null || $html === '') {
            return null;
        }

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

    /**
     * Allowlist HTML produced by the admin editor.
     */
    public static function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = trim($html);
        if ($html === '') {
            return null;
        }

        if (! class_exists(\DOMDocument::class)) {
            return strip_tags($html, '<p><br><span><h2><h3><h4><ul><ol><li><a><img><table><thead><tbody><tr><th><td><blockquote><pre><code><strong><b><em><i><u><s><hr>');
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $wrapped = '<div id="rte-root">'.$html.'</div>';
        $loaded = $dom->loadHTML('<?xml encoding="UTF-8">'.$wrapped, LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return strip_tags($html, '<p><br><span><h2><h3><h4><ul><ol><li><a><img><table><thead><tbody><tr><th><td><blockquote><pre><code><strong><b><em><i><u><s><hr>');
        }

        $root = $dom->getElementById('rte-root');
        if (! $root) {
            return null;
        }

        self::sanitizeNode($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        $out = self::removeEmptyParagraphs(trim($out));

        return $out === '' ? null : $out;
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

    private static function sanitizeNode(\DOMNode $node, int $depth = 0): void
    {
        if ($depth > 40) {
            return;
        }

        $again = false;
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child instanceof \DOMElement) {
                $tag = strtolower($child->tagName);

                if ($tag === 'font') {
                    self::unwrapFont($child);
                    $again = true;
                    continue;
                }

                if (! isset(self::ALLOWED_TAGS[$tag])) {
                    self::unwrapNode($child);
                    $again = true;
                    continue;
                }

                self::sanitizeAttributes($child, $tag);
                if ($child->parentNode) {
                    self::sanitizeNode($child, $depth + 1);
                } else {
                    $again = true;
                }
                continue;
            }

            if ($child instanceof \DOMComment) {
                $child->parentNode?->removeChild($child);
            }
        }

        if ($again) {
            self::sanitizeNode($node, $depth + 1);
        }
    }

    private static function unwrapFont(\DOMElement $node): void
    {
        $color = trim((string) $node->getAttribute('color'));
        $span = $node->ownerDocument?->createElement('span');
        if (! $span || ! $node->parentNode) {
            self::unwrapNode($node);

            return;
        }

        if ($color !== '' && self::isSafeColor($color)) {
            $span->setAttribute('style', 'color: '.$color);
        }

        while ($node->firstChild) {
            $span->appendChild($node->firstChild);
        }

        $node->parentNode->replaceChild($span, $node);
        self::sanitizeNode($span);
    }

    private static function unwrapNode(\DOMNode $node): void
    {
        $parent = $node->parentNode;
        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }

    private static function sanitizeAttributes(\DOMElement $node, string $tag): void
    {
        $keep = [];

        if ($tag === 'a') {
            $href = self::safeUrl($node->getAttribute('href'), ['http', 'https', 'mailto']);
            if ($href !== null) {
                $keep['href'] = $href;
                $keep['rel'] = 'noopener noreferrer';
                if (in_array(strtolower($node->getAttribute('target')), ['_blank', '_self'], true)) {
                    $keep['target'] = strtolower($node->getAttribute('target'));
                }
            }
            $title = trim($node->getAttribute('title'));
            if ($title !== '') {
                $keep['title'] = $title;
            }
        }

        if ($tag === 'img') {
            $src = self::safeUrl($node->getAttribute('src'), ['http', 'https']);
            if ($src === null && self::isSafeDataImage($node->getAttribute('src'))) {
                $src = $node->getAttribute('src');
            }
            if ($src !== null) {
                $keep['src'] = $src;
                $alt = trim($node->getAttribute('alt'));
                $keep['alt'] = $alt;
            }
        }

        if (in_array($tag, ['td', 'th'], true)) {
            foreach (['colspan', 'rowspan'] as $attr) {
                $value = trim($node->getAttribute($attr));
                if ($value !== '' && ctype_digit($value)) {
                    $keep[$attr] = $value;
                }
            }
        }

        $style = self::safeStyle($node->getAttribute('style'));
        if ($style !== null) {
            $keep['style'] = $style;
        }

        $class = self::safeClass($node->getAttribute('class'));
        if ($class !== null) {
            $keep['class'] = $class;
        }

        while ($node->attributes->length > 0) {
            $node->removeAttribute($node->attributes->item(0)->nodeName);
        }

        foreach ($keep as $name => $value) {
            $node->setAttribute($name, $value);
        }

        if ($tag === 'img' && ! isset($keep['src'])) {
            $node->parentNode?->removeChild($node);
        }

        if ($tag === 'a' && ! isset($keep['href'])) {
            self::unwrapNode($node);
        }
    }

    private static function safeUrl(string $url, array $schemes): ?string
    {
        $url = trim(html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($url === '') {
            return null;
        }

        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        if (str_starts_with($url, '#')) {
            return $url;
        }

        $parts = parse_url($url);
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        if (! in_array($scheme, $schemes, true)) {
            return null;
        }

        return $url;
    }

    private static function isSafeDataImage(string $src): bool
    {
        return (bool) preg_match('#^data:image/(?:png|jpe?g|gif|webp);base64,[a-z0-9+/]+=*$#i', trim($src));
    }

    private static function safeStyle(string $style): ?string
    {
        $style = trim($style);
        if ($style === '') {
            return null;
        }

        $kept = [];
        foreach (explode(';', $style) as $declaration) {
            if (! str_contains($declaration, ':')) {
                continue;
            }
            [$prop, $value] = array_map('trim', explode(':', $declaration, 2));
            $prop = strtolower($prop);
            $value = trim($value);
            if ($value === '' || preg_match('/expression|javascript|url\s*\(/i', $value)) {
                continue;
            }

            if ($prop === 'text-align' && in_array(strtolower($value), ['left', 'center', 'right', 'justify'], true)) {
                $kept[] = 'text-align: '.strtolower($value);
            }

            if (in_array($prop, ['color', 'background-color'], true) && self::isSafeColor($value)) {
                $kept[] = $prop.': '.$value;
            }
        }

        return $kept === [] ? null : implode('; ', $kept);
    }

    private static function isSafeColor(string $value): bool
    {
        $value = trim($value);

        return (bool) preg_match('/^(#([0-9a-f]{3}|[0-9a-f]{6})|rgb\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*\)|rgba\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*(?:0|0?\.\d+|1(?:\.0)?)\s*\)|[a-z]{3,20})$/i', $value);
    }

    private static function safeClass(string $class): ?string
    {
        $kept = [];
        foreach (preg_split('/\s+/', trim($class)) ?: [] as $token) {
            if (isset(self::ALLOWED_CLASSES[$token])) {
                $kept[] = $token;
            }
        }

        return $kept === [] ? null : implode(' ', array_unique($kept));
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
