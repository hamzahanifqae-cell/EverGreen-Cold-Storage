<?php

namespace App\Support;

class DomainConfig
{
    public static function splitEnabled(): bool
    {
        return filled(config('domains.landing')) && filled(config('domains.app'));
    }

    public static function landingHost(): ?string
    {
        return self::normalizeHost(config('domains.landing'));
    }

    public static function appHost(): ?string
    {
        return self::normalizeHost(config('domains.app'));
    }

    public static function landingUrl(): string
    {
        return rtrim((string) (config('domains.landing_url') ?: config('app.url')), '/');
    }

    public static function appUrl(): string
    {
        return rtrim((string) (config('domains.app_url') ?: config('app.url')), '/');
    }

    public static function isLandingHost(?string $host): bool
    {
        $landing = self::landingHost();

        if (! $landing || ! $host) {
            return false;
        }

        // Exact match only (CRM host is APP_DOMAIN, e.g. app.evergreencold.store).
        return self::normalizeHost($host) === $landing;
    }

    public static function isAppHost(?string $host): bool
    {
        $app = self::appHost();

        if (! $app || ! $host) {
            return false;
        }

        return self::normalizeHost($host) === $app;
    }

    public static function normalizeHost(?string $host): ?string
    {
        if ($host === null || $host === '') {
            return null;
        }

        $host = strtolower(trim($host));
        $host = preg_replace('#:\d+$#', '', $host) ?: $host;

        return $host;
    }
}
