<?php

namespace Tests\Feature;

use Tests\TestCase;

class DomainSplitTest extends TestCase
{
    protected function setUp(): void
    {
        // Set before app boot so Filament panel ->domain() sees APP_DOMAIN.
        putenv('LANDING_DOMAIN=evergreencold.store');
        putenv('APP_DOMAIN=app.evergreencold.store');
        putenv('LANDING_URL=https://evergreencold.store');
        putenv('APP_URL=https://app.evergreencold.store');
        $_ENV['LANDING_DOMAIN'] = 'evergreencold.store';
        $_ENV['APP_DOMAIN'] = 'app.evergreencold.store';
        $_ENV['LANDING_URL'] = 'https://evergreencold.store';
        $_ENV['APP_URL'] = 'https://app.evergreencold.store';
        $_SERVER['LANDING_DOMAIN'] = 'evergreencold.store';
        $_SERVER['APP_DOMAIN'] = 'app.evergreencold.store';
        $_SERVER['LANDING_URL'] = 'https://evergreencold.store';
        $_SERVER['APP_URL'] = 'https://app.evergreencold.store';

        parent::setUp();
    }

    protected function tearDown(): void
    {
        putenv('LANDING_DOMAIN');
        putenv('APP_DOMAIN');
        putenv('LANDING_URL');
        unset($_ENV['LANDING_DOMAIN'], $_ENV['APP_DOMAIN'], $_ENV['LANDING_URL'], $_SERVER['LANDING_DOMAIN'], $_SERVER['APP_DOMAIN'], $_SERVER['LANDING_URL']);

        parent::tearDown();
    }

    public function test_apex_serves_landing_pages(): void
    {
        $this->get('http://evergreencold.store/')
            ->assertOk()
            ->assertSee('EverGreen Cold Storage');

        $this->get('http://evergreencold.store/how-it-works')
            ->assertOk()
            ->assertSee('How EverGreen works');
    }

    public function test_apex_redirects_crm_paths_to_app_host(): void
    {
        $this->get('http://evergreencold.store/merchant/login')
            ->assertRedirect('https://app.evergreencold.store/merchant/login');
    }

    public function test_app_root_redirects_to_merchant_login(): void
    {
        $this->get('http://app.evergreencold.store/')
            ->assertRedirect('/merchant/login');
    }

    public function test_app_how_it_works_redirects_to_apex(): void
    {
        $this->get('http://app.evergreencold.store/how-it-works')
            ->assertRedirect('https://evergreencold.store/how-it-works');
    }
}
