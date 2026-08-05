<?php

namespace App\Support;

use Illuminate\Http\Request;

class ContactBotGuard
{
    public const MIN_SECONDS = 3;

    public const MAX_SECONDS = 7200;

    /** Detect automated or suspicious submissions without CAPTCHA. */
    public function isBot(Request $request): bool
    {
        if ($this->honeypotTriggered($request)) {
            return true;
        }

        if ($this->timingInvalid()) {
            return true;
        }

        if ($this->messageLooksSpammy($request->input('message', ''))) {
            return true;
        }

        if ($this->nameLooksSpammy($request->input('name', ''))) {
            return true;
        }

        return false;
    }

    public function stampFormSession(): void
    {
        session(['contact_form_started_at' => time()]);
    }

    private function honeypotTriggered(Request $request): bool
    {
        return $request->filled('extra_field')
            || $request->filled('website_url')
            || $request->filled('company');
    }

    private function timingInvalid(): bool
    {
        $started = session('contact_form_started_at');

        if (! is_numeric($started)) {
            return true;
        }

        $elapsed = time() - (int) $started;

        return $elapsed < self::MIN_SECONDS || $elapsed > self::MAX_SECONDS;
    }

    private function messageLooksSpammy(string $message): bool
    {
        if ($message === '') {
            return false;
        }

        if (preg_match_all('/https?:\/\//i', $message) > 3) {
            return true;
        }

        if (preg_match('/\b(viagra|casino|crypto pump|seo service|backlinks)\b/i', $message)) {
            return true;
        }

        return false;
    }

    private function nameLooksSpammy(string $name): bool
    {
        return (bool) preg_match('/https?:\/\//i', $name);
    }
}
