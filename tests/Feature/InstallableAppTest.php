<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstallableAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_both_crm_panels_are_installable_on_phones_only(): void
    {
        foreach (['/merchant/login', '/staff/login'] as $url) {
            $this->get($url)
                ->assertOk()
                // No static manifest link: desktop browsers must not offer "Install app"
                ->assertDontSee('<link rel="manifest"', false)
                // The manifest is added by script on phones and tablets only
                ->assertSee("link.rel = 'manifest'", false)
                ->assertSee('if (!isMobile) return;', false)
                ->assertSee('manifest.json', false)
                // A phone that has opened the installed app is remembered, so the banner stays hidden there
                ->assertSee("localStorage.setItem('eg-app-installed', '1')", false)
                ->assertSee('serviceWorker.register(', false)
                ->assertSee('name="theme-color" content="#0f766e"', false)
                // Our own "Install app" banner, since Chrome's popup only appears once per phone
                ->assertSee('data-eg-install', false)
                ->assertSee('beforeinstallprompt', false);
        }
    }

    public function test_manifest_is_valid_and_its_icons_exist(): void
    {
        $manifest = json_decode(file_get_contents(public_path('manifest.json')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('EverGreen Cold Storage', $manifest['name']);
        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame('/merchant', $manifest['start_url']);

        $sizes = [];
        foreach ($manifest['icons'] as $icon) {
            $path = public_path(ltrim($icon['src'], '/'));
            $this->assertFileExists($path);
            [$width, $height] = getimagesize($path);
            $this->assertSame($icon['sizes'], "{$width}x{$height}", $icon['src']);
            $sizes[$icon['purpose']][] = $icon['sizes'];
        }

        // Android needs 192 and 512 icons, plus a maskable one for adaptive (round/squircle) icons.
        $this->assertContains('192x192', $sizes['any']);
        $this->assertContains('512x512', $sizes['any']);
        $this->assertContains('512x512', $sizes['maskable']);
    }

    public function test_service_worker_only_handles_page_loads_and_never_caches_crm_data(): void
    {
        $sw = file_get_contents(public_path('sw.js'));

        $this->assertStringContainsString("event.request.mode !== 'navigate'", $sw);
        $this->assertStringContainsString("'/offline.html'", $sw);
        $this->assertStringNotContainsString('cache.put', $sw);
        $this->assertFileExists(public_path('offline.html'));
    }
}
