<?php

namespace App\Services;

class HtmlSanitizer
{
    protected array $allowedTags = [
        'p', 'br', 'strong', 'em', 'u', 's', 'del', 'ins',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li',
        'blockquote', 'pre', 'code',
        'a', 'img',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'div', 'span',
    ];

    protected array $allowedAttributes = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan'],
        '*' => ['class', 'id'],
    ];

    public function clean(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        $html = $this->removeScriptTags($html);
        $html = $this->removeEventHandlers($html);
        $html = strip_tags($html, $this->getAllowedTagsString());
        $html = $this->removeUnsafeAttributes($html);

        return $html;
    }

    protected function removeScriptTags(string $html): string
    {
        return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html) ?? $html;
    }

    protected function removeEventHandlers(string $html): string
    {
        return preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html) ?? $html;
    }

    protected function removeUnsafeAttributes(string $html): string
    {
        return preg_replace_callback(
            '/<(\w+)\s+([^>]*)>/i',
            function ($matches) {
                $tag = strtolower($matches[1]);
                $attrs = $matches[2];

                if (!in_array($tag, $this->allowedTags)) {
                    return $matches[0];
                }

                $allowedForTag = array_merge(
                    $this->allowedAttributes['*'] ?? [],
                    $this->allowedAttributes[$tag] ?? []
                );

                $cleanedAttrs = $this->filterAttributes($attrs, $allowedForTag);

                return "<{$tag} {$cleanedAttrs}>";
            },
            $html
        ) ?? $html;
    }

    protected function filterAttributes(string $attrs, array $allowed): string
    {
        preg_match_all('/(\w+)\s*=\s*["\']([^"\']*)["\']/', $attrs, $matches, PREG_SET_ORDER);

        $result = [];
        foreach ($matches as $match) {
            $attrName = strtolower($match[1]);
            $attrValue = $match[2];

            if (!in_array($attrName, $allowed)) {
                continue;
            }

            if ($attrName === 'href' && preg_match('/^\s*javascript:/i', $attrValue)) {
                continue;
            }

            if ($attrName === 'src' && preg_match('/^\s*javascript:/i', $attrValue)) {
                continue;
            }

            $result[] = "{$attrName}=\"" . htmlspecialchars($attrValue, ENT_QUOTES, 'UTF-8') . "\"";
        }

        return implode(' ', $result);
    }

    protected function getAllowedTagsString(): string
    {
        return '<' . implode('><', $this->allowedTags) . '>';
    }
}
