<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\EditProfile;
use App\Http\Middleware\DemoSessionTimeout;
use App\Http\Middleware\EnsureStaffIsVerified;
use App\Http\Middleware\SetPanelAuthDefaults;
use App\Support\DomainConfig;
use App\Support\PanelBranding;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MerchantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {

        $panel = $panel
            ->id('merchant')
            ->path('merchant')
            ->authGuard('merchant')
            ->authPasswordBroker('merchants')
            ->default()
            ->login(Login::class)
            ->passwordReset()
            ->colors([
                'primary' => Color::generatePalette(config('branding.colors.primary')),
                'gray' => Color::Slate,
            ])
            ->defaultThemeMode(ThemeMode::Dark)
            ->favicon(fn () => asset(PanelBranding::get('favicon')))
            ->brandLogo(function () {
                $merchant = Filament::auth()->user();
                $defaultLogo = asset(PanelBranding::get('logo'));

                // Demo visitors always see the Cold Storage CRM logo.
                if (PanelBranding::isDemo()) {
                    return $defaultLogo;
                }

                if (! $merchant) {
                    return $defaultLogo;
                }

                if (! $merchant->logo) {
                    return $defaultLogo;
                }

                $path = $merchant->logo->photo_url;

                if (! Storage::disk('public')->exists($path)) {
                    return $defaultLogo;
                }

                return asset('storage/'.$path);
            })
            ->brandName(fn () => PanelBranding::isDemo()
                ? PanelBranding::get('name')
                : (Filament::auth()->user()?->name ?? config('branding.name')))
            ->brandLogoHeight('2.5rem')
            ->darkModeBrandLogo(fn () => PanelBranding::isDemo()
                ? asset(PanelBranding::get('logo_dark'))
                : asset(config('branding.logo_dark')))
            ->userMenuItems([
                Action::make('editProfile')
                    ->label('Edit profile')
                    ->icon('heroicon-o-user')
                    ->url(fn () => EditProfile::getUrl(panel: 'merchant'))
                    ->sort(-10), // appears above theme switcher & logout
            ])

            ->viteTheme('resources/css/filament/merchant/theme.css')
            // Installable app: manifest, theme colour and service worker
            ->renderHook(
                'panels::head.end',
                fn () => view('filament.pwa-head')
            )
            ->renderHook(
                'panels::head.end',
                function () {
                    /*$merchant = auth('merchant')->user();

                    $settings = $merchant->settings;
                    return [
                        'primary' => $settings?->primary_color ?? '#6d28d9',
                        'secondary' => $settings?->secondary_color ?? '#9333ea',
                    ];*/

                    $merchant = Filament::auth()->user();
                    // Demo visitors get the Cold Storage CRM colours, not the merchant's saved ones.
                    $settings = PanelBranding::isDemo() ? null : $merchant?->settings;

                    return view('filament.merchant.theme-vars', [
                        'primary' => Color::generatePalette($settings?->primary_color ?? PanelBranding::get('colors.primary')),
                        'success' => Color::generatePalette($settings?->success_color ?? PanelBranding::get('colors.success')),
                        'secondary' => Color::generatePalette($settings?->secondary_color ?? PanelBranding::get('colors.secondary')),
                        'danger' => Color::generatePalette($settings?->danger_color ?? PanelBranding::get('colors.danger')),
                        'warning' => Color::generatePalette($settings?->warning_color ?? PanelBranding::get('colors.warning')),
                        'default' => Color::generatePalette($settings?->default_color ?? PanelBranding::get('colors.default')),
                        'sidebarPrimary' => $settings?->primary_color ?? PanelBranding::get('colors.primary'),
                        'sidebarSecondary' => $settings?->secondary_color ?? PanelBranding::get('colors.accent'),
                    ]);
                })
            ->navigationGroups([
                'Procurement',
                'Inventory',
                'Assets',
                'Reportings',
                'Cold Storage',
                'Configurations',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->resourceCreatePageRedirect('index')
            ->resourceEditPageRedirect('index')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetPanelAuthDefaults::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                EnsureStaffIsVerified::class,
                DemoSessionTimeout::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])

            ->renderHook(
                'panels::body.start',
                fn () => view('filament.demo-banner')
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn () => view('filament.auth.forgot-password-link')
            )
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.sidebar-hover')
            )
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.dashboard-product-variant-select-script')
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn () => view('filament.show-hidden-modules-toggle')
            )

            ->globalSearch(false);

        if ($appHost = DomainConfig::appHost()) {
            $panel->domain($appHost);
        }

        return $panel;
    }
}
