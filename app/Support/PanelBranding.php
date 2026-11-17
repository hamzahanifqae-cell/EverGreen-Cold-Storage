<?php

namespace App\Support;

use Throwable;

/**
 * Resolves the merchant panel's branding.
 *
 * Real merchants get the normal branding (config/branding.php). Visitors who
 * started a temporary demo from the marketing site get the neutral
 * "Cold Storage CRM" branding (config/branding.php → demo), so the demo
 * matches the landing page instead of showing a real customer's brand.
 */
class PanelBranding
{
    public static function isDemo(): bool
    {
        try {
            return DemoAccount::isDemoMerchant();
        } catch (Throwable) {
            // No auth/session yet (e.g. during boot or on the console).
            return false;
        }
    }

    /**
     * Read a branding value, preferring the demo override for demo visitors.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (self::isDemo()) {
            $override = config("branding.demo.{$key}");

            if ($override !== null) {
                return $override;
            }
        }

        return config("branding.{$key}", $default);
    }
}
