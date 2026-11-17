<?php

namespace Tests\Feature;

use App\Models\Merchant;
use App\Services\Demo\TemporaryDemoProvisioner;
use App\Support\DemoAccount;
use App\Support\PanelBranding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DemoBrandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_and_real_merchants_keep_the_normal_branding(): void
    {
        $this->assertFalse(PanelBranding::isDemo());
        $this->assertSame(config('branding.logo'), PanelBranding::get('logo'));
        $this->assertSame(config('branding.colors.primary'), PanelBranding::get('colors.primary'));

        $this->actingAs($this->merchant('owner@example.com'), 'merchant');

        $this->assertFalse(PanelBranding::isDemo());
        $this->assertSame(config('branding.logo'), PanelBranding::get('logo'));
    }

    public function test_demo_visitors_get_the_cold_storage_crm_branding(): void
    {
        $this->actingAs($this->merchant(DemoAccount::temporaryEmailForSession((string) Str::uuid())), 'merchant');

        $this->assertTrue(PanelBranding::isDemo());
        $this->assertSame('Cold Storage CRM', PanelBranding::get('name'));
        $this->assertSame('images/cold-storage-crm-logo.svg', PanelBranding::get('logo'));
        $this->assertSame('images/cold-storage-crm-icon.svg', PanelBranding::get('favicon'));
        $this->assertSame('#0b6bcb', PanelBranding::get('colors.primary'));
        // Keys without a demo override fall back to the normal branding.
        $this->assertSame(config('branding.login_visual'), PanelBranding::get('login_visual'));
    }

    private function merchant(string $email): Merchant
    {
        return Merchant::query()->create([
            'id' => Str::uuid()->toString(),
            'email' => $email,
            'name' => 'Test Merchant',
            'address_line_1' => 'Somewhere',
            'city' => 'Karachi',
            'status' => Merchant::STATUS_VERIFIED,
            'is_active' => true,
            'password' => 'Secret@123456',
        ]);
    }
}
