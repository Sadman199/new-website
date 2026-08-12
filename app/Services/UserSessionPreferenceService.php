<?php

namespace App\Services;

use App\Models\User;
use Symfony\Component\HttpFoundation\Cookie;

class UserSessionPreferenceService
{
    public function applyPreferredCountry(User $user): ?Cookie
    {
        if (! $user->preferred_country_slug) {
            return null;
        }

        session(['preferred_country' => $user->preferred_country_slug]);

        return cookie('preferred_country', $user->preferred_country_slug, 60 * 24 * 365);
    }

    public function persistPreferredCountry(User $user, ?string $slug): ?Cookie
    {
        $user->preferred_country_slug = $slug ?: null;
        $user->save();

        if (! $slug) {
            return null;
        }

        session(['preferred_country' => $slug]);

        return cookie('preferred_country', $slug, 60 * 24 * 365);
    }

    public function attachCookieToResponse($response, ?Cookie $cookie)
    {
        if ($cookie) {
            return $response->withCookie($cookie);
        }

        return $response;
    }
}
