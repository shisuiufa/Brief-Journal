<?php

namespace App\Http\Cookies;

use Symfony\Component\HttpFoundation\Cookie;

final class RefreshTokenCookie
{
    private const string NAME = 'refresh_token';

    public function name(): string
    {
        return self::NAME;
    }

    public function make(string $token): Cookie
    {
        return cookie(
            name: self::NAME,
            value: $token,
            minutes: 60 * 24 * 30,
            path: '/',
            domain: config('session.domain'),
            secure: app()->environment('production'),
            httpOnly: true,
            raw: false,
            sameSite: 'lax',
        );
    }

    public function forget(): Cookie
    {
        return cookie()->forget(
            name: self::NAME,
            path: '/',
            domain: config('session.domain'),
        );
    }
}
